<?php

namespace LMS\Repository\Impl;

use DateTimeImmutable;
use LMS\Domain\Payment;
use LMS\DTO\CreatePaymentRequest;
use LMS\Repository\PaymentRepository;
use LMS\Traits\MetadataTrait;

class PaymentRepositoryImpl implements PaymentRepository {
    private string $file = __DIR__ . '/../../../data/payments.json';
    use MetadataTrait;

    public function findByLoanId(int $loanId): ?Payment {
         $data = json_decode(file_get_contents($this->file), true) ?? [];

        foreach ($data as $payment) {
            if ($payment['loan_id'] === $loanId)
                return $this->mapToPayment($payment);
        }
        return null;
    }

    public function save(Payment $payment): void {
        $data = json_decode(file_get_contents($this->file), true) ?? [];
        $data[] = $this->mapToStorage($payment);

        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function remove(int $id): void {
        $data = json_decode(file_get_contents($this->file), true) ?? [];
        $newData = [];
        
        foreach ($data as $payment) {
            if($payment['payment_id'] !== $id)
                $newData[] = $payment;
        }
        file_put_contents($this->file, json_encode($newData, JSON_PRETTY_PRINT));
    }

    private function mapToPayment(array $payment): Payment {
        return new Payment(
            (int) $payment['payment_id'],
            (int) $payment['loan_id'],
            (float) $payment['amount'],
            new DateTimeImmutable($payment['payment_date'])
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