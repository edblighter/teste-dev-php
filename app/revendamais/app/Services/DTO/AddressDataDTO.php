<?php

namespace App\Services\DTO;

class AddressDataDTO
{
    public function __construct(
        public readonly string $street,
        public readonly string $city,
        public readonly ?string $state,
        public readonly string $postCode,
        public readonly string $country,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            street: $data['street'],
            city: $data['city'],
            state: $data['state'] ?? null,
            postCode: $data['post_code'],
            country: $data['country'],
        );
    }

    public function toArray(): array
    {
        return [
            'street' => $this->street,
            'city' => $this->city,
            'state' => $this->state,
            'post_code' => $this->postCode,
            'country' => $this->country,
        ];
    }
}
