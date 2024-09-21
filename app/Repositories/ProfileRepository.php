<?php

namespace App\Repositories;

use App\Interfaces\Profile\ProfileRepositoryInterface;
use App\Models\Profile;

class ProfileRepository implements ProfileRepositoryInterface
{

    public function getAll($pages = 10, string $user_id = null)
    {
        // return (isset($user_id))
        //     ? Profile::with('user')->where('user_id', '!=',$user_id)->paginate($pages)
        //     : Profile::with('user')->paginate($pages);
        return Profile::with('user')->paginate($pages);
    }

    public function getById(string $id)
    {
        return Profile::with('user')->where('id', $id)->first();
    }

    public function getByUserName(string $username)
    {
        return Profile::with('user')->where('username', $username)->first();
    }

    public function getByUserId(string $user_id)
    {
        return Profile::with('user')->where('user_id', $user_id)->first();
    }

    public function create(array $data)
    {
        return Profile::create($data);
    }

    public function update(string $user_id, array $data)
    {
        $is_updated = false;
       if($profile = $this->getById($user_id)) {
           $is_updated = $profile->update($data);
       }
        return $is_updated;
    }
}
