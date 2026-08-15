<?php

namespace LMS\Service;

use LMS\Repository\UserRepository;
use LMS\Traits\MetadataTrait;

class UserService {
    private UserRepository $userRepository;
    use MetadataTrait;

    public function getMembers(): array {
        return $this->userRepository->findByRoleId(2);
    }

    public function updateUserName(int $userId, string $name): void {
        $this->userRepository->updateUserName($userId, $name);
    }

    public function updateUserPassword(int $userId, string $pass): void {
        $this->userRepository->updateUserPassword($userId, $pass);
    }
}