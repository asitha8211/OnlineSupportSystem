<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function getUserData($email)
    {
        return User::where('email', $email)
            ->first();
    }

}