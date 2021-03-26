<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\StoreCashback;


class ImporterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //importing advertisers/stores/merchents

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://advertiser-lookup.api.cj.com/v2/advertiser-lookup?requestor-cid=5499477&advertiser-ids=joined&records-per-page=10');
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

        foreach ($array['advertisers']['advertiser'] as $advertiser) {

            
            $store = Store::create([
                'name'         => $advertiser['advertiser-name'],
                'advertiser_id'=> $advertiser['advertiser-id'],
                'network_id'   => 1,
                'tracking_url' => $advertiser['program-url'],
                'store_url'    => $advertiser['program-url'],
            ]);

        //importing cashbacks for current advertiser/store/merchent

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://link-search.api.cj.com/v2/link-search?website-id=100179843&link-type=banner&advertiser-ids='.$advertiser['advertiser-id']);
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

        //saving cashbacks fof current advertiser/store/merchent
        
        foreach ($link_array['links']['link'] as $link) {

            $html = $link['link-code-html'];

            $doc = new \DOMDocument();
            $doc->loadHTML($html);
            $xpath = new \DOMXPath($doc);
            $src = $xpath->evaluate("string(//img/@src)");
           
                $cashback = StoreCashback::create([
                'image'           => $src,
                'click_url'       => $link['clickUrl'],
                'sale_commission' => $link['sale-commission'],
                'store_id' => $store->id,
               
            ]);
            

        }
            

        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        

        
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
        //
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
}
