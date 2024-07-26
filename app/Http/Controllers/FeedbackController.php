<?php

namespace App\Http\Controllers;

use App\Models\FeedbackSchedule;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        // dd($request->token);
        $domain = url('/');
        $encode_token = base64_encode($domain . '/feedback/' . $request->token);
        $data = FeedbackSchedule::with('feedback')
            ->where('token_link', $encode_token)
            ->where('expiry_date', '>=', date('Y-m-d'))
            ->first();
        // dd($check);
        if ($data) {
            if ($data->token_link) {
                return view('admin.feedback.general', compact('data'));
            } else {
                return view('admin.feedback.internal');
            }
        } else {
            $data = 'The link has expired or is Invalid.';
            // dd($data);
            return view('admin.feedback.expiry', compact('data'));
        }

    }
}
