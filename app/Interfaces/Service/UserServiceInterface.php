<?php
namespace App\Interfaces\Service;

interface UserServiceInterface
{
    public function list();
    public function search($id);
    public function store(array $data);
    public function update(array $data, int $id);
    public function destroy($id);
}
