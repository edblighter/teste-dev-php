<?php

namespace App\Services\DTO;

use Illuminate\Http\Request;

class SupplierDTO
{
    public ?int $id;
    public string $name;
    public string $type;
    public string $document;
    public string $email;
    public string $phone;
    public string $street;
    public string $post_code;
    public string $state;
    public string $city;
    public string $country;

    /**
     * Create a new class instance.
    */

    public function __construct(
        ?int $id,
        string $name,
        string $type,
        string $document,
        string $email,
        string $phone,
        string $street,
        string $post_code,
        string $state,
        string $city,
        string $country
    ) {
        $this->id        = $id;
        $this->name      = $name;
        $this->type      = $type;
        $this->document  = $document;
        $this->email     = $email;
        $this->phone     = $phone;
        $this->street    = $street;
        $this->state     = $state;
        $this->post_code = $post_code;
        $this->city      = $city;
        $this->country   = $country;
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
        return [
            'street'    => $this->street,
            'post_code' => $this->post_code,
            'state'     => $this->state,
            'city'      => $this->city,
            'country'   => $this->country,
        ];
    }

    public function toArray()
    {
        return \array_merge($this->getBasicData(), $this->getAddress());
    }

    public static function fromArray(array $data)
    {
        return new self(
            $data['id'] ?? null,
            $data['name'],
            $data['type'],
            $data['document'],
            $data['email'],
            $data['phone'],
            $data['street'],
            $data['post_code'],
            $data['state'],
            $data['city'],
            $data['country'],
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
            $request->street,
            $request->post_code,
            $request->state,
            $request->city,
            $request->country
        ));
    }

}
