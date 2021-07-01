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
        if($this->search!= ''){
            $stores= Store::where('name','like', '%'.$this->search.'%')->get();
        }
        
     return view('livewire.search-stores', [
            'stores' => $stores,
        ]);
    }
}
