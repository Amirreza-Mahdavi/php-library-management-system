<?php

namespace LMS\Domain;

use DateTimeImmutable;
use LMS\Enums\LoanStatus;

class Loan {

    public function __construct(
        private readonly int $loanId,
        private readonly int $userId,
        private readonly int $copyId,
        private readonly int $paymentId,
        private readonly DateTimeImmutable $checkoutDate,
        private DateTimeImmutable $dueDate,
        private ?DateTimeImmutable $returnDate,
        private LoanStatus $status,
        private int $renewalCount,
        private float $fine
    ){}

    public function getLoanId(){
        return $this->loanId;
    }
    public function getLoanUserId(){
        return $this->userId;
    }
    public function getLoanCopyId(){
        return $this->copyId;
    }
    public function getLoanPaymentId(){
        return $this->paymentId;
    }
    public function getLoanCheckoutDate(){
        return $this->checkoutDate;
    }
    public function getLoanDueDate(){
        return $this->dueDate;
    }
    public function getLoanReturnDate(){
        return $this->returnDate;
    }
    public function getLoanStatus(){
        return $this->status;
    }
    public function getLoanRenewalCount(){
        return $this->renewalCount;
    }
    public function getLoanFine(){
        return $this->fine;
    }

    public function setReturnDate(DateTimeImmutable $returnDate): void{
        $this->returnDate = $returnDate;
    }
    public function setDueDate(DateTimeImmutable $dueDate): void {
        $this->dueDate = $dueDate;
    }
    public function setLoanStatus(LoanStatus $status): void {
        $this->status = $status;
    }
    public function setRenewalCount(int $count): void {
        $this->renewalCount = $count;
    }
    public function setFine(float $fine): void {
        $this->fine = $fine;
    }

}