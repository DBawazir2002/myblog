<?php
namespace App\Http\Validators;

class AuthValidator extends BaseValidator
{
    //rules for register
    public function signup() {
    return [
         'name' => 'sometimes|required|string|max:255',
         'phone' => 'required|unique:users,phone',
         'password' => 'required|string|min:8|confirmed',
        ];
    }


// rules for login
    public function signin() {
        return [
              //  'email' => 'required|email|unique:users,email,{{id}}',
                'phone' => 'required',
                'password' => 'required|string',
        ];
    }
}
