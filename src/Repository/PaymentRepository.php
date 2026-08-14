<?php

namespace LMS\Repository;

use LMS\Domain\Payment;

class PaymentRepository {
    private string $file = __DIR__ . '/../../data/payments.json';

    public function findByLoanId(int $loanId): ?Payment {
        $data = json_decode(file_get_contents($this->file), true, LOCK_EX);

        foreach ($data as $payment) {
            if ($payment['loan_id'] === $loanId)
                return $this->mapToPayment($payment);
        }
        return null;
    }

    private function mapToPayment(array $payment): Payment {
        return new Payment(
            $payment['payment_id'],
            $payment['loan_id'],
            $payment['amount'],
            $payment['payment_date']
        );
    }

    private function mapToStorage(Payment $payment): array {
        return [
            'payment_id' => $payment->getPaymentId(),
            'loan_id' => $payment->getPaymentLoanId(),
            'amount' => $payment->getPaymentAmount(),
            'payment_date' => $payment->getPaymentDaye()
        ];
    }
}