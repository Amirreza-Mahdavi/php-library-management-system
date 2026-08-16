<?php

namespace LMS\DTO;

class RegisterRequest {

    public function __construct(
        public string $name,
        public string $email,
        public string $password
    ){}
    
}