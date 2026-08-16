<?php

namespace LMS\Domain;

use DateTimeImmutable;

class Payment {

    public function __construct(
        private readonly int $paymentId,
        private readonly int $loanId,
        private float $amount,
        private readonly ?DateTimeImmutable $paymentDate
    ){}

    public function getPaymentId(){
        return $this->paymentId;
    }
    public function getPaymentLoanId(){
        return $this->loanId;
    }
    public function getPaymentAmount(){
        return $this->amount;
    }
    public function getPaymentDaye(){
        return $this->paymentDate;
    }

}