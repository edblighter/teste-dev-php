<?php

namespace App\Services;

use App\Repositories\Interfaces\SupplierRepositoryInterface;
use App\Services\DTO\SupplierDTO;
use Illuminate\Database\RecordNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * SupplierService Class.
 *
 * Provides methods to manage suppliers in the application.
 */
class SupplierService
{
    protected $supplierRepository;
    /**
     * Constructor.
     *
     * @param SupplierRepositoryInterface $supplierRepository The supplier repository instance.
     */
    public function __construct(SupplierRepositoryInterface $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;
    }
    /**
     * Retrieves a paginated list of all suppliers based on the provided request fields.
     *
     * @param array $requestFields The fields to filter by in the supplier data.
     * @return LengthAwarePaginator A paginated list of suppliers.
     */
    public function getAll(array $requestFields): LengthAwarePaginator
    {
        $suppliers = $this->supplierRepository->all($requestFields);
        return $suppliers;
    }
    /**
     * Creates a new supplier.
     *
     * @param SupplierDTO $data The data to create the supplier with.
     * @return array The created supplier details.
     */
    public function create(SupplierDTO $data): array
    {
        $supplier = $this->supplierRepository->create($data->getBasicData(), $data->getAddress());
        return $supplier;
    }
    /**
     * Retrieves a supplier by ID.
     *
     * @param int $id The ID of the supplier to retrieve.
     * @return array The supplier details with its address.
     */
    public function getById(int $id): array
    {
        try {
            return $this->supplierRepository->find($id);
        } catch (RecordNotFoundException $e) {
            throw new RecordNotFoundException($e->getMessage());
        }
    }
    /**
     * Updates an existing supplier.
     *
     * @param SupplierDTO $data The data to update the supplier with.
     * @param int $id The ID of the supplier to update.
     * @return array The updated supplier details.
     */
    public function update(SupplierDTO $data, int $id): array
    {
        try {
            $updatedData = $this->supplierRepository->update($id, $data->getBasicData(), $data->getAddress());
        } catch (RecordNotFoundException $e) {
            throw new RecordNotFoundException($e->getMessage());
        }
        return $updatedData;
    }
    /**
     * Deletes a supplier by ID.
     *
     * @param int $id The ID of the supplier to delete.
     */
    public function delete(int $id): bool
    {
        try {
            return $this->supplierRepository->delete($id);
        } catch (RecordNotFoundException $e) {
            throw new RecordNotFoundException($e->getMessage());
        }
    }
}
