<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Slider;
use App\Models\Slide;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sliders = array(
            array('name' => 'Home','slider_type' => 'banner','slides_per_page' => NULL,'auto_play' => '0','slider_width' => NULL,'slider_height' => NULL,'is_active' => '1')
          );

        foreach ($sliders as $slider) {
            Slider::create([
                'name'=>$slider['name'],
                'slider_type'=>$slider['slider_type'],
                'slides_per_page'=>$slider['slides_per_page'],
                'auto_play'=>$slider['auto_play'],
                'slider_width'=>$slider['slider_width'],
                'slider_height'=>$slider['slider_height'],
                'is_active'=>$slider['is_active'],
            ]);
        }
        $slides = array(
            array('slider_id' => '1','store_id' => '10','name' => 'slide1','logo' => 'default1.png','banner' => 'default1.png','cashback_title' => NULL,'description' => 'Get everything at a huge discount'),
            array('slider_id' => '1','store_id' => '12','name' => 'slide2','logo' => 'default2.png','banner' => 'default2.png','cashback_title' => NULL,'description' => 'Get everything at a huge discount'),
            array('slider_id' => '1','store_id' => '15','name' => 'slide3','logo' => 'default3.png','banner' => 'default3.png','cashback_title' => NULL,'description' => 'Get everything at a huge discount'),
          );

          foreach ($slides as $slide) {
            Slide::create([
                'slider_id'=>$slide['slider_id'],
                'store_id'=>$slide['store_id'],
                'name'=>$slide['name'],
                'logo'=>$slide['logo'],
                'banner'=>$slide['banner'],
                'cashback_title'=>$slide['cashback_title'],
                'description'=>$slide['description'],
            ]);
        }
    }
}
