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
        $addressData  = $supplier->addresses()->first();
        $addressArray = [
            'street'    => $addressData->street,
            'post_code' => $addressData->post_code,
            'state'     => $addressData->state,
            'city'      => $addressData->city,
            'country'   => $addressData->country->iso_3166_2,
        ];
        return \array_merge($supplier->toArray(), $addressArray);
    }
    /**
     * Retrieves all suppliers.
     *
     * @param array $data The fields to filter by in the supplier data.
     * @return LengthAwarePaginator A paginated list of suppliers.
     */
    public function all(array $data)
    {
        $supplierList = Supplier::orderBy($data['order_by'] ?? 'name', $data['order'] ?? 'asc')
        ->paginate(page: $data['page'] ?? 1, perPage: $data['per_page'] ?? 20);

        $supplierList->getCollection()->transform(function ($supplier) {
            $address             = $supplier->addresses()->first();
            $supplier->street    = $address->street;
            $supplier->post_code =  $address->post_code;
            $supplier->city      = $address->city;
            $supplier->state     = $address->state;
            $supplier->country   = $address->country->name;
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
        $supplier->addAddress($address);
        $supplier->save();
        $supplier->refresh();
        $supplierData = $this->mergeData($supplier);
        $this->cacher->setCached('supplier_' . $supplier->id, \json_encode($supplierData));
        return $supplierData;
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
            $supplier    = Supplier::findOrFail($id);
            $addressData = $supplier->addresses()->first();

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new RecordNotFoundException("Id:`$id` not found");
        }
        $supplier->update($data);
        $supplier->updateAddress($addressData, $address);
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
