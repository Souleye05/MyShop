<?php

namespace App\Services;

interface UserServiceInterface
{
    public function createUser(array $data);
    public function getUsersByRole(string $role);
    public function getUsersByStatus(bool $status);
    public function getUsersByRoleAndStatus(string $role, bool $status);
    public function getAllUsers();
    public function getFilteredUsers(array $filters);

}
