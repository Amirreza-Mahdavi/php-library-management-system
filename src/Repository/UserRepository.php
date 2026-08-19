<?php

namespace LMS\Repository;

use LMS\Domain\User;

interface UserRepository {

    public function findById(int $id): ?User;
    public function findByRoleId(int $roleId): array;
    public function findByEmail(string $email): ?User;
    public function updateUserName(int $id, string $name): void;
    public function updateUserPassword(int $id, string $oldPass, string $newPass): void;
    public function save(User $user): void;
    public function remove(int $id): void;

}