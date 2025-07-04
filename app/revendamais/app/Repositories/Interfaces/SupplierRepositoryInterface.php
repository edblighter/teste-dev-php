<?php

namespace App\Repositories\Interfaces;

interface SupplierRepositoryInterface
{
    public function all(array $data);
    public function find(int $id);
    public function create(array $data, array $address);
    public function update(int $id, array $data, array $address);
    public function delete(int $id);

}
