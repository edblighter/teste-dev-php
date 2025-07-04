<?php

namespace Tests\Unit\Services;

use App\Services\ExternalSearchService;
use App\Services\Contracts\ExternalSearchServiceInterface;
use App\Helpers\Cacher;
use Illuminate\Http\Request;
use InvalidArgumentException;
use RafaelLaurindo\BrasilApi\BrasilApi;
use LaravelLegends\PtBrValidator\Rules\Cnpj;
use Tests\TestCase;

class ExternalSearchServiceTest extends TestCase
{
    /**
     * Test invalid CNPJ (invalid format)
     */
    public function testInvalidCNPJ()
    {
        $service = new ExternalSearchService();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('CNPJ Invalido!');
        $service->searchCNPJ('12345678900');
    }


}
