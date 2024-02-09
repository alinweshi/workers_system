<?php
namespace App\Interfaces;

interface OrderRepositoryInterface
{
    public function all();
    public function show($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function is_paid($id,array $data);
    public function is_completed($id);
    public function is_cancelled($id);
}
