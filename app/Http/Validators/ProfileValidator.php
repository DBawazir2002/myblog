<?php

namespace App\Http\Validators;

use App\Http\Validators\BaseValidator;

class ProfileValidator extends BaseValidator
{
    public function create() {
        return [
            'username' => 'required|string|max:255|min:3,unique:profiles,username',
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:profiles,email',
            'bio' => 'sometimes|required|string|max:1500|min:10',
        ];
    }

    public function update($user){
        return [
            'username' => "sometimes|required|string|max:255|min:3",
            'email' => "sometimes|required|email|unique:profiles,email,".$user->id,
            'name' => 'sometimes|required|string|max:255',
            'bio' => 'sometimes|required|string|max:1500|min:10',
            'password' => 'sometimes|required|string|min:8|confirmed',
        ];
    }
}
