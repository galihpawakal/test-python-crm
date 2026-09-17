<?php

namespace App\Services;

use App\Models\UserModel;

class UserService
{
    protected $userModel;

    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    public function getAllUsers()
    {
        return $this->userModel->findAll();
    }

    public function getUserById($id)
    {
        return $this->userModel->find($id);
    }

    public function createUser(array $data)
    {
        return $this->userModel->save($data);
    }

    public function updateUser($id, array $data)
    {
        return $this->userModel->update($id, $data);
    }

    public function deleteUser($id)
    {
        return $this->userModel->delete($id);
    }

    public function getUserCount()
    {
        return $this->userModel->countAllResults();
    }
}
