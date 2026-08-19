<?php

namespace LMS\Repository;

use LMS\Domain\Payment;

interface PaymentRepository {

    public function findByLoanId(int $loanId): ?Payment;
    public function save(Payment $payment): void;
    public function remove(int $id): void;

}