<?php

namespace App\Services\DTO;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
class SupplierDTO
{
    /**
     * Create a new class instance.
    */

    public function __construct(
        public ?int $id,
        public string $name,
        public string $type,
        public string $document,
        public string $email,
        public string $phone,
        public array $address
        /* public string $street,
        public string $post_code,
        public string $state,
        public string $city,
        public string $country, */
    ) {
    }

    public function getId()
    {
        return $this->id;
    }

    public function getBasicData(): array
    {
        return [
            'name'     => $this->name,
            'type'     => $this->type,
            'document' => $this->document,
            'email'    => $this->email,
            'phone'    => $this->phone,
        ];
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function toArray()
    {
        return \array_merge($this->getBasicData(), $this->getAddress());
    }

    public static function fromArray(array $data)
    {
        $address = Arr::only($data, ['street', 'post_code', 'state', 'city', 'country']);
        return new self(
            $data['id'] ?? null,
            $data['name'],
            $data['type'],
            $data['document'],
            $data['email'],
            $data['phone'],
            $address
        );
    }

    public static function fromRequest(Request $request)
    {
        return (new self(
            $request->id,
            $request->name,
            $request->type,
            $request->document,
            $request->email,
            $request->phone,
            ['address'=>
                ['street' =>$request->street,
                'post_code'=>$request->post_code,
                'state'=>$request->state,
                'city'=>$request->city,
                'country'=>$request->country
                ]
            ]
        ));
    }

}
