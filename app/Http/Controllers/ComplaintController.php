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

        try {
            Contact::create($request->only('name', 'email', 'phone', 'issue', 'message'));
            
            // Return JSON for AJAX requests, redirect for regular requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Thank you! Your message has been sent successfully. We\'ll get back to you soon.'
                ], 200);
            }
            
            return redirect()->back()->with('success', 'Your message has been sent!');
            
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send message. Please try again later.'
                ], 500);
            }
            
            return redirect()->back()->withErrors(['error' => 'Failed to send message. Please try again.']);
        }
    }

    /**
     * API endpoint for contact form submission
     */
    public function submitApi(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|min:2|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'required|string|min:10|max:20',
            'issue'   => 'required|string|max:255',
            'message' => 'required|string|min:10|max:1000',
        ]);

        try {
            Contact::create($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your message has been sent successfully. We\'ll get back to you soon.'
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message. Please try again later.'
            ], 500);
        }
    }
}

