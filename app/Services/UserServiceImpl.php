<?php

namespace App\Services;

use App\Repository\UserRepositoryInterface;

class UserServiceImpl implements UserServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(array $data)
    {
        return $this->userRepository->create($data);
    }

    public function getUsersByRole(string $role)
    {
        return $this->userRepository->findByRole($role);
    }

    public function getUsersByStatus(bool $status)
    {
        return $this->userRepository->findByStatus($status);
    }

    public function getUsersByRoleAndStatus(string $role, bool $status)
    {
        return $this->userRepository->findByRoleAndStatus($role, $status);
    }

    public function getAllUsers()
    {
        return $this->userRepository->findAll();
    }

    public function getFilteredUsers(array $filters)
    {
        if (isset($filters['role']) && isset($filters['status'])) {
            return $this->getUsersByRoleAndStatus($filters['role'], $filters['status']);
        } elseif (isset($filters['role'])) {
            return $this->getUsersByRole($filters['role']);
        } elseif (isset($filters['status'])) {
            return $this->getUsersByStatus($filters['status']);
        } else {
            return $this->getAllUsers();
        }
    }
}
