<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $logo_url= null;
        $banner_url= null;

        if($this->logo_type == 'upload'){
            $logo_url = ($this->logo_upload =="category_default_logo.png") ? url('frontend/images/categories/images/category_default_logo.png') :url('storage/categories/images/'.$this->logo_upload);
        }elseif($this->logo_type =='link'){
           $logo_url = $this->logo_link;
        }

        if($this->banner_type == 'upload'){
            $banner_url =($this->banner_upload =="category_default_banner.png") ? url('frontend/images/categories/images/category_default_banner.png') :url('storage/categories/images/'.$this->banner_upload);
        }elseif($this->banner_type =='link'){
           $banner_url = $this->banner_link;
        }

        return [ 
        "name"=> $this->name,
        "slug"=> $this->slug,
        "description"=> $this->description,
        "logo"=>$logo_url,
        "banner"=> $banner_url,
        "parent_id"=> $this->parent_id,
    ];
    }
}
