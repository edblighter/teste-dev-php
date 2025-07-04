<?php

namespace App\Services\Contracts;

use Illuminate\Http\Request;

interface ExternalSearchServiceInterface
{
    public function searchCNPJ($cnpj);
}
