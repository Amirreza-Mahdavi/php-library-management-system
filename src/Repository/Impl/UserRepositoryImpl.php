<?php

namespace LMS\Repository\Impl;

use Exception;
use LMS\Repository\UserRepository;
use LMS\Domain\User;

class UserRepositoryImpl implements UserRepository {
    private string $file = __DIR__ . '/../../../data/users.json';

    public function findById(int $id): ?User {
         $data = json_decode(file_get_contents($this->file), true) ?? [];

        foreach ($data as $user) {
            if ($user['user_id'] === $id)
                return $this->mapToUser($user);
        }
        return null;
    }

    public function findByRoleId(int $roleId): array {
        $data = json_decode(file_get_contents($this->file), true) ?? [];
        $users = [];

        foreach ($data as $user) {
            if ($user['role_id'] === $roleId)
                $users[] = $this->mapToUser($user);
        }
        return $users;
    }

    public function findByEmail(string $email): ?User {
         $data = json_decode(file_get_contents($this->file), true) ?? [];

        foreach ($data as $user) {
            if ($user['email'] === $email)
                return $this->mapToUser($user);
        }
        return null;
    }

    public function updateUserName(int $id, string $name): void {
        $data = json_decode(file_get_contents($this->file), true) ?? [];

        foreach ($data as &$user) {
            if($user['user_id'] === $id){
                $user['name'] = $name;
                break;
            }
            unset($user);
        }
        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function updateUserPassword(int $id, string $oldPass, string $newPass): void {
        $data = json_decode(file_get_contents($this->file), true) ?? [];

        foreach ($data as &$user) {
            if($user['user_id'] === $id && password_verify($oldPass, $user['password'])){
                $this->checkPassword($oldPass, $newPass);
                $user['password'] = password_hash($newPass, PASSWORD_DEFAULT);
                break;
            }
            unset($user);
        }
        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT));
    }

    private function checkPassword(string $oldPass, string $newPass): void {
        if ($oldPass === $newPass)
            throw new Exception("New password matches previous password");
    }

    public function save(User $user): void {
        $data = json_decode(file_get_contents($this->file), true) ?? [];
        $data[] = $this->mapToStorage($user);

        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function remove(int $id): void {
        $data = json_decode(file_get_contents($this->file), true) ?? [];
        $newData = [];
        
        foreach ($data as $user) {
            if($user['user_id'] !== $id)
                $newData[] = $user;
        }
        file_put_contents($this->file, json_encode($newData, JSON_PRETTY_PRINT));
    }

    private function mapToUser(array $user): User {
        return new User(
            (int)$user['user_id'],
            $user['name'],
            $user['email'],
            (int)$user['role_id'],
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