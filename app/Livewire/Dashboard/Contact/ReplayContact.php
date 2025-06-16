<?php

namespace App\Livewire\Dashboard\Contact;

use App\Http\Services\Dashboard\ContactService;
use Livewire\Component;
use Livewire\Attributes\On;

class ReplayContact extends Component
{

    public $contact;
    public $id,$email,$subject,$replaymessage,$contactname;
  //  public $listeners=['ReplayMsgToLuanchModal'];
  protected $listeners = ['call-replay-contact-component' => 'CallReplayContactComponent'];
    protected ContactService $contactService;
    public function boot(ContactService $contactService){
        $this->contactService = $contactService;
    }
    public function CallReplayContactComponent($MsgId){
        $this->id=$MsgId;
        $this->contact=$this->contactService->GetContactById($this->id);
        $this->email=$this->contact->email;
        $this->subject=$this->contact->subject;
        $this->contactname=$this->contact->name;
      $this->dispatch('Launch-replay-modal');
    }

    public function submitreplayMsg(){
        $this->contactService->ReplayContact($this->id,$this->replaymessage);
        $this->dispatch('Close-replay-modal');
        $this->dispatch('RefreshData')->to(ContactShow::class);
        $this->dispatch('RefreshData')->to(ContactMessage::class);
        $this->dispatch('MsgReplied','Message Replied Success');
    }
    public function render()
    {
        return view('livewire.dashboard.contact.replay-contact');
    }
}
