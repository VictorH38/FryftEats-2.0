<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.index');
    }

    public function send(Request $request)
    {
        $details = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ]);

        try {
            Mail::to('victorho@usc.edu')->send(new ContactFormMail($details));
            return redirect()->back()->with('message', 'Thanks for your message! We will get back to you soon.');
        } catch (\Exception $e) {
            Log::error("Failed to send mail: " . $e->getMessage());
            return back()->with('error', 'Failed to send mail. Please try again later.');
        }
    }
}
