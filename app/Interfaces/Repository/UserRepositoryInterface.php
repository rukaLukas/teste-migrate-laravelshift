<?php
namespace App\Interfaces\Repository;

use Illuminate\Http\Client\Request;

interface UserRepositoryInterface
{
    public function getAll();
    public function getById(int $id);
    public function save(array $request);
    public function update(array $data, int $id);
    public function delete(int $id);
}
