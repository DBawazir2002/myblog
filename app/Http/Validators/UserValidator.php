<?php

namespace App\Http\Validators;

use App\Http\Validators\BaseValidator;

class UserValidator extends BaseValidator
{
    //rules for create user

    public function create() {
        return [
            'phone' => 'required|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function update($user){
        return [
//            'name' => "sometimes|required|string|max:255",
           // 'phone' => "required|unique:users,phone,{$user->id}",
            'password' => 'required|string|min:8',
        ];
    }
}
