<?php

namespace LMS\Service;

use Exception;
use DateTimeImmutable;
use LMS\Repository\BookRepository;
use LMS\Traits\MetadataTrait;
use LMS\DTO\CreateLoanRequest;
use LMS\Repository\UserRepository;
use LMS\Repository\CopyRepository;
use LMS\Repository\LoanRepository;
use LMS\Repository\PaymentRepository;
use LMS\Domain\Loan;
use LMS\Enums\LoanStatus;
use LMS\Domain\Payment;
use LMS\Domain\Copy;
use LMS\Enums\CopyStatus;

class LoanService {
    private const MAX_RENEWALS = 3;
    use MetadataTrait;

    public function __construct(
        private readonly BookRepository $bookRepository,
        private readonly UserRepository $userRepository,
        private readonly CopyRepository $copyRepository,
        private readonly LoanRepository $loanRepository,
        private readonly PaymentRepository $paymentRepository
    ){}

    public function checkoutBook(CreateLoanRequest $loanRequest, float $amount): Copy {
        $loanId = $this->getNextId("loan");
        $paymentId = $this->getNextId("payment");

        if($this->userRepository->findById($loanRequest->userId) == null)
            throw new Exception("User not found with user id: $loanRequest->userId");
        
        if($this->bookRepository->findById($loanRequest->copyId) == null)
            throw new Exception("Book not found with book id: $loanRequest->copyId");
        $book = $this->bookRepository->findById($loanRequest->copyId);

        $copy = $this->findAvailableCopy($book->getBookId());

        $loan = new Loan(
            $loanId,
            $loanRequest->userId,
            $loanRequest->copyId,
            $paymentId,
            new DateTimeImmutable(),
            $loanRequest->dueDate,
            null,
            LoanStatus::Borrowed,
            0,
            0
        );

        $payment = new Payment(
            $paymentId,
            $loanId,
            $amount,
            null
        );

        $this->loanRepository->save($loan);
        $this->paymentRepository->save($payment);
        
        return $copy;
    }

    public function returnBook(int $userId, int $copyId): void {
        $copy = $this->copyRepository->findById($copyId);
        if($copy == null)
            throw new Exception("Copy not found with id: $copyId");

        $loan = $this->loanRepository->findByUserIdAndCopyId($userId, $copyId);
        if($loan == null)
            throw new Exception("Loan not found with user id: $userId and copy id: $copyId");

        $copy->setStatus(CopyStatus::Available);

        $loan->setReturnDate(new DateTimeImmutable());
        $loan->setFine($this->calculateFine($loan->getLoanDueDate(), $loan->getLoanReturnDate()));
        $loan->setLoanStatus(LoanStatus::Returned);

        $this->copyRepository->save($copy);
        $this->loanRepository->save($loan);

        if($loan->getLoanFine() > 0){
            $payment = new Payment(
                $this->getNextId("payment"),
                $loan->getLoanId(),
                $loan->getLoanFine(),
                new DateTimeImmutable()
            );
            $this->paymentRepository->save($payment);
        }
    }

    public function renewLoan(int $userId, int $copyId): void {
        $copy = $this->copyRepository->findById($copyId);
        if($copy == null)
            throw new Exception("Copy not found with id: $copyId");

        $loan = $this->loanRepository->findByUserIdAndCopyId($userId, $copyId);
        if($loan == null)
            throw new Exception("Loan not found with user id: $userId and copy id: $copyId");
        if($loan->getLoanStatus() === LoanStatus::Returned)
            throw new Exception("Can't renew a returned loan");
        if(new DateTimeImmutable() > $loan->getLoanDueDate())
            throw new Exception("Can't renew an overdue loan");
        if($loan->getLoanRenewalCount() >= self::MAX_RENEWALS)
            throw new Exception("Maximum number of renewals reached");

        $loan->setDueDate($loan->getLoanDueDate()->modify('+7 days'));
        $loan->setRenewalCount($loan->getLoanRenewalCount() + 1);

        $this->loanRepository->save($loan);
    }

    private function calculateFine(DateTimeImmutable $dueDate, DateTimeImmutable $returnDate): float {
        if($returnDate <=  $dueDate)
            return 0;

        $daysLate = $dueDate->diff($returnDate)->days;
        return $daysLate * 7;
    }

    private function findAvailableCopy(int $bookId): Copy {
        foreach ($this->copyRepository->findByBookId($bookId) as $copy) {
            if($copy->getCopyStatus() === CopyStatus::Available){
                $this->copyRepository->updateStatus($copy->getCopyId(), CopyStatus::Borrowed);
                return $copy;
            }
        }
        throw new Exception("No available copies with book id: $bookId");
    }
}