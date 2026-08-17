<?php

namespace LMS\CLI;

use Exception;
use LMS\Service\AuthService;
use LMS\CLI\Console;
use LMS\DTO\RegisterRequest;

class AuthMenu {
    public function __construct(
        private readonly AuthService $authService,
        private readonly Console $console
    ){}

    public function showMenu(): bool {
        $this->console->writeLine("1.Login");
        $this->console->writeLine("2.Register");
        $this->console->writeLine("3.Logout");
        $this->console->writeLine("4.Exit");

        $choice = $this->console->readInt("Choose an option: ");
        return $this->selectOption($choice);
    }

    private function selectOption(int $option): bool {
        switch($option) {
            case 1:
                $this->login();
                return true;
            case 2:
                $this->register();
                return true;
            case 3:
                return false;
            default:
                $this->console->error("Invalid input!");
                return true;
        }
    }

    public function login(): void {
        $email = $this->console->readLine("Email: ");
        $password = $this->console->readLine("Password: ");

        try {
            $this->authService->login($email, $password);
            $this->console->success("Login successful");
        }
        catch(Exception $e) {
            $this->console->error($e->getMessage());
        }
    }

    public function register(): void {
        $name = $this->console->readLine("Name: ");
        $email = $this->console->readLine("Email: ");
        $password = $this->console->readLine("Password: ");

        $registerRequest = new RegisterRequest($name, $email, $password);

        try {
            $this->authService->register($registerRequest);
            $this->console->success("Register successful");
        }
        catch(Exception $e) {
            $this->console->error($e->getMessage());
        }
    }
}