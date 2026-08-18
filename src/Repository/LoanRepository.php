<?php

namespace LMS\Repository;

use DateTimeImmutable;
use LMS\Domain\Loan;
use LMS\DTO\CreateLoanRequest;
use LMS\Enums\LoanStatus;
use LMS\Traits\MetadataTrait;

class LoanRepository {
    private string $file = __DIR__ . '/../../data/loans.json';
    use MetadataTrait;

    public function findByUserId(int $userId): array {
        $data = json_decode(file_get_contents($this->file), true) ?? [];
        $loans = [];

        foreach ($data as $loan) {
            if ($loan['user_id'] === $userId)
                $loans[] = $this->mapToLoan($loan);
        }
        return $loans;
    }

    public function findByUserIdAndCopyId(int $userId, int $copyId): ?Loan {
        $data = json_decode(file_get_contents($this->file), true) ?? [];

        foreach ($data as $loan) {
            if ($loan['user_id'] === $userId && $loan['copy_id'] === $copyId)
                return $this->mapToLoan($loan);
        }
        return null;
    }

    public function updateLoanWhileReturninBook(int $loanId, DateTimeImmutable $returnDate, float $fine, LoanStatus $status): void{
        $data = json_decode(file_get_contents($this->file), true) ?? [];

        foreach ($data as &$loan) {
            if((int) $loan['loan_id'] === $loanId){
                $loan['return_date'] = $returnDate->format('Y-m-d H:i:s');
                $loan['status'] = $status->value;
                $loan['fine'] = $fine;
                $newData[] = $this->mapToLoan($loan);
                break;
            }
        }
        unset($loan);
        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT));
    }


    public function save(Loan $loan): void {
        $data = json_decode(file_get_contents($this->file), true) ?? [];
        $data[] = $this->mapToStorage($loan);

        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function remove(int $id): void {
        $data = json_decode(file_get_contents($this->file), true) ?? [];
        $newData = [];
        
        foreach ($data as $loan) {
            if($loan['loan_id'] !== $id)
                $newData[] = $loan;
        }
        file_put_contents($this->file, json_encode($newData, JSON_PRETTY_PRINT));
    }

    private function mapToLoan(array $loan): Loan {
        return new Loan(
            (int) $loan['loan_id'],
            (int) $loan['user_id'],
            (int) $loan['copy_id'],
            (int) $loan['payment_id'],
            new DateTimeImmutable($loan['checkout_date']),
            new DateTimeImmutable($loan['due_date']),
            $loan['return_date'] !== null
                ? new DateTimeImmutable($loan['return_date'])
                : null,
            LoanStatus::from($loan['status']),
            (int) $loan['renewal_count'],
            (float) $loan['fine']
        );
    }

    private function mapToStorage(Loan $loan): array {
        return [
            'loan_id' => $loan->getLoanId(),
            'user_id' => $loan->getLoanUserId(),
            'copy_id' => $loan->getLoanCopyId(),
            'payment_id' => $loan->getLoanPaymentId(),
            'checkout_date' => $loan->getLoanCheckoutDate(),
            'due_date' => $loan->getLoanDueDate(),
            'return_date' => $loan->getLoanReturnDate(),
            'status' => $loan->getLoanStatus(),
            'renewal_count' => $loan->getLoanRenewalCount(),
            'fine' => $loan->getLoanFine()
        ];
    }
}