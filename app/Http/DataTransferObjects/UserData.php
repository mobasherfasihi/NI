<?php

use Illuminate\Support\Carbon;

class UserData
{
    public function __construct(
        public string $name,
        public string $email,
        public ?Carbon $email_verified_at,
        private ?string $password,
        private ?string $remember_token,
        public Carbon $created_at,
        public Carbon $updated_at,
    )
    {}
}