<?php

namespace App\Services\Contracts;

/**
 * ExternalSearchServiceInterface Contract.
 *
 * Provides a contract for the external search service implementation.
 */
interface ExternalSearchServiceInterface
{
    /**
     * Searches for suppliers based on the provided CNPJ (Cadastro de Contribuintes).
     *
     * @param string $cnpj The CNPJ to search for.
     */
    public function searchCNPJ($cnpj): array;
}
