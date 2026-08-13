<?php

class Loan {

    public function __construct(
        private readonly int $loanId,
        private readonly int $userId,
        private readonly int $copyId,
        private readonly int $paymentId,
        private readonly DateTimeImmutable $checkoutDate,
        private DateTimeImmutable $dueDate,
        private readonly DateTimeImmutable $returnDate,
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

}