<?php

namespace LMS\Service;

use Exception;
use LMS\Repository\UserRepository;
use LMS\Traits\MetadataTrait;
use LMS\Domain\User;
use LMS\Repository\LoanRepository;
use LMS\Repository\CopyRepository;

class UserService {
    use MetadataTrait;

    public function __construct(
        private UserRepository $userRepository,
        private LoanRepository $loanRepository,
        private CopyRepository $copyRepository
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

    public function getBorrowedCopies(int $id): array {
        $copies = [];
        $loans = $this->loanRepository->findByUserId($id);
        if(empty($loans))
            throw new Exception("User with id: $id hasn't borrowed anything");

        foreach ($loans as $loan) {
            $copy = $this->copyRepository->findById($loan->getLoanCopyId());
            if($copy !== null)
                $copies[] = $copy;
        }
        return $copies;
    }
}