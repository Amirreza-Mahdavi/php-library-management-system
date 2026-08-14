<?php

namespace LMS\Repository;

use LMS\Domain\Loan;

class LoanRepository {
    private string $file = __DIR__ . '/../../data/loans.json';

    private function mapToLoan(array $loan): Loan {
        return new Loan(
            $loan['loan_id'],
            $loan['user_id'],
            $loan['copy_id'],
            $loan['payment_id'],
            $loan['checkout_date'],
            $loan['due_date'],
            $loan['return_date'],
            $loan['status'],
            $loan['renewal_count'],
            $loan['fine']
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