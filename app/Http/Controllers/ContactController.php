<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;


class ContactController extends Controller
{
    public function view(){
        return response()->json(Contact::All());
    }
    public function store(Request $request)
    {
        /*$name = $request->input('name');
        $lastname = $request->input('last_name');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $address = $request->input('address');
        $message = $request->input('message');

        $data = Contact::create(['name' => $name, 'last_name' => $lastname, 'email' => $email, 'phone' => $phone, 'address' => $address, 'message' => $message]);*/

        if ($request->has(['name', 'last_name', 'email', 'phone', 'address', 'message'])) {

            $validated = $request->validate([
                'name' => 'required|min:2',
                'last_name' => 'required|min:2',
                'email' => 'required|email',
                'phone' => '',
                'address' => '',
                'message' => ''
            ]);
                Contact::create($validated);
            return response("Contact saved" , 200);
        } else {
            return response("Something is missing from request", 500);
        }
    }

    public function delete($id){
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return response("Contact with id " .$id. " deleted" , 200);

    }

}
