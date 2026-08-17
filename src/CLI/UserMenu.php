<?php 

namespace LMS\CLI;

use Exception;
use LMS\Service\AuthService;
use LMS\Service\UserService;
use LMs\Service\BookService;
use LMS\CLI\Console;


abstract class UserMenu {
    public function __construct(
        protected readonly AuthService $authService,
        protected readonly UserService $userService,
        protected readonly BookService $bookService,
        protected readonly Console $console
    ){}

    abstract function showMenu(): bool;

    protected function showUserInfo(): void {
        $user = $this->authService->getCurrentUser();

        $this->console->writeLine("Id: {$user->getUserId()}");
        $this->console->writeLine("Name: {$user->getUserName()}");
        $this->console->writeLine("Email: {$user->getUserEmail()}");
    }

    protected function updateName(): void {
        $user = $this->authService->getCurrentUser();
        $newName = $this->console->readLine("Enter new name: ");

        $this->userService->updateUserName($user->getUserId(), $newName);
    }

    protected function updatePassword(): void {
        $user = $this->authService->getCurrentUser();
        $oldPass = $this->console->readLine("Enter old password: ");
        $newPass = $this->console->readLine("Enter new password: ");

        try {
            $this->userService->updateUserPassword($user->getUserId(), $oldPass, $newPass);
            $this->console->success("Change Password successful");
        }
        catch(Exception $e){
            $this->console->error($e->getMessage());
        }
    }

    protected function searchBooksByTitle(): void {
        $keyword = $this->console->readLine("Search a book title: ");

        $books = $this->bookService->searchByTitle($keyword);

        if(empty($books))
            $this->console->error("No books found");
        foreach ($books as $book) {
            $this->console->writeLine(
                "{$book->getBookId()} - {$book->getTitle()} by {$book->getAuthor()}"
            );
        }
    }

    protected function searchBooksByAuthor(): void {
        $keyword = $this->console->readLine("Search a book author: ");

        $this->displayBooks($this->bookService->searchByAuthor($keyword));
    }

    protected function filterBooksByCategory(): void {
        $id = $this->console->readInt("Enter Category Id: ");

        $this->displayBooks($this->bookService->filterByCategory($id));
    }

    protected function showBooks(): void {
        $this->displayBooks($this->bookService->getBooks());
    }

    private function displayBooks(array $books): void {
        if(empty($books)){
            $this->console->error("No books found");
            return;
        }
        foreach ($books as $book) {
            $this->console->writeLine(
                "{$book->getBookId()} - {$book->getTitle()} by {$book->getAuthor()}"
            );
        }
    }

    protected function logout(): void {
        $this->authService->logout();
        $this->console->success("Logout successful");
    }
}