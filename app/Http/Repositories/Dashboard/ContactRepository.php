<?php
namespace App\Http\Repositories\Dashboard;

use App\Models\admin\Contact;

class ContactRepository{

    public function GetContactById($id){
        $contact = Contact::find($id);
        return $contact;
    }

    public function deleteContact($contact){
        return $contact->delete();
    }
    public function MakeRed($contact){
        return $contact->update(['read' => 1]);
    }
    public function MakeUnRed($contact){
        return $contact->update(['read' => 0]);
    }
    public function GetUnReadContacts(){
        $contacts = Contact::where('read', 0)->get();
        return $contacts;
    }
    public function GetReadContacts(){
        $contacts = Contact::where('read', 1)->get();
        return $contacts;
    }
}
