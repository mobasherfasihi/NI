<?php

use Illuminate\Support\Carbon;

class ProductData
{
    public function __construct(
        public int $id,
        public string $name,
        public string $sku,
    )
    {}

    // Its usage is $data = new CustomerData(...$customerRequest->validated());
}