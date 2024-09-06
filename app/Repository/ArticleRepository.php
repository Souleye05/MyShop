<?php
namespace App\Repository;

use Illuminate\Http\Request;

interface ArticleRepository
{
    public function all();
    public function create(array $data);
    public function find($id);
    public function update($id, array $data);
    public function delete($id);
    public function findByLibelle($libelle);
    public function findByEtat(Request $request);
    
}