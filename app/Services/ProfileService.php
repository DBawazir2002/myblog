<?php

namespace App\Services;

use App\Http\Validators\ProfileValidator;
use App\Interfaces\Profile\ProfileRepositoryInterface;
use App\Interfaces\Profile\ProfileServiceInterface;
use App\Interfaces\User\UserServiceInterface;

class ProfileService implements ProfileServiceInterface
{
    private $repository, $validator, $userService;

    public function __construct(
        ProfileRepositoryInterface $repository,
        ProfileValidator $validator,
        UserServiceInterface $userService
    ){
        $this->repository = $repository;
        $this->validator = $validator;
        $this->userService = $userService;
    }

    public function getProfiles($pages = 10, string $user_id = null)
    {
        return $this->repository->getAll($pages,$user_id);
    }

    public function getProfileById(string $id)
    {
        return $this->repository->getById($id);
    }

    public function getProfileByUserId(string $user_id)
    {
        return $this->repository->getByUserId($user_id);
    }

    public function getProfileByUserName(string $username)
    {
        return $this->repository->getByUserName($username);
    }

    public function createProfile(string $user_id)
    {
        $validated = $this->validator->validated($this->validator->create());
        $validatedData = (array) $validated->getData()->data;
        $validatedData['user_id'] = $user_id;
        return $this->repository->create($validatedData);
    }

    public function updateProfile(string $user_id)
    {
        $user = $this->repository->getByUserId($user_id);
        $validated = $this->validator->validated($this->validator->update($user));
        $validatedData = (array) $validated->getData()->data;
        if(isset($validatedData['password'])){
            return $this->userService->updateUser($this->userService->getUserById($user_id));
        }

        $validatedData['user_id'] = $user_id;
        return $this->repository->update($user->id, $validatedData);

    }
}
