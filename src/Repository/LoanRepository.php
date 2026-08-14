<?php

namespace LMS\Repository;

use LMS\Domain\Loan;

class LoanRepository {
    private string $file = __DIR__ . '/../../data/loans.json';

    private function mapToLoan(): array {
        $loans = [];
        $data = json_decode(file_get_contents($this->file), true);

        foreach ($data as $loan) {
            $loans = new Loan(
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
        return $loans;
    }
}