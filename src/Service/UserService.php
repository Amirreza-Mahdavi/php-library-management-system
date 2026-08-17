<?php

namespace LMS\Service;

use Exception;
use LMS\Repository\UserRepository;
use LMS\Traits\MetadataTrait;
use LMS\Domain\User;
use LMS\Repository\LoanRepository;
use LMS\Repository\CopyRepository;
use LMS\Repository\BookRepository;

class UserService {
    use MetadataTrait;

    public function __construct(
        private UserRepository $userRepository,
        private LoanRepository $loanRepository,
        private CopyRepository $copyRepository,
        private BookRepository $bookRepository
    ){}

    public function getMembers(): array {
        return $this->userRepository->findByRoleId(2);
    }

    public function findById(int $id): ?User {
        return $this->userRepository->findById($id);
    }

    public function updateUserName(int $userId, string $name): void {
        $this->userRepository->updateUserName($userId, $name);
    }

    public function updateUserPassword(int $userId, string $oldPass, string $newPass): void {
        $this->userRepository->updateUserPassword($userId, $oldPass, $newPass);
    }

    public function getBorrowedBooks(int $id): array {
        $books = [];
        $loans = $this->loanRepository->findByUserId($id);
        if(empty($loans))
            throw new Exception("User with id: $id hasn't borrowed anything");

        foreach ($loans as $loan) {
            $copy = $this->copyRepository->findById($loan->getLoanCopyId());
            $book = $this->bookRepository->findById($copy->getCopyBookId());
            if($book !== null)
                $books[] = $book;
        }
        return $books;
    }
}