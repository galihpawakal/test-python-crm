<?php

namespace App\Controllers;

use App\Services\UserService;
use App\Models\UserModel;

class Users extends BaseController
{
    protected $userService;

    public function __construct()
    {
        $this->userService = new UserService(new UserModel());
    }

    public function index()
    {
        $data['users'] = $this->userService->getAllUsers();
        return view('users/index', $data);
    }

    public function create()
    {
        return view('users/create');
    }

    public function store()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userService->createUser([
            'name' => $this->request->getPost('name')
        ]);

        return redirect()->to('/users')->with('success', 'User added successfully.');
    }

    public function edit($id)
    {
        $data['user'] = $this->userService->getUserById($id);
        if (!$data['user']) return redirect()->to('/users')->with('error', 'User not found.');

        return view('users/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userService->updateUser($id, [
            'name' => $this->request->getPost('name')
        ]);
        return redirect()->to('/users')->with('success', 'User updated successfully.');
    }

    public function delete($id)
    {
        $this->userService->deleteUser($id);
        return redirect()->to('/users')->with('success', 'User deleted successfully.');
    }
}
