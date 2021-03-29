<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Category;
use App\Models\StoreCashback;
use Illuminate\Support\Facades\DB;



class ImporterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin-dashboard.importer.index');

    }
    public function import()
    {
        ini_set('max_execution_time', 3000); // 5 minutes

        //importing advertisers/stores/merchents

        $total_records= 1;
        $fetched_records= 0;
        $page = 1;

        while($fetched_records < $total_records){

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://advertiser-lookup.api.cj.com/v2/advertiser-lookup?requestor-cid=5499477&advertiser-ids=joined&records-per-page=50&page-number='.$page);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


        $headers = array();
        $headers[] = 'Authorization: Bearer 1jkkfyp5r28p43ghpsx4p1h588';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
         echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);      


        $data = simplexml_load_string($result);
        $array = json_decode(json_encode($data), TRUE);

        

        // dd($array);

        $adverts = $array['advertisers']['advertiser'];

        foreach ($adverts as $advertiser) {

            try{
            $store = Store::create([
                'name'         => $advertiser['advertiser-name'],
                'advertiser_id'=> $advertiser['advertiser-id'],
                'network_id'   => 1,
                'tracking_url' => $advertiser['program-url'],
                'store_url'    => $advertiser['program-url'],
            ]);

         
            if(!empty($advertiser['primary-category']['parent'])){

                $category_parent = Category::where('name',$advertiser['primary-category']['parent'])->first();

                if(!$category_parent){
                    $category_parent = new Category();
                $category_parent->name = $advertiser['primary-category']['parent'];
                $category_parent->save();

                }

                DB::table('category_store')->insert([
                    'store_id'=>$store->id,
                    'category_id'=> $category_parent->id
                ]);

            }
            if(!empty($advertiser['primary-category']['child'])){
                
                $category_child = Category::where('name',$advertiser['primary-category']['child'])->first();

                if(!$category_child){

                $category_child = new Category();
                $category_child->name = $advertiser['primary-category']['child'];
                $category_child->parent_id = $category_parent->id ?? 0;
                $category_child->save();

                }

                DB::table('category_store')->insert([
                    'store_id'=>$store->id,
                    'category_id'=> $category_child->id
                ]);
            }

        

            //importing cashbacks for current advertiser/store/merchent

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://link-search.api.cj.com/v2/link-search?website-id=100179843&advertiser-ids='.$advertiser['advertiser-id']);
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

            // dd($link_array);

            //saving cashbacks fof current advertiser/store/merchent
           

            if(array_key_exists('links',$link_array) && array_key_exists('link',$link_array['links'])){

                if(array_key_exists(0,$advertiser['actions']['action'])){
                $commission = $advertiser['actions']['action'][0]['commission']['default'];
                $cashback_name = $advertiser['actions']['action'][0]['name'];
                }else{
                $commission = $advertiser['actions']['action']['commission']['default'];
                $cashback_name = $advertiser['actions']['action']['name'];
                }

                if(array_key_exists(0,$link_array['links']['link'])){
                    $link = $link_array['links']['link'][0];
                    
                
                        if(array_key_exists('link-code-html',$link)){

            
                            $html = $link['link-code-html'];
            
                            $doc = new \DOMDocument();
                            @$doc->loadHTML($html);
                            $xpath = new \DOMXPath($doc);
                            $img_src = $xpath->evaluate("string(//img/@src)");
            
                        }
            
                            $cashback = StoreCashback::create([
                            'image'           => $img_src,
                            'click_url'       => $link['clickUrl'] ?? '#',
                            'sale_commission' => $commission,
                            'cashback_name' => $cashback_name,
                            'store_id' => $store->id,
                        
                        ]);
                    
                    
                }else{
                
                        $link = $link_array['links']['link'];
                
                        if(array_key_exists('link-code-html',$link)){
            
                            $html = $link['link-code-html'];
            
                            $doc = new \DOMDocument();
                            @$doc->loadHTML($html);
                            $xpath = new \DOMXPath($doc);
                            $img_src = $xpath->evaluate("string(//img/@src)");
            
                        }
            
                            $cashback = StoreCashback::create([
                            'image'           => $img_src,
                            'click_url'       => $link['clickUrl'] ?? '#',
                            'sale_commission' => $commission,
                            'name' => $cashback_name,
                            'store_id' => $store->id,
                        
                        ]);
                    
                }  
                
            }  
            }
            catch(\Execption $e)
            {
                // continue;
            }          

        }

        $total_records = $array['advertisers']['@attributes']['total-matched'];
        $fetched_records= $fetched_records + $array['advertisers']['@attributes']['records-returned'];
        $page++;
    

    }

    return redirect()->route('admin.stores.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://advertiser-lookup.api.cj.com/v2/advertiser-lookup?requestor-cid=5499477&advertiser-ids=4441431&records-per-page=10');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


        $headers = array();
        $headers[] = 'Authorization: Bearer 1jkkfyp5r28p43ghpsx4p1h588';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
         echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);      


        $data = simplexml_load_string($result);
        $array = json_decode(json_encode($data), TRUE);
        // foreach ($array['advertisers']['advertiser'] as $advertiser) {

        //     echo '<pre>';
        //    print_r($advertiser['primary-category']['parent']);
        //    echo '<br>';
        //    print_r($advertiser['primary-category']['child']);


        // }
        // $store = Store::create([
        //     'name'         => $array['advertisers']['advertiser']['advertiser-name'],
        //     'advertiser_id'=> $array['advertisers']['advertiser']['advertiser-id'],
        //     'network_id'   => 1,
        //     'tracking_url' => $array['advertisers']['advertiser']['program-url'],
        //     'store_url'    => $array['advertisers']['advertiser']['program-url'],
        // ]);

        dd($array);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://link-search.api.cj.com/v2/link-search?website-id=100179843&advertiser-ids=4441431');
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

        // dd($link_result);

        $link_data = simplexml_load_string($link_result);
        $link_array = json_decode(json_encode($link_data), TRUE);

        dd($link_array);



        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $store = Store::where('id',94)->first();
        dd($store->id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function index_backup()
    {
        ini_set('max_execution_time', 3000); // 5 minutes

        //importing advertisers/stores/merchents

        $total_records= 1;
        $fetched_records= 0;
        $page = 1;

        while($fetched_records < $total_records){

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://advertiser-lookup.api.cj.com/v2/advertiser-lookup?requestor-cid=5499477&advertiser-ids=joined&records-per-page=50&page-number='.$page);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


        $headers = array();
        $headers[] = 'Authorization: Bearer 1jkkfyp5r28p43ghpsx4p1h588';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
         echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);      


        $data = simplexml_load_string($result);
        $array = json_decode(json_encode($data), TRUE);

        

        // dd($array);

        foreach ($array['advertisers']['advertiser'] as $advertiser) {

            
            $store = Store::create([
                'name'         => $advertiser['advertiser-name'],
                'advertiser_id'=> $advertiser['advertiser-id'],
                'network_id'   => 1,
                'tracking_url' => $advertiser['program-url'],
                'store_url'    => $advertiser['program-url'],
            ]);

         
        if(!empty($advertiser['primary-category']['parent'])){

            $category_parent = Category::where('name',$advertiser['primary-category']['parent'])->first();

            if(!$category_parent){
                $category_parent = new Category();
            $category_parent->name = $advertiser['primary-category']['parent'];
            $category_parent->save();

            }

            DB::table('category_store')->insert([
                'store_id'=>$store->id,
                'category_id'=> $category_parent->id
            ]);

        }
        if(!empty($advertiser['primary-category']['child'])){
            
            $category_child = Category::where('name',$advertiser['primary-category']['child'])->first();

            if(!$category_child){

            $category_child = new Category();
            $category_child->name = $advertiser['primary-category']['child'];
            $category_child->parent_id = $category_parent->id ?? 0;
            $category_child->save();

            }

            DB::table('category_store')->insert([
                'store_id'=>$store->id,
                'category_id'=> $category_child->id
            ]);
        }

        

        //importing cashbacks for current advertiser/store/merchent

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://link-search.api.cj.com/v2/link-search?website-id=100179843&advertiser-ids='.$advertiser['advertiser-id']);
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

        // dd($link_array);

        //saving cashbacks fof current advertiser/store/merchent

        if(array_key_exists('link',$link_array['links'])){

            if(array_key_exists(0,$link_array['links']['link'])){
                foreach ($link_array['links']['link'] as $link) {
            
                    if(array_key_exists('link-code-html',$link)){


                        if(empty($link['sale-commission']) ){
                            $commission = 0;
                        }else{
                            $commission = $link['sale-commission'];
                        }
        
                        $html = $link['link-code-html'];
        
                        $doc = new \DOMDocument();
                        @$doc->loadHTML($html);
                        $xpath = new \DOMXPath($doc);
                        $img_src = $xpath->evaluate("string(//img/@src)");
        
                    }
        
                        $cashback = StoreCashback::create([
                        'image'           => $img_src,
                        'click_url'       => $link['clickUrl'] ?? '#',
                        'sale_commission' => $commission,
                        'store_id' => $store->id,
                       
                    ]);
                }
                
            }else{
               
                    $link = $link_array['links']['link'];
            
                    if(array_key_exists('link-code-html',$link)){
        
                        $html = $link['link-code-html'];
        
                        $doc = new \DOMDocument();
                        @$doc->loadHTML($html);
                        $xpath = new \DOMXPath($doc);
                        $img_src = $xpath->evaluate("string(//img/@src)");
        
                    }
        
                        $cashback = StoreCashback::create([
                        'image'           => $img_src,
                        'click_url'       => $link['clickUrl'] ?? '#',
                        'sale_commission' => $commission,
                        'store_id' => $store->id,
                       
                    ]);
                
            }  
            
        }            

        }

        $total_records = $array['advertisers']['@attributes']['total-matched'];
        $fetched_records= $fetched_records + $array['advertisers']['@attributes']['records-returned'];
        $page++;

    }
    }
}
