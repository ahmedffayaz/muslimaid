<?php

use App\Models\SiteSetting;

$settings = App\Models\SiteSetting::latest()->get()->pluck('value','type');

$currency = Currency::where('id',$setting['currency'])->first()->pluck('symbol');
return array(
    'currency' =>$currency,
);
