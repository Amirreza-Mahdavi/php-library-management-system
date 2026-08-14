<?php

namespace LMS\Repository;

use LMS\Domain\User;

class UserRepository {
    private string $file = __DIR__ . '/../../data/users.json';

    private function mapToUser(): array {
        $users = [];
        $data = json_decode(file_get_contents($this->file), true);

        foreach ($data as $user) {
            $users[] = new User(
                $user['user_id'],
                $user['name'],
                $user['email'],
                $user['role_id'],
                $user['password']
            );
        }
        return $users;
    }
}