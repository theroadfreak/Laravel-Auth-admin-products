<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;


class ContactController extends Controller
{
    public function store(Request $request){
        $name = $request->input('name');
        $lastname = $request->input('last-name');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $address = $request->input('address');
        $message = $request->input('message');

        $data = Contact::create(['name' => $name, 'last-name' => $lastname, 'email' => $email, 'phone' => $phone, 'address' => $address, 'message' => $message]);

        return response("200", 200);
    }
}
