<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\ApiBiometricModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class StaffBiometricApiController extends Controller
{
    public function check(Request $request)
    {

        // Read the input stream
        // $body = file_get_contents("php://input");
        $body = $request->getContent();
        // Decode the JSON object
        $object = json_decode($body);
        // Perform necessary operations with the $object data
        // For example, insert data into the 'api_biometric' table
        // dd($object);
        // dd($object);
        // dd($object->data, json_encode($object->data));
        $insert = ApiBiometricModel::create([
            'response' => $object->data ? json_encode($object->data) : null,
            'date_time' => $object->datetime,
            'status'=> 1
        ]);

        // Return a response indicating the status of the operation
        if ($insert) {
            return response()->json(['message' => 'Data inserted successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to insert data'], 500);
        }

    }
}
