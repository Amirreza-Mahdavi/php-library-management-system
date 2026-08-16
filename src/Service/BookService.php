<?php

namespace LMS\Service;

use Exception;
use LMS\Repository\BookRepository;
use LMS\DTO\AddBookRequest;
use LMS\Traits\MetadataTrait;
use LMS\Domain\Book;
use LMS\DTO\CreateLoanRequest;
use LMS\DTO\CreatePaymentRequest;
use LMS\Repository\UserRepository;
use LMS\Repository\CopyRepository;
use LMS\Repository\LoanRepository;
use LMS\Repository\PaymentRepository;
use LMS\Domain\Loan;
use LMS\Enums\LoanStatus;
use LMS\Domain\Payment;
use LMS\Domain\Copy;
use DateTimeImmutable;

class BookService {

    private BookRepository $bookRepository;
    private UserRepository $userRepository;
    private CopyRepository $copyRepository;
    private LoanRepository $loanRepository;
    private PaymentRepository $paymentRepository;
    use MetadataTrait;
    
    public function addBook(AddBookRequest $request): void {
        $book = new Book(
            $this->getNextId("book"),
            $request->title,
            $request->author,
            $request->categoryId,
            $request->language
        );
        $this->bookRepository->save($book);
    }

    public function removeBook(int $id): void {
        $this->bookRepository->remove($id);
    }

    public function getBooks(): array {
        return $this->bookRepository->findAll();
    }

    public function searchByTitle(string $keyword): array {
        return $this->bookRepository->searchByTitle($keyword);
    }

    public function searchByAuthor(string $keyword): array {
        return $this->bookRepository->searchByAuthor($keyword);
    }

    public function filterByCategory(int $categoryId): array {
        return $this->bookRepository->findByCategoryId($categoryId);
    }

    public function checkoutBook(CreateLoanRequest $loanRequest, float $amount): Copy {
        $loanId = $this->getNextId("loan");
        $paymentId = $this->getNextId("payment");

        if($this->userRepository->findById($loanRequest->userId) == null)
            throw new Exception("User not found with user id: $loanRequest->userId");
        
        if($this->bookRepository->findById($loanRequest->copyId) == null)
            throw new Exception("Book not found with book id: $loanRequest->copyId");
        $book = $this->bookRepository->findById($loanRequest->copyId);

        $copy = $this->copyRepository->findAvailableCopy($book->getBookId());

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
}