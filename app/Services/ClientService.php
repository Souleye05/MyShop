<?php

namespace App\Services;

use Illuminate\Http\Request;

interface ClientService
{
    public function getAll(Request $filters);
    public function findByPhone(string $telephone);
    public function create(array $data);
    public function find(int $id);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function listDettes($id);
}
