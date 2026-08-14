<?php

namespace LMS\Repository;

use LMS\Domain\Payment;

class PaymentRepository {
    private string $file = __DIR__ . '/../../data/payments.json';

    private function mapToPayment(array $payment): Payment {
        return new Payment(
            $payment['payment_id'],
            $payment['loan_id'],
            $payment['amount'],
            $payment['payment_date']
        );
    }
}