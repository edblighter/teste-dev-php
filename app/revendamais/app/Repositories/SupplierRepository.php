<?php

namespace App\Repositories;

use App\Helpers\Cacher;
use App\Models\Supplier;
use App\Repositories\Interfaces\SupplierRepositoryInterface;
use Illuminate\Database\RecordNotFoundException;

class SupplierRepository implements SupplierRepositoryInterface
{
    private $cacher;

    public function __construct()
    {
        $this->cacher = new Cacher('redis');
    }

    /**
     * Merges data from a supplier object into its array representation.
     *
     * @param Supplier $supplier The supplier object to merge data from.
     * @return array The merged data with added address information.
     */
    private function mergeData(Supplier $supplier): array
    {
        $addressData  = $supplier->address()->get();
        if(!empty($addressData[0])){
            $addressArray = [
                'street'    => $addressData[0]->street,
                'post_code' => $addressData[0]->post_code,
                'state'     => $addressData[0]->state,
                'city'      => $addressData[0]->city,
                'country'   => $addressData[0]->country,
            ];
            return \array_merge($supplier->toArray(), $addressArray);
        }else{
            return $supplier->toArray();
        }
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

        $supplierList->getCollection()->transform(function ($supplier) {
            $address             = $supplier->address()->get();
            $supplier->street    = $address[0]->street;
            $supplier->post_code = $address[0]->post_code;
            $supplier->city      = $address[0]->city;
            $supplier->state     = $address[0]->state;
            $supplier->country   = $address[0]->country;
            return $supplier;
        });
        return $supplierList;
    }

    /**
     * Finds a supplier by ID.
     *
     * @param int $id The ID of the supplier to find.
     */
    public function find($id): array
    {
        $cachedData = $this->cacher->getCached('supplier_' . $id);

        if ($cachedData) {
            return (array) $cachedData;
        }
        try {
            $supplier = Supplier::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new RecordNotFoundException("Id:`$id` not found");
        }
        $supplierData = $this->mergeData($supplier);
        $this->cacher->setCached('supplier_' . $supplier->id, \json_encode($supplierData));
        return $supplierData;
    }

    /**
     * Creates a new supplier.
     *
     * @param array $data The data to create the supplier with.
     * @param array $address The address data for the supplier.
     */
    public function create(array $data, array $address): array
    {
        $supplier = Supplier::create($data);
        $supplier->address()->create($address);
        $supplier->fresh();
        $out = $this->mergeData($supplier);
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
    public function update(int $id, array $data, array $address): array
    {
        try {
            $supplier = Supplier::findOrFail($id);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new RecordNotFoundException("Id:`$id` not found");
        }
        $supplier->update($data);
        $supplier->address()->update($address);
        $supplier->refresh();
        $supplierData = $this->mergeData($supplier);
        if ($this->cacher->getCached('supplier_' . $supplier->id)) {
            $this->cacher->removeCached('supplier_' . $supplier->id);
        }
        $this->cacher->setCached('supplier_' . $supplier->id, \json_encode($supplierData));
        return $supplierData;
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
