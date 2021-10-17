<?php

use Illuminate\Support\Carbon;

class SuccessMessage
{
    public function __construct(
        public string $name,
        public string $mail,
        public Carbon $birthDate,
    )
    {}

    // Its usage is $data = new CustomerData(...$customerRequest->validated());
}