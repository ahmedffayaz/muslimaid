<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Country;
use Livewire\Component;
use Spatie\Permission\Models\Role;


class UserDetails extends Component
{
    public $roles;
    public $user;
    public $users;
    public $user_id;
     public $countries;

    protected $queryString = ['user_id'];

    protected $listeners = [
        'changeEvent',
    ];

    public function mount()
    {
        $this->user = User::find($this->user_id);
        $this->users = User::role('user')->latest()->orderBy('id', 'DESC')->get();
        $this->roles = Role::all();
        $this->countries = Country::whereStatus('1')->get();
    }

    public function render()
    {
        return view('livewire.user-details');
    }

    public function changeEvent($value)
    {
        $this->user = User::find($value);
        $this->user_id = $this->user->id;
        $this->emit('userChange');
    }
}
