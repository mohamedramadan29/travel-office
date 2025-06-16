<?php

namespace App\Livewire\Dashboard\Contact;

use App\Models\admin\Contact;
use Livewire\Component;

class ContactSidebar extends Component
{
    public $counts_not_read;

    public $listeners=['MessageRead'];
    public function MessageRead(){
        $this->counts_not_read = Contact::where('is_read', '0')->count();
    }
    public function render()
    {
        $this->counts_not_read = Contact::where('is_read', '0')->count();
        return view('livewire.dashboard.contact.contact-sidebar');
    }
}
