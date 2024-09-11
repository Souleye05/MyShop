<?php

namespace App\Services;

interface AuthenticationServiceInterface
{
    public function authenticate($credentials);
    public function logout();
}
