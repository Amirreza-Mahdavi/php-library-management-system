<?php

namespace LMS\Repository;

use LMS\Domain\User;

class UserRepository {
    private string $file = __DIR__ . '/../../data/users.json';

    private function mapToUser(array $user): User {
        return new User(
            $user['user_id'],
            $user['name'],
            $user['email'],
            $user['role_id'],
            $user['password']
        );
    }

    private function mapToStorage(User $user): array {
        return [
            'user_id' => $user->getUserId(),
            'name' => $user->getUserName(),
            'email' => $user->getUserEmail(),
            'role_id' => $user->getUserRoleId(),
            'password' =>$user->getUserPassword()
        ];
    }
}