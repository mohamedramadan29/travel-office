<?php

namespace App\Livewire\Dashboard\Contact;

use Livewire\Component;
use App\Models\admin\Contact;
use App\Livewire\Dashboard\Contact\ContactSidebar;
use App\Livewire\Dashboard\Contact\ContactMessage;
use App\Livewire\Dashboard\Contact\ReplayContact;

class ContactShow extends Component
{
    public $contact;
    public $listeners=['ShowMessage','RefreshData'=>'$refresh','replayMsg'];
    public function showMessage($id){
       $this->contact=Contact::findOrFail($id);
    }
    public function deleteMsg($id){
        $contact=Contact::findOrFail($id);
        $contact->delete();
        $this->dispatch('RefreshData')->to(ContactMessage::class);
        $this->contact = Contact::latest()->first();
        $this->dispatch('MsgDeleted','Message Deleted Success');
    }

    ############## Replay Message ###############

    public function replayMsg($MsgId){
       // dd('click');
        $this->dispatch('call-replay-contact-component',$MsgId);
    }

    public function render()
    {
        if($this->contact){
        $this->contact->update([
            'is_read' => true,
        ]);
        $this->dispatch('MessageRead')->to(ContactSidebar::class);
        $this->dispatch('MessageRead')->to(ContactMessage::class);
        }
        return view('livewire.dashboard.contact.contact-show');
    }
}
