<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Store;
use App\Models\Network;
use App\Models\Category;
use App\Models\Tag;

class StoreDetails extends Component
{
    public $store;
    public $stores;
    public $networks;
    public $categories;
    public $tags;
    public $slug;

    protected $queryString = ['slug'];

    protected $listeners = [
        'changeEvent',
    ];


    public function mount()
    {
        $this->store = Store::where('slug',$this->slug)->first();
        $this->stores = Store::latest()->get();
        $this->networks = Network::all();
        $this->categories = Category::where('parent_id',0)->get();
        $this->tags = Tag::where('type', 'stores')->get(); 


    }
    public function render()
    {
        return view('livewire.store-details');
    }
    public function changeEvent($value)
    {
        $this->store = Store::where('slug',$value)->first();
        $this->slug = $this->store->slug;
        $this->emit('storeChange');
    }
}
