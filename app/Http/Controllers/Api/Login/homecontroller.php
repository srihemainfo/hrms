<?php

namespace App\Http\Controllers\Api\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class homecontroller extends Controller
{
    public function index(){

        $user=auth()->user();
        $data = [
            'name' => 'subin',
            'email' => 'subinStark24@gmail.com',
        ];
        return response()->json($user);
    }

}
