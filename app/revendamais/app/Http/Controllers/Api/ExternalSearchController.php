<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExternalSearchRequest;
use App\Services\Contracts\ExternalSearchServiceInterface;
use Illuminate\Http\Request;
use InvalidArgumentException;

class ExternalSearchController extends Controller
{
    protected $externalSearch;

    public function __construct(ExternalSearchServiceInterface $search)
    {
        $this->externalSearch = $search;
    }

    public function search(ExternalSearchRequest $request)
    {
        $data = $request->validated();
        try {
            $data = $this->externalSearch->searchCNPJ($data['cnpj']);
        } catch (InvalidArgumentException $e) {
            return response()->json(['message' => 'CNPJ Invalido!'], 400);
        }
        return response()->json($data);
    }
}
