<?php

namespace App\Repository;

interface UserRepositoryInterface
{
    public function create($data);
    public function findByRole(string $role);
    public function findByStatus(bool $status);
    public function findByRoleAndStatus(string $role, bool $status);
    public function findAll();
}
