<?php
namespace App\Http\Services\Dashboard;

use App\Mail\ReplayContactMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Repositories\Dashboard\ContactRepository;

class ContactService{

    protected $contactRepository;
    public function __construct(ContactRepository $contactRepository){
        $this->contactRepository = $contactRepository;
    }

    public function GetContactById($id){
        $contact = $this->contactRepository->GetContactById($id);
        if($contact){
            return $contact;
        }
        return $contact;
    }

    public function deleteContact($contact){
        $contact =  $this->contactRepository->deleteContact($contact);
        if(!$contact){
            return false;
        }
        return true;
    }
    public function MakeRed($contact){
        $contact = $this->contactRepository->MakeRed($contact);
        if(!$contact){
            return false;
        }
        return true;
    }
    public function MakeUnRed($contact){
        $contact = $this->contactRepository->MakeUnRed($contact);
        if(!$contact){
            return false;
        }
        return true;
    }
    public function GetUnReadContacts(){
        $contacts = $this->contactRepository->GetUnReadContacts();
        if(!$contacts){
            return [];
        }
        return $contacts;
    }
    public function GetReadContacts(){
        $contacts = $this->contactRepository->GetReadContacts();
        if(!$contacts){
            return [];
        }
        return $contacts;
    }

    public function ReplayContact($contactId,$replayMessage){
        $contact = $this->GetContactById($contactId);
        if(!$contact){
            return false;
        }
      Mail::to($contact->email)->send(new ReplayContactMail($contact->name,$replayMessage,$contact->subject));
      return true;
    }
}
