<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository {

public $userModel;

public function __construct(User $userModel)
{
    $this->userModel = $userModel;
}

public function getUser(object $request) {
    return $this->userModel::where('email', $request->email)->first();
}

public function createUser(array $userData) {
   return $this->userModel->firstOrCreate([
        'name' => $userData['name'],
        'email' => $userData['email'],
        'password' => Hash::make($userData['password']),
    ]);
}

}