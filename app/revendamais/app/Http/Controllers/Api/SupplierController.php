<?php

namespace App\Http\Controllers\Api;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierCreateRequest;
use App\Http\Requests\SupplierIndexRequest;
use App\Http\Requests\SupplierUpdateRequest;
use App\Http\Resources\SupplierCollection;
use App\Http\Resources\SupplierResource;
use App\Services\DTO\SupplierDTO;
use App\Services\SupplierService;
use Illuminate\Database\RecordNotFoundException;
use Illuminate\Http\JsonResponse;

class SupplierController extends Controller
{
    protected $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(SupplierIndexRequest $request)
    {
        $data = $request->validated();
        $data = $this->supplierService->getAll($data);
        return ApiResponseClass::sendResponse(new SupplierCollection($data), '', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierCreateRequest $request): JsonResponse
    {
        $data   = $request->validated();
        $create = $this->supplierService->create(SupplierDTO::fromArray($data));
        return ApiResponseClass::sendResponse(
            new SupplierResource(SupplierDTO::fromArray($create)),
            '',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $data = $this->supplierService->getById($id);
        } catch (RecordNotFoundException $e) {
            return ApiResponseClass::sendResponse('', 'Supplier Not found', 404);
        }
        return ApiResponseClass::sendResponse(new SupplierResource(SupplierDTO::fromArray($data)), '', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierUpdateRequest $request, $id): JsonResponse
    {
        try {
            $data   = $request->validated();
            $action = $this->supplierService->update(SupplierDTO::fromArray($data), $id);
        } catch (RecordNotFoundException $e) {
            return ApiResponseClass::sendResponse('', 'Supplier Not found', 404);
        }
        return ApiResponseClass::sendResponse(new SupplierResource(SupplierDTO::fromArray($action)), '', 200);
    }

    /**
     * Delete the specified resource.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $delete = $this->supplierService->delete($id);
            if ($delete) {
                return ApiResponseClass::sendResponse('', '', 204);
            } else {
                return ApiResponseClass::sendResponse('', 'The DELETE operation was not successful', 400);
            }
        } catch (RecordNotFoundException $e) {
            return ApiResponseClass::sendResponse('', 'Supplier Not found', 404);
        }
    }
}
