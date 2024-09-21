<?php

namespace App\Services;

use App\Http\Validators\UserValidator;
use App\Interfaces\User\UserRepositoryInterface;
use App\Interfaces\User\UserServiceInterface;
use App\Models\User;
use App\Traits\ResponseHelper;

class UserService implements UserServiceInterface
{
    private $repository, $validator;
    public function __construct(UserRepositoryInterface $repository, UserValidator $validator)    {
        $this->repository = $repository;
        $this->validator = $validator;
    }
    public function getAllUsers()
    {
        return $this->repository->getAll();
    }

    public function getUserById(string $id)
    {
        return $this->repository->getById($id);
    }

    public function getUserByPhone(string $phone){
        return $this->repository->getByPhone($phone);
    }

    public function createUser()
    {
        $validated = $this->validator->validated($this->validator->create());
        $validatedData = (array) $validated->getData()->data;
        return $this->repository->create($validatedData);
    }

    public function updateUser(string $id)
    {
        $user = $this->repository->getById($id);
        $validated = $this->validator->validated($this->validator->update($user));
        $validatedData = (array) $validated->getData()->data;
        return $this->repository->update($user, $validatedData);
    }
}
