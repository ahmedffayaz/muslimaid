<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Store;

class SearchStores extends Component
{
    public $search = '';

    public function render()
    {
        $stores = array();
        $search = $this->search;
        if($search != ''){
            $stores= Store::where('name','like', '%'.$search.'%')
                ->orWhereHas('storeRuleData', function($query) use ($search) {
                    $query->where('key', 'meta:keywords')->where('value', 'like', '%' . $search . '%');
                })->get();
        }

     return view('livewire.search-stores', [
            'stores' => $stores,
        ]);
    }
}
