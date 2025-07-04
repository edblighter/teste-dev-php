<?php

namespace App\Services;

use App\Helpers\Cacher;
use App\Services\Contracts\ExternalSearchServiceInterface;
use InvalidArgumentException;
use RafaelLaurindo\BrasilApi\BrasilApiFacade;

/**
 * ExternalSearchService Class.
 *
 * Provides a method to search for suppliers using an external API service.
 */
class ExternalSearchService implements ExternalSearchServiceInterface
{
    private $cacher;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->cacher = new Cacher('redis');
    }

    /**
     * Searches for suppliers based on the provided CNPJ (Cadastro de Contribuintes).
     *
     * @param string $cnpj The CNPJ to search for.
     */
    public function searchCNPJ($cnpj): array
    {
        $cachedData = $this->cacher->getCached('search_' . $cnpj);
        if ($cachedData) {
            $data = $cachedData;
        } else {
            try {
                $data = BrasilApiFacade::findCnpj($cnpj);
            } catch (\Illuminate\Http\Client\RequestException $e) {
                $response = $e->response;
                $message  = $e->getMessage();
                if ($response->getStatusCode() == 400 && \str_contains($message, 'inválido')) {
                    throw new InvalidArgumentException('CNPJ Invalido!');
                } else {
                    $this->cacher->setCached('search_' . $cnpj, \json_encode($data));
                }
            }
        }
        return $data;
    }
}
