<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('user_create');
    }

    public function rules()
    {
        $rules = [];
        $selectedRole = $this->input('roles');

        if ($selectedRole == '1' || $selectedRole == '2' || $selectedRole == '13' || $selectedRole == '14' || $selectedRole == '15' || $selectedRole == '16' || $selectedRole == '42' || $selectedRole == '43' || $selectedRole == '47') {

            $rules = ['roles' => 'required'];

            if ($selectedRole == '1' || $selectedRole == '2' || $selectedRole == '13' || $selectedRole == '15' || $selectedRole == '16' || $selectedRole == '14' || $selectedRole == '42' || $selectedRole == '43' || $selectedRole == '47') {
                $rules['fullname'] = 'required';
            }

            if ($selectedRole == '11') {
                $rules['name'] = 'required';
                $rules['register_no'] = 'numeric|max:15';
                $rules['enroll_master_id'] = 'required';
                $rules['phone'] = 'required';
                $rules['rollNumber'] = 'required|max:11';
                // Add more rules specific to role 11
            }

            if ($selectedRole == '14') {
                $rules['Dept'] = 'required';
                // Add more rules specific to role 14
            }

            // Only add these rules if the selected role requires them
            if ($selectedRole == '1' || $selectedRole == '2' || $selectedRole == '13' || $selectedRole == '15' || $selectedRole == '16' || $selectedRole == '14' || $selectedRole == '11' || $selectedRole == '42' || $selectedRole == '43' || $selectedRole == '47') {
                $rules['email'] = 'required';
            }

            if ($selectedRole == '1' || $selectedRole == '2' || $selectedRole == '13' || $selectedRole == '15' || $selectedRole == '16' || $selectedRole == '14' || $selectedRole == '42' || $selectedRole == '43' || $selectedRole == '47') {
                $rules['password'] = 'required';
            }

        } else {
            $rules = [
                'roles' => 'required',
                'firstname' => 'required',
                'last_name' => 'required',
                'Dept' => 'required',
                'Designation' => 'required',
                'StaffCode' => 'required|string|unique:users,employID',
                'email' => 'required',
                'phone' => 'required',
                // 'password' => 'required',
            ];

        }

        return $rules ?? [];
    }

}
