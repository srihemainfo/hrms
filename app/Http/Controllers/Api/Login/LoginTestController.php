<?php

namespace App\Http\Controllers\Api\Login;

use App\Http\Controllers\Controller;
use Laravel\Sanctum\HasApiTokens;
use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Http\Request;


class LoginTestController extends Controller
{

    public function checkStudent(Request $request,HasherContract $hasher)
    {

        if (isset($request->register_no) && isset($request->password)) {
            $checkReg = User::where(['register_no' => $request->register_no])->select('password')->first();
            if ($checkReg != '') {
                $checkPassword = $checkReg->password;
                $givenPassword = $request->password;
                $check = $hasher->check($request->password, $checkReg->password);
                if ($check === true) {
                     return response()->json(['message' => 'These Credentials Matched','status' => true]);
                } else {
                    return response()->json(['message' => 'These Credentials Not Matched','status' => false]);
                }
            } else {
                return response()->json(['message' => 'The Register No Not Matched','status' => false]);
            }
        } else {
            return response()->json(['message' => 'Required Details Not Found','status' => false]);
        }
    }


       public function checkStaff(Request $request, HasherContract $hasher)
{
    if (isset($request->email) && isset($request->password)) {
        $checkReg = User::where(['email' => $request->email])->first();
        if ($checkReg) { // Check if user exists
            if ($hasher->check($request->password, $checkReg->password)) {
                // Create a token for the found user
                $token = $checkReg->createToken('API Token')->plainTextToken;
                return response()->json(['message' => 'These Credentials Matched', 'status' => true, 'token' => $token]);
            } else {
                return response()->json(['message' => 'These Credentials Not Matched', 'status' => false]);
            }
        } else {
            return response()->json(['message' => 'The Email Not Matched', 'status' => false]);
        }
    } else {
        return response()->json(['message' => 'Required Details Not Found', 'status' => false]);
    }
}

}
