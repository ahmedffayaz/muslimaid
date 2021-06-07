<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\ImportedCategory;
use App\Models\StoreCashback;
use App\Models\StoreImage;
use App\Models\UserCashback;
use App\Models\Voucher;
use App\Models\ExitClick;
use App\Models\ImporterSetting;
use App\Models\SiteSetting;
use App\Models\CashbackStatusChange;
use App\Models\Network;
use Illuminate\Support\Facades\DB;

class Importer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //fetching importer settings
        $network = Network::where('id',1)->first();
        $setting = ImporterSetting::where('network_id',1)->first();
        

        //importing advertisers/stores/merchents

        if($setting->import_stores == 1){
            $total_records= 1;
            $fetched_records= 0;
            $page = 1;

            while($fetched_records < $total_records){

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, 'https://advertiser-lookup.api.cj.com/v2/advertiser-lookup?requestor-cid='.$network->requestor_cid.'&advertiser-ids=joined&records-per-page=100&page-number='.$page);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

                $headers = array();
                $headers[] = 'Authorization: Bearer '.$network->token;
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
                }
                curl_close($ch);      

                $data = simplexml_load_string($result);
                $array = json_decode(json_encode($data), TRUE);

                $adverts = $data->advertisers->advertiser;

                foreach ($adverts as $advertiser) {

                    try{

                    $store = Store::where('advertiser_id',$advertiser->{'advertiser-id'})->first();
                    if((!$store) && $advertiser->{'account-status'} == 'Active'){

                        $store = Store::create([
                            'name'         => $advertiser->{'advertiser-name'},
                            'slug'         => \Str::slug($advertiser->{'advertiser-name'}),
                            'advertiser_id'=> $advertiser->{'advertiser-id'},
                            'network_id'   => 1,
                            'tracking_url' => $advertiser->{'program-url'},
                            'store_url'    => $advertiser->{'program-url'},
                            'status' => 'error',
                            'status_description' =>'',
                            'network_status' =>$advertiser->{'account-status'}
                        ]);
                        
                        $ch = curl_init();

                        curl_setopt($ch, CURLOPT_URL, 'https://link-search.api.cj.com/v2/link-search?website-id='.$network->website_id.'&link-type=banner&advertiser-ids='.$advertiser->{'advertiser-id'});
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


                        $headers = array();
                        $headers[] = 'Authorization: Bearer 1jkkfyp5r28p43ghpsx4p1h588';
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                        $link_result = curl_exec($ch);
                        if (curl_errno($ch)) {
                            echo 'Error:' . curl_error($ch);
                        }
                        curl_close($ch);

                        $link_data = simplexml_load_string($link_result);
                        $link_array = json_decode(json_encode($link_data), TRUE);
                       
                        $destination_url = $advertiser->{'program-url'};

                        $link_found =0;
                        $link_url ='#';
                        if($link_data->links->link){
                        foreach ($link_data->links->link as $link) {
                           
                            if(!strcmp($link->destination , $destination_url)){
                                $link_found =1;
                                $link_url = $link->clickUrl;
                            }

                        }

                        if($link_found){
                            $store->tracking_url = $link_url;
                        }else{
                            $store->tracking_url = $link_data->links->link[0]->clickUrl;
                        }

                        // $store->tracking_url = $link_url;
                        $store->update();
                        }
                        foreach ($advertiser->actions->action as $action) {


                            if($action->commission->default){
                                $c_type = \Str::contains($action->commission->default, '%') ? 'percentage' : 'fixed';
                                if($c_type=='percentage'){
                                    $sale_commission=  str_replace('%','',$action->commission->default);
                                    $currency = null;
                                  }else{
                                      $splited = explode(" ", $action->commission->default);
                                      $sale_commission = $splited[1];
                                    $currency = $splited[0];

                                  }
                                $cb = StoreCashback::where('store_id',$store->id)->where('type',$c_type)->where('sale_commission',$action->commission->default)->first();
                                if($cb){
                                    $cb->update([
                                        'detail' => $cb->detail.', '.$action->name.' default',
                                        'network_detail' => $cb->network_detail.', '.$action->name.' default',
                                    ]);
                                }else{
                                    $cashback = StoreCashback::create([
                                        'type' => $c_type,
                                        'image'           => '#',
                                        'click_url'       =>  '#',
                                        'sale_commission' => $sale_commission,
                                        'currency'=>$currency,
                                        'cashback_name' => $action->name,
                                        'detail' => $action->name.' default',
                                        'network_detail' => $action->name.' default',
                                        'store_id' => $store->id,
                                        'default' => 1
                                    ]);
                                }
                                
                            }
                            if($action->commission->itemlist){
                                foreach($action->commission->itemlist as $item){
                                $c_type= \Str::contains($item, '%') ? 'percentage' : 'fixed';

                                if($c_type=='percentage'){
                                    $sale_commission=  str_replace('%','',$item);
                                    $currency = null;

                                  }else{
                                    $splited = explode(" ", $item);
                                    $sale_commission = $splited[1];
                                    $currency = $splited[0];

                                  }


                                    $cb = StoreCashback::where('store_id',$store->id)->where('type',$c_type)->where('sale_commission',$item)->first();
                                    if($cb){
                                        $cb->update([
                                           
                                            'detail' => $cb->detail.', '.$action->name.' '.$item->attributes()->name,
                                            'network_detail' => $cb->network_detail.', '.$action->name.' '.$item->attributes()->name,
                                        ]);
                                    }else{
                                   
                                    $cashback = StoreCashback::create([
                                        'type' => $c_type,
                                        'image'           => '#',
                                        'click_url'       =>  '#',
                                        'sale_commission' => $sale_commission,
                                        'currency'=>$currency,
                                        'cashback_name' => $action->name,
                                        'detail' => $action->name.' '.$item->attributes()->name,
                                        'network_detail' => $action->name.' '.$item->attributes()->name,
                                        'store_id' => $store->id,
                                    ]);
                                }
        
                                }
                            }
                           
                        }
                                                        
                        if(!empty($advertiser->{'primary-category'}->{'parent'})){

                            $category_parent = ImportedCategory::where('name',$advertiser->{'primary-category'}->{'parent'})->first();
        
                            if(!$category_parent){
                                $category_parent = new ImportedCategory();
                                $category_parent->name = $advertiser->{'primary-category'}->{'parent'};
                                $category_parent->network_id = 1;
                                $category_parent->save();
                            }
        
                            DB::table('category_store')->insert([
                                'store_id'=>$store->id,
                                'category_id'=> $category_parent->mapped_to ?? 0,
                                'network_category_id'=> $category_parent->id,
        
                            ]);
        
                        }
                            
                        if(!empty($advertiser->{'primary-category'}->{'child'})){
                            
                            $category_child = ImportedCategory::where('name',$advertiser->{'primary-category'}->{'child'})->first();

                            if(!$category_child){

                                $category_child = new ImportedCategory();
                                $category_child->name = $advertiser->{'primary-category'}->{'child'};
                                $category_child->network_id = 1;
                                $category_child->save();

                            }
                            DB::table('category_store')->insert([
                                'store_id'=>$store->id,
                                'category_id'=> $category_child->mapped_to ?? 0,
                                'network_category_id'=> $category_child->id,
                            ]);
                        }

                        // $storelogo = StoreImage::create([
                        //     'store_id'=>$store->id,
                        //     'title' => 'logo',
                        //     'image' =>'default.png',
                        //     'image_type'=>'store_logo',
                        //     'is_uploaded'=>0
                        // ]);
                    
                    }else{

                        if($store->override_cashback){
                            
                            $ch = curl_init();

                            curl_setopt($ch, CURLOPT_URL, 'https://link-search.api.cj.com/v2/link-search?website-id=100179843&link-type=banner&advertiser-ids='.$advertiser->{'advertiser-id'});
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


                            $headers = array();
                            $headers[] = 'Authorization: Bearer 1jkkfyp5r28p43ghpsx4p1h588';
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                            $link_result = curl_exec($ch);
                            if (curl_errno($ch)) {
                                echo 'Error:' . curl_error($ch);
                            }
                            curl_close($ch);

                            $link_data = simplexml_load_string($link_result);
                            $link_array = json_decode(json_encode($link_data), TRUE);
                        
                            $destination_url = $advertiser->{'program-url'};

                            $link_found =0;
                            $link_url ='#';
                            if($link_data->links->link){
                                foreach ($link_data->links->link as $link) {
                                
                                    if(!strcmp($link->destination , $destination_url)){
                                        $link_found =1;
                                        $link_url = $link->clickUrl;
                                    }

                                }

                                if($link_found){
                                    $store->tracking_url = $link_url;
                                }else{
                                    $store->tracking_url = $link_data->links->link[0]->clickUrl;
                                }

                                // $store->tracking_url = $link_url;
                                $store->update();
                            }
                            if(count($store->cashbacks)){
                                $store->cashbacks()->delete();
                            }
    
                            foreach ($advertiser->actions->action as $action) {

                                if($action->commission->default){
                                    $c_type = \Str::contains($action->commission->default, '%') ? 'percentage' : 'fixed';
                                    
                                    if($c_type=='percentage'){
                                        $sale_commission=  str_replace('%','',$action->commission->default);
                                        $currency = null;

                                      }else{
                                          $splited = explode(" ", $action->commission->default);
                                          $sale_commission = $splited[1];
                                          $currency = $splited[0];

                                      }
                                    $cb = StoreCashback::where('store_id',$store->id)->where('type',$c_type)->where('sale_commission',$sale_commission)->first();
                                    if($cb){
                                        $cb->update([
                                            'detail' => $cb->detail.', '.$action->name.' default',
                                            'network_detail' => $cb->network_detail.', '.$action->name.' default',
                                        ]);
                                    }else{

                                        $cashback = StoreCashback::create([
                                            'type' => $c_type,
                                            'image'           => '#',
                                            'click_url'       =>  '#',
                                            'sale_commission' => $sale_commission,
                                            'currency'=>$currency,
                                            'cashback_name' => $action->name,
                                            'detail' => $action->name.' default',
                                            'network_detail' => $action->name.' default',
                                            'store_id' => $store->id,
                                            'default' => 1
                                        ]);
                                    }
                                    
                                }
                                if($action->commission->itemlist){

                                    foreach($action->commission->itemlist as $item){
                                        $c_type= \Str::contains($item, '%') ? 'percentage' : 'fixed';
                                        
                                        if($c_type=='percentage'){
                                            $sale_commission=  str_replace('%','',$item);
                                            $currency = null;

                                          }else{
                                            $splited = explode(" ", $item);
                                            $sale_commission = $splited[1];
                                            $currency = $splited[0];

                                          }
                                        $cb = StoreCashback::where('store_id',$store->id)->where('type',$c_type)->where('sale_commission',$sale_commission)->first();
                                        if($cb){
                                            $cb->update([
                                            
                                                'detail' => $cb->detail.', '.$action->name.' '.$item->attributes()->name,
                                                'network_detail' => $cb->network_detail.', '.$action->name.' '.$item->attributes()->name,
                                            ]);
                                        }else{

                                    
                                        $cashback = StoreCashback::create([
                                            'type' => $c_type,
                                            'image'           => '#',
                                            'click_url'       =>  '#',
                                            'sale_commission' =>  $sale_commission,
                                            'currency'=>$currency,
                                            'cashback_name' => $action->name,
                                            'detail' => $action->name.' '.$item->attributes()->name,
                                            'network_detail' => $action->name.' '.$item->attributes()->name,
                                            'store_id' => $store->id,
                                        ]);
                                    }
            
                                    }
                                }
                            
                            }

                        }

                        if($store->override_categories){
                            if(count($store->categories)){
                                $store->categories()->delete();
                            }

                            if(!empty($advertiser->{'primary-category'}->{'parent'})){

                                $category_parent = ImportedCategory::where('name',$advertiser->{'primary-category'}->{'parent'})->first();
            
                                if(!$category_parent){
                                    $category_parent = new ImportedCategory();
                                    $category_parent->name = $advertiser->{'primary-category'}->{'parent'};
                                    $category_parent->network_id = 1;
                                    $category_parent->save();
                                }
            
                                DB::table('category_store')->insert([
                                    'store_id'=>$store->id,
                                    'category_id'=> $category_parent->mapped_to ?? 0,
                                    'network_category_id'=> $category_parent->id,
            
                                ]);
            
                            }
                                
                            if(!empty($advertiser->{'primary-category'}->{'child'})){
                                
                                $category_child = ImportedCategory::where('name',$advertiser->{'primary-category'}->{'child'})->first();
    
                                if(!$category_child){
    
                                    $category_child = new ImportedCategory();
                                    $category_child->name = $advertiser->{'primary-category'}->{'child'};
                                    $category_child->network_id = 1;
                                    $category_child->save();
    
                                }
                                DB::table('category_store')->insert([
                                    'store_id'=>$store->id,
                                    'category_id'=> $category_child->mapped_to ?? 0,
                                    'network_category_id'=> $category_child->id,
                                ]);
                            }

                        }
                        
                    }


                    $store->status_description = '';
                    $logo_exits = StoreImage::where([ 'store_id'=>$store->id, 'title'=>'logo' ])->first();
                    if($logo_exits && $store->cashbacks && $store->categories && $store->description){
                        $store->update(['status'=>'pending review']);
                    }else{
                        $store->status = 'error';
                        if(!$logo_exits){
                            $store->status_description = $store->status_description.' '.'image,';
                        }
                        if(!$store->cashbacks){
                            $store->status_description = $store->status_description.' '.'cashbacks,';
                        }
                        if(!$store->categories){
                            $store->status_description = $store->status_description.' '.'categories,';
                        }
                        if(!$store->description){
                            $store->status_description = $store->status_description.' '.'description';
                        }
                        $store->update();
                    }

                    }
                    catch(\Execption $e)
                    {
                        flash()->error('Error while running importer');
                        return redirect()->route('admin.stores.index');
                    }          

                }

                $total_records = $data->{'advertisers'}->attributes()->{'total-matched'};
                $fetched_records= $fetched_records + $data->{'advertisers'}->attributes()->{'records-returned'};
                $page++;
            }
        }

        if($setting->import_cashbacks == 1){

            //importing cashbacks

            $cashback_percent_setting = SiteSetting::where('type','cashback_percentage')->first()->value;
            $total_callback = 0;
            $beforePostingDate = date('Y-m-d\TH:i:s\z');
            $sincePostingDate = date('Y-m-d\TH:i:s\z', strtotime('-31 days'));
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://commissions.api.cj.com/query',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{ publisherCommissions(forPublishers: ["'.$network->requestor_cid.'"], sincePostingDate:"'.$sincePostingDate.'",beforePostingDate:"'.$beforePostingDate.'"){count payloadComplete records {actionTrackerName websiteName advertiserName advertiserId pubCommissionAmountPubCurrency postingDate pubCommissionAmountUsd saleAmountPubCurrency actionStatus validationStatus clickDate eventDate shopperId   items { quantity perItemSaleAmountPubCurrency totalCommissionPubCurrency  }}}}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$network->token,
                'Content-Type: application/json'
            ),
            ));

            $result = curl_exec($curl);
            if (curl_errno($curl)) {
                echo 'Error:' . curl_error($curl);
            }
            curl_close($curl);
            $result_array = json_decode($result, TRUE);

            if(array_key_exists('data',$result_array) 
            && array_key_exists('publisherCommissions',$result_array['data']) 
            && array_key_exists('records',$result_array['data']['publisherCommissions'])){
                $cashback_percent = 0;
                foreach($result_array['data']['publisherCommissions']['records'] as $cashback){

                    $store = Store::where('advertiser_id',$cashback['advertiserId'])->first();
                    $click = ExitClick::where('id',$cashback['shopperId'])->first();

                    if($click){
                        $click_id = $cashback['shopperId'];
                        $cashback_percent = $click->current_cashback_percentage;
                        
                    }else{
                        $new_click = ExitClick::create(['store_id'=>$store->id,
                        'user_id'=>1,
                        'status'=>'pending',
                        'exit_url'=>'#',
                        'current_cashback_percentage'=>$cashback_percent_setting]);
                        $click_id = $new_click->id;
                        $cashback_percent = $cashback_percent_setting;

                    }
                    $cashback_amount_for_user = ($cashback['pubCommissionAmountPubCurrency']/100) * $cashback_percent;
                    $commission_exist = UserCashback::where('exit_click_id',$cashback['shopperId'])->first();
                    $status = '';
                    if($cashback['actionStatus'] == 'new' || $cashback['actionStatus'] == 'extended' || $cashback['actionStatus'] == 'locked'){
                        $status = 1;
                    }else if($cashback['actionStatus'] == 'closed' && $cashback['ValidationStatus'] == 'ACCEPTED'){
                        $status = 3;
                    }else if($cashback['actionStatus'] == 'closed' && $cashback['ValidationStatus'] == 'DECLINED'){
                        $status = 2;
                    }
                    if(!$commission_exist){
                    
                        $commission = UserCashback::create([
                            'store_id'=>$store->id,
                            'user_id'=> $click->user_id ?? 01,
                            'exit_click_id'=>$click_id,
                            'amount'=>round($cashback_amount_for_user,2),
                            'network_commission'=>$cashback['pubCommissionAmountPubCurrency'],
                            'order_value'=>$cashback['saleAmountPubCurrency'],
                            'status'=>$status,
                            'event_date'=> \Carbon\Carbon::parse($cashback['eventDate'])->toDateTimeString(),
                            'click_date'=> \Carbon\Carbon::parse($cashback['clickDate'])->toDateTimeString(),
                        ]);  

                        $change_status = CashbackStatusChange::create([
                            'user_cashback_id'=>$commission->id,
                            'cashback_status_id'=>$status
        
                        ]);

                    }else{
                        if($commission_exist->status!= $status){
                            $change_status = CashbackStatusChange::create([
                                'user_cashback_id'=>$commission_exist->id,
                                'cashback_status_id'=>$status
            
                            ]);
                        }
                        
                        $commission_exist->update([
                            'store_id'=>$store->id,
                            'user_id'=> $click->user_id ?? 1,
                            'exit_click_id'=>$click_id,
                            'amount'=>round($cashback_amount_for_user,3),
                            'network_commission'=>$cashback['pubCommissionAmountPubCurrency'],
                            'order_value'=>$cashback['saleAmountPubCurrency'],
                            'status'=>$status,
                            'event_date'=> \Carbon\Carbon::parse($cashback['eventDate'])->toDateTimeString(),
                            'click_date'=> \Carbon\Carbon::parse($cashback['clickDate'])->toDateTimeString(),
                        ]); 

                        
                    }
                }
            }
        }

        if($setting->import_vouchers == 1){
            // importing vouchers
            
            $total_records= 1;
            $fetched_records= 0;
            $page = 1;

            while($fetched_records < $total_records){ 
            
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://link-search.api.cj.com/v2/link-search?website-id='.$network->website_id.'&promotion-type=coupon&advertiser-ids=joined&records-per-page=100&page-number='.$page);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                $headers = array();
                $headers[] = 'Authorization: Bearer '.$network->token;
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                
                $link_result = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close($ch);
                $link_data = simplexml_load_string($link_result);
                $link_array = json_decode(json_encode($link_data), TRUE);
                if(array_key_exists('links',$link_array) && array_key_exists('link',$link_array['links'])){
                    if(array_key_exists(0,$link_array['links']['link'])){
                        $links = $link_array['links']['link'];
                        foreach ($links as $link) {

                            $store = Store::where('advertiser_id',$link['advertiser-id'])->first();
                            $voucher_exist = Voucher::where('link_id',$link['link-id'])->first();

                            if(!$voucher_exist){
                            if(array_key_exists('link-code-html',$link)){
                                $html = $link['link-code-html'];
                                $doc = new \DOMDocument();
                                @$doc->loadHTML($html);
                                $xpath = new \DOMXPath($doc);
                                $img_src = $xpath->evaluate("string(//img/@src)");
                
                            }
                                $voucher = Voucher::create([
                                    'image'           => $img_src,
                                    'click_url'       => empty($link['clickUrl']) ? '#' : $link['clickUrl'],
                                    'sale_commission' => empty($link['sale-commission']) ? NULL: $link['sale-commission'],
                                    'store_id' => $store->id ?? 0,
                                    "description" => empty($link['description']) ? NULL : $link['description'],
                                    "destination" => empty($link['destination']) ? NULL : $link['destination'],
                                    "link_id" => empty($link['link-id']) ? NULL : $link['link-id'],
                                    "link_name" => empty($link['link-name']) ? NULL : $link['link-name'],
                                    "link_type" => empty($link['link-type']) ? NULL : $link['link-type'],
                                    "promotion_end_date" => empty($link['promotion-end-date']) ? NULL : $link['promotion-end-date'],
                                    "promotion_start_date" => empty($link['promotion-start-date']) ? NULL : $link['promotion-start-date'],
                                    "promotion_type" => empty($link['promotion-type']) ? NULL: $link['promotion-type'],
                                    "coupon_code" => empty($link['coupon-code']) ? NULL : $link['coupon-code'],           
                            
                            ]);

                            }
                
                        
                        }
                        
                    }else{

                        $voucher_exist = Voucher::where('link_id',$link['link-id'])->first();
                        if(!$voucher_exist){
                            $link = $link_array['links']['link'];
                            $store = Store::where('advertiser_id',$link['advertiser-id'])->first();                
                            if(array_key_exists('link-code-html',$link)){
                                $html = $link['link-code-html'];
                                $doc = new \DOMDocument();
                                @$doc->loadHTML($html);
                                $xpath = new \DOMXPath($doc);
                                $img_src = $xpath->evaluate("string(//img/@src)");
                
                            }
                            $voucher = Voucher::create([
    
                                    'image'           => $img_src,
                                    'click_url'       => empty($link['clickUrl']) ? '#' : $link['clickUrl'],
                                    'sale_commission' => empty($link['sale-commission']) ? NULL: $link['sale-commission'],
                                    'store_id' => $store->id ?? 0,
                                    "description" => empty($link['description']) ? NULL : $link['description'],
                                    "destination" => empty($link['destination']) ? NULL : $link['destination'],
                                    "link_id" => empty($link['link-id']) ? NULL : $link['link-id'],
                                    "link_name" => empty($link['link-name']) ? NULL : $link['link-name'],
                                    "link_type" => empty($link['link-type']) ? NULL : $link['link-type'],
                                    "promotion_end_date" => empty($link['promotion-end-date']) ? NULL : $link['promotion-end-date'],
                                    "promotion_start_date" => empty($link['promotion-start-date']) ? NULL : $link['promotion-start-date'],
                                    "promotion_type" => empty($link['promotion-type']) ? NULL: $link['promotion-type'],
                                    "coupon_code" => empty($link['coupon-code']) ? NULL : $link['coupon-code'],  
                        ]);

                        }
                    
                    
                    }     
                }
                $total_records = $link_array['links']['@attributes']['total-matched'];
                $fetched_records= $fetched_records + $link_array['links']['@attributes']['records-returned'];
                $page++;
            }

            //importing deals
            $total_records= 1;
            $fetched_records= 0;
            $page = 1;

            while($fetched_records < $total_records){ 

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://link-search.api.cj.com/v2/link-search?website-id=100179843&promotion-type=sale/discount&advertiser-ids=joined&records-per-page=100&page-number='.$page);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                $headers = array();
                $headers[] = 'Authorization: Bearer 1jkkfyp5r28p43ghpsx4p1h588';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                
                $link_result = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close($ch);

                $link_data = simplexml_load_string($link_result);
                $link_array = json_decode(json_encode($link_data), TRUE);  
            
                if(array_key_exists('links',$link_array) && array_key_exists('link',$link_array['links'])){
                    if(array_key_exists(0,$link_array['links']['link'])){
                        $links = $link_array['links']['link'];
                        foreach ($links as $link) {

                        $voucher_exist = Voucher::where('link_id',$link['link-id'])->first();
                        if(!$voucher_exist){
                            $store = Store::where('advertiser_id',$link['advertiser-id'])->first();
                            
                            if(array_key_exists('link-code-html',$link)){
                                $html = $link['link-code-html'];
                                $doc = new \DOMDocument();
                                @$doc->loadHTML($html);
                                $xpath = new \DOMXPath($doc);
                                $img_src = $xpath->evaluate("string(//img/@src)");
                            }
                                $voucher = Voucher::create([
                                    'image'           => $img_src,
                                    'click_url'       => empty($link['clickUrl']) ? '#' : $link['clickUrl'],
                                    'sale_commission' => empty($link['sale-commission']) ? NULL: $link['sale-commission'],
                                    'store_id' => $store->id ?? 0,
                                    "description" => empty($link['description']) ? NULL : $link['description'],
                                    "destination" => empty($link['destination']) ? NULL : $link['destination'],
                                    "link_id" => empty($link['link-id']) ? NULL : $link['link-id'],
                                    "link_name" => empty($link['link-name']) ? NULL : $link['link-name'],
                                    "link_type" => empty($link['link-type']) ? NULL : $link['link-type'],
                                    "promotion_end_date" => empty($link['promotion-end-date']) ? NULL : $link['promotion-end-date'],
                                    "promotion_start_date" => empty($link['promotion-start-date']) ? NULL : $link['promotion-start-date'],
                                    "promotion_type" => empty($link['promotion-type']) ? NULL: $link['promotion-type'],
                                    "coupon_code" => empty($link['coupon-code']) ? NULL : $link['coupon-code'],           
                            ]);
                        }
                        }
                        
                    }else{
                    $voucher_exist = Voucher::where('link_id',$link['link-id'])->first();
                    if(!$voucher_exist){
                            $link = $link_array['links']['link'];
                            $store = Store::where('advertiser_id',$link['advertiser-id'])->first();                
                            if(array_key_exists('link-code-html',$link)){
                                $html = $link['link-code-html'];
                                $doc = new \DOMDocument();
                                @$doc->loadHTML($html);
                                $xpath = new \DOMXPath($doc);
                                $img_src = $xpath->evaluate("string(//img/@src)");
                            }
                            $voucher = Voucher::create([
                                    'image'           => $img_src,
                                    'click_url'       => empty($link['clickUrl']) ? '#' : $link['clickUrl'],
                                    'sale_commission' => empty($link['sale-commission']) ? NULL: $link['sale-commission'],
                                    'store_id' => $store->id ?? 0,
                                    "description" => empty($link['description']) ? NULL : $link['description'],
                                    "destination" => empty($link['destination']) ? NULL : $link['destination'],
                                    "link_id" => empty($link['link-id']) ? NULL : $link['link-id'],
                                    "link_name" => empty($link['link-name']) ? NULL : $link['link-name'],
                                    "link_type" => empty($link['link-type']) ? NULL : $link['link-type'],
                                    "promotion_end_date" => empty($link['promotion-end-date']) ? NULL : $link['promotion-end-date'],
                                    "promotion_start_date" => empty($link['promotion-start-date']) ? NULL : $link['promotion-start-date'],
                                    "promotion_type" => empty($link['promotion-type']) ? NULL: $link['promotion-type'],
                                    "coupon_code" => empty($link['coupon-code']) ? NULL : $link['coupon-code'],
                        ]);
                    }
                    }  
                }
                $total_records = $link_array['links']['@attributes']['total-matched'];
                $fetched_records= $fetched_records + $link_array['links']['@attributes']['records-returned'];
                $page++;
            }

        }
        
             
    }
}
