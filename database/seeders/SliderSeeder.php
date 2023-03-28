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
            array('name' => 'Home','slider_type' => 'banner','slides_per_page' => NULL,'auto_play' => '0','slider_width' => NULL,'slider_height' => NULL,'is_active' => '1'),
            array('name' => 'Mobile Home','slider_type' => 'banner','slides_per_page' => NULL,'auto_play' => '0','slider_width' => NULL,'slider_height' => NULL,'is_active' => '1')
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
            array('slider_id' => '1','slider_type' => 'store','store_id' => '10','name' => 'slide1','logo' => 'default1.png','banner' => 'default1.png','cashback_title' => NULL,'description' => 'Get everything at a huge discount','order'=>1),
            array('slider_id' => '1','slider_type' => 'store','store_id' => '12','name' => 'slide2','logo' => 'default2.png','banner' => 'default2.png','cashback_title' => NULL,'description' => 'Get everything at a huge discount','order'=>2),
            array('slider_id' => '1','slider_type' => 'store','store_id' => '15','name' => 'slide3','logo' => 'default3.png','banner' => 'default3.png','cashback_title' => NULL,'description' => 'Get everything at a huge discount','order'=>3),
            array('slider_id' => '2','slider_type' => 'store','store_id' => '10','name' => 'slide3','logo' => 'default1.png','banner' => 'default1.png','cashback_title' => NULL,'description' => 'Get everything at a huge discount','order'=>1),
            array('slider_id' => '2','slider_type' => 'store','store_id' => '12','name' => 'slide3','logo' => 'default2.png','banner' => 'default2.png','cashback_title' => NULL,'description' => 'Get everything at a huge discount','order'=>2),
            array('slider_id' => '2','slider_type' => 'store','store_id' => '15','name' => 'slide3','logo' => 'default3.png','banner' => 'default3.png','cashback_title' => NULL,'description' => 'Get everything at a huge discount','order'=>3)
          );

          foreach ($slides as $slide) {
            Slide::create([
                'slider_id'=>$slide['slider_id'],
                'slider_type'=> $slide['slider_type'],
                'store_id'=>$slide['store_id'],
                'name'=>$slide['name'],
                'logo'=>$slide['logo'],
                'banner'=>$slide['banner'],
                'cashback_title'=>$slide['cashback_title'],
                'description'=>$slide['description'],
                'order'=>$slide['order'],
            ]);
        }
    }
}
