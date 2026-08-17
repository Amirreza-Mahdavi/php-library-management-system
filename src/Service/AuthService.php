<?php

namespace LMS\Service;

use Exception;
use LMS\Domain\User;
use LMS\Repository\UserRepository;
use LMS\DTO\RegisterRequest;
use LMS\Traits\MetadataTrait;

class AuthService {
    use MetadataTrait;
    private ?User $currentUser = null;

    public function __construct(
        private readonly UserRepository $userRepository
    ){}

    public function register(RegisterRequest $request): void {
        if($this->userRepository->findByEmail($request->email) !== null)
            throw new Exception("User already exists with email: $request->email");

        $user = new User(
            $this->getNextId("user"),
            $request->name,
            $request->email,
            2,
            password_hash($request->password, PASSWORD_DEFAULT)
        );
        $this->userRepository->save($user);
        $this->login($request->email, $request->password);
    }

    public function login(string $email, string $password): void {
        $user = $this->userRepository->findByEmail($email);
        if($user === null)
            throw new Exception("Invalid credentials");

        if(!password_verify($password, $user->getUserPassword()))
            throw new Exception("Invalid credentials");

        $this->currentUser = $user;
    }

    public function logout(): void {
        $this->currentUser = null;
    }

    public function getCurrentUser(): ?User {
        return $this->currentUser;
    }

    public function requireAdmin(): void {
        if($this->currentUser === null)
            throw new Exception("Not logged in");
        if($this->currentUser->getUserRoleId() !== 1)
            throw new Exception("Access denied");
    }
}