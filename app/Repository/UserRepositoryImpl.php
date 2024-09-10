<?php

namespace App\Repository;

use App\Models\User;

class UserRepositoryImpl implements UserRepositoryInterface
{
    public function create($data)
    {
        return User::create($data);
    }

    public function findByRole(string $role)
    {
        return User::whereHas('role', function($query) use ($role) {
            $query->where('name', $role);
        })->get();
    }

    public function findByStatus(bool $status)
    {
        return User::where('status', $status)->get();
    }

    public function findByRoleAndStatus(string $role, bool $status)
    {
        return User::whereHas('role', function($query) use ($role) {
            $query->where('name', $role);
        })->where('status', $status)->get();
    }

    public function findAll()
    {
        return User::all();
    }
}
