<?php

namespace Tests\Unit;

use App\Models\Supplier;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SupplierTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::table('suppliers')->truncate();
        DB::table('addresses')->truncate();
    }
    /**
     * A basic test example.
     */
    public function test_if_index_is_ok(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * GET supplier Test
     */
    public function test_get_supplier()
    {
        $supplier = Supplier::factory()->create();
        $supplier->address()->create(['street' => fake()->streetAddress(),
            'post_code'                 => fake()->postcode(),
            'state'                     => fake()->state(),
            'city'                      => fake()->city() ,
            'country'                   => fake()->countryCode()]);

        $response = $this->getJson("/api/suppliers/{$supplier->id}");
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'name'     => $supplier->name,
                'email'    => $supplier->email,
                'type'     => $supplier->type,
                'document' => $supplier->document,
                'phone'    => $supplier->phone,
               // "street" => $supplier->street,
               // "post_code" => $supplier->postcode,
               // "state" => $supplier->state,
               // "city" => $supplier->city ,
               // "country" => $supplier->country
            ]);
    }

    /**
     * Create Supplier Test
     */
    public function test_store_supplier()
    {
        $data = [
            'name'      => 'Pessoa 1',
            'type'      => 'CPF',
            'document'  => '08061758008',
            'email'     => 'supplier@fakedata.com',
            'phone'     => '11999999999',
            'street'    => fake()->streetAddress(),
            'post_code' => fake()->postcode(),
            'state'     => fake()->state(),
            'city'      => fake()->city() ,
            'country'   => fake()->countryCode(),
        ];
        $response = $this->postJson('/api/suppliers', $data);
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment([
                'name'     => 'Pessoa 1',
                'email'    => 'supplier@fakedata.com',
                'document' => '08061758008',
            ]);
    }

    /**
     * Update Supplier Test
     */
    public function test_update_supplier()
    {
        $supplier = Supplier::factory()->create([
            'name'     => 'Pessoa 1',
            'type'     => 'CPF',
            'document' => '08061758008',
            'email'    => 'supplier@fakedata.com',
            'phone'    => '11999999999',
        ]);
        $data = [
            'name'      => 'Pessoa 1 - UPDATED',
            'type'      => 'CPF',
            'document'  => '08061758008',
            'email'     => 'supplier_updated@fakedata.com',
            'phone'     => '99999999999',
            'street'    => fake()->streetAddress(),
            'post_code' => fake()->postcode(),
            'state'     => fake()->state(),
            'city'      => fake()->city() ,
            'country'   => fake()->countryCode(),
        ];
        $response = $this->putJson("/api/suppliers/{$supplier->id}", $data);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'id'       => $supplier->id,
                'name'     => 'Pessoa 1 - UPDATED',
                'type'     => 'CPF',
                'document' => '08061758008',
                'email'    => 'supplier_updated@fakedata.com',
                'phone'    => '99999999999',
            ]);
    }


    /**
     * DELETE supplier Test
     */
    public function test_destroy_supplier()
    {
        $supplier = Supplier::factory()->create();
        $this->assertDatabaseHas('suppliers', ['id' => $supplier->id]);
        $response = $this->delete("/api/suppliers/{$supplier->id}");
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * Test supplier with validation failure
     */
    public function test_store_suppliers_validation()
    {
        $response = $this->postJson('/api/suppliers', []);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
                ->assertJsonStructure([
                    'message',
                ]);
    }
}
