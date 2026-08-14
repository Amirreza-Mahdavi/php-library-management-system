<?php

namespace LMS\Repository;

use LMS\Domain\Payment;

class PaymentRepository {
    private string $file = __DIR__ . '/../../data/payments.json';

    private function mapToPayment(): array {
        $payments = [];
        $data = json_decode(file_get_contents($this->file), true);

        foreach ($data as $payment) {
            $payment[] = new Payment(
                $payment['payment_id'],
                $payment['loan_id'],
                $payment['amount'],
                $payment['payment_date']
            );
        }
        return $payment;
    }

}