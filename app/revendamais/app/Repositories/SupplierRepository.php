<?php

namespace App\Repositories;

use App\Helpers\Cacher;
use App\Models\Address;
use App\Models\Supplier;
use App\Repositories\Interfaces\SupplierRepositoryInterface;
use App\Services\DTO\SupplierDTO;
use Illuminate\Database\RecordNotFoundException;

class SupplierRepository implements SupplierRepositoryInterface
{
    private $cacher;

    public function __construct()
    {
        $this->cacher = new Cacher('redis');
    }

    /**
     * Retrieves all suppliers.
     *
     * @param array $data The fields to filter by in the supplier data.
     * @return LengthAwarePaginator A paginated list of suppliers.
     */
    public function all(array $data)
    {
        $supplierList = Supplier::with('address')->orderBy($data['order_by'] ?? 'name', $data['order'] ?? 'asc')
        ->paginate(page: $data['page'] ?? 1, perPage: $data['per_page'] ?? 20);

        return $supplierList;
    }

    /**
     * Finds a supplier by ID.
     *
     * @param int $id The ID of the supplier to find.
     */
    public function find($id): Supplier
    {
        $cachedData = $this->cacher->getCached('supplier_' . $id);

        if ($cachedData) {
            $supplier = new Supplier((array)$cachedData);
            $supplier->exists = true;
            $supplier->id = $cachedData->id;
            if(isset($cachedData->address)){
                $supplier->setRelation('address',new Address((array)$cachedData->address));
                $supplier->address->id = $cachedData->address->id;
                $supplier->address->created_at = $cachedData->address->created_at;
                $supplier->address->updated_at = $cachedData->address->updated_at;
            }
            return $supplier;
        }
        try {
            $supplier = Supplier::with('address')->findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new RecordNotFoundException("Id:`$id` not found");
        }

        $this->cacher->setCached('supplier_' . $supplier->id, \json_encode($supplier->toArray()));
        return $supplier;
    }

    /**
     * Creates a new supplier.
     *
     * @param array $data The data to create the supplier with.
     * @param array $address The address data for the supplier.
     */
    public function create(array $data, array $address): Supplier
    {
        $supplier = Supplier::create($data);
        $supplier->address()->create($address);
        $supplier->fresh();
        $out = $supplier->load('address');
        //$out = $this->mergeData($supplier);
        $this->cacher->setCached('supplier_' . $supplier->id, \json_encode($out));
        return $out;
    }

    /**
     * Updates a supplier.
     *
     * @param int $id The ID of the supplier to update.
     * @param array $data The data to update the supplier with.
     * @param array $address The address data for the supplier.
     */
    public function update(int $id, array $data, array $address): Supplier
    {
        try {
            $supplier = Supplier::with('address')->findOrFail($id);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new RecordNotFoundException("Id:`$id` not found");
        }
        $supplier->update($data);
        $supplier->address()->update($address);
        $supplier->refresh();
        $supplier->load('address');
        if ($this->cacher->getCached('supplier_' . $supplier->id)) {
            $this->cacher->removeCached('supplier_' . $supplier->id);
        }
        $this->cacher->setCached('supplier_' . $supplier->id, \json_encode($supplier));
        return $supplier;
    }

    /**
     * Deletes a supplier.
     *
     * @param int $id The ID of the supplier to delete.
     */
    public function delete($id): bool
    {
        try {
            $supplier = Supplier::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new RecordNotFoundException("Id:`$id` not found");
        }
        $this->cacher->removeCached('supplier_' . $id);
        return $supplier->forceDelete();
    }
}
