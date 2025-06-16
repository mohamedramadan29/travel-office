<?php

namespace App\Livewire\Dashboard\Contact;

use App\Models\admin\Contact;
use Livewire\Component;
use Livewire\WithPagination;

class ContactMessage extends Component
{

    use WithPagination;
    public $search;
    public $is_read;
    public $openMessageid;

    public $listeners = ['MessageRead','RefreshData'=>'$refresh'];

    public function updatedSearch(){
        $this->resetPage();
    }

    public function showMessage($id){
        $this->dispatch('ShowMessage', $id)->to(ContactShow::class);
        $this->openMessageid = $id;
    }
    public function MessageRead(){
        $this->is_read = Contact::where('is_read', '0')->count();
    }
    public function render()
    {
        $contacts = Contact::where('email', 'like', '%' . $this->search . '%')->latest()->paginate(8);
        return view('livewire.dashboard.contact.contact-message', compact('contacts'));
    }
}
