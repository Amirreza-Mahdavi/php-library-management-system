<?php

namespace LMS\Repository;

use DateTimeImmutable;
use LMS\Domain\Loan;
use LMS\Enums\LoanStatus;

interface LoanRepository {

    public function findByUserId(int $userId): array;
    public function findByUserIdAndCopyId(int $userId, int $copyId): ?Loan;
    public function updateLoanWhileReturninBook(
        int $loanId, 
        DateTimeImmutable $returnDate, 
        float $fine, 
        LoanStatus $status
    ): void;
    public function updateLoanWhileRenewal(int $loanId, DateTimeImmutable $dueDate, int $renewalCount): void;
    public function save(Loan $loan): void;
    public function remove(int $id): void;

}