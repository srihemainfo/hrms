<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\MailClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class sampleMail extends Controller
{
    public function send(Request $request)
    {
        if ($request) {
            try {
                Mail::to($request->email)->send(new MailClass($request->message));
                return back()->with(['success', 'message' => 'Mail Send Successfully.']);
            } catch (\Exception $th) {
                // return response()->json(['status' => false, 'message' => $th->getMessage()]);
                return back()->with(['error', 'message' => $th->getMessage()]);
            }
        }
    }
    public function index(Request $request)
    {
        return view('admin.mail.index');
    }
}
