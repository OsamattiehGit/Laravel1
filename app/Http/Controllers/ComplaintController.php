<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\Contact;
class ComplaintController extends Controller
{
    public function showForm()
    {
        return view('contact');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'name'    => 'required|string',
            'email'   => 'required|email',
            'phone'   => 'required|string',
            'issue'   => 'required|string',
            'message' => 'required|string',
        ]);

        Contact::create($request->only('name', 'email', 'phone', 'issue', 'message'));

        return redirect()->back()->with('success', 'Your message has been sent!');
    }
}

