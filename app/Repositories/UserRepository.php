<?php

namespace App\Repositories;

use App\Interfaces\User\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{

    public function getAll()
    {
        return User::paginate();
    }

    public function getById(string $id)
    {
        return User::findOrFail($id);
    }

    public function getByPhone(string $phone)
    {
        return User::where('phone',$phone)->first();
    }

    public function create(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    public function update(User $user, array $data)
    {
        if(isset($data['password'])){
            $data['password'] = Hash::make($data['password']);
        }
        return $user->update($data);
    }
}
