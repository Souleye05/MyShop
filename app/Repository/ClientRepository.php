<?php

namespace App\Repository;

use Illuminate\Http\Request;

interface ClientRepository
{
    public function getAll(Request $filters);
    public function findByPhone($telephone);
    public function create($data);
    public function find($id);
    public function update($id, $data);
    public function delete($id);
    public function listDettes($id);
    public function findWithUser($id);
}