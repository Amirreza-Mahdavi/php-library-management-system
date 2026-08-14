<?php

namespace LMS\Repository;

use LMS\Domain\User;

class UserRepository {
    private string $file = __DIR__ . '/../../data/users.json';

    public function findById(int $id): ?User {
        $data = json_decode(file_get_contents($this->file), true, LOCK_EX);

        foreach ($data as $user) {
            if ($user['user_id'] === $id)
                return $this->mapToUser($user);
        }
        return null;
    }

    public function findByRoleId(int $roleId): array {
        $data = json_decode(file_get_contents($this->file), true, LOCK_EX);
        $users = [];

        foreach ($data as $user) {
            if ($user['role_id'] === $roleId)
                $users[] = $this->mapToUser($user);
        }
        return $users;
    }

    public function findByEmail(string $email): ?User {
        $data = json_decode(file_get_contents($this->file), true, LOCK_EX);

        foreach ($data as $user) {
            if ($user['email'] === $email)
                return $this->mapToUser($user);
        }
        return null;
    }

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