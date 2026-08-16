<?php

namespace LMS\DTO;

use DateTimeImmutable;
use LMS\Enums\LoanStatus;

class CreateLoanRequest {

    public function __construct(
        public int $userId,
        public int $copyId,
        public DateTimeImmutable $dueDate,
        public LoanStatus $status,
    ){}

}