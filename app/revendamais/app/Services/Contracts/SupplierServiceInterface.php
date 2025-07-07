<?php

namespace App\Services\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\DTO\SupplierDTO;
use App\Models\Supplier;

interface SupplierServiceInterface
{
    public function getAll(array $requestFields): LengthAwarePaginator;
    public function create(SupplierDTO $dto): Supplier;
    public function getById(int $id): Supplier;
    public function update(SupplierDTO $data, int $id): Supplier;
    public function delete(int $id): bool;
}
