<?php

namespace LMS\DTO;

use LMS\Enums\CopyStatus;

class AddCopyRequest {

    public function __construct(
        public int $bookId,
        public CopyStatus $status,
        public string $location
    ){}
    
}