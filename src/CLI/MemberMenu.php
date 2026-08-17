<?php 

namespace LMS\CLI;

use Exception;
use LMS\Service\UserService;
use LMS\Service\AuthService;
use LMS\Service\BookService;
use LMS\CLI\Console;

class MemberMenu {
    public function __construct(
        private readonly UserService $userService,
        private readonly AuthService $authService,
        private readonly BookService $bookService,
        private readonly Console $console
    ){}

    public function showMenu(): bool {
        $this->console->writeLine("1. Show User Information");
        $this->console->writeLine("2. Update Name");
        $this->console->writeLine("3. Update Password");
        $this->console->writeLine("4. Show Borrowed Books");
        $this->console->writeLine("5. Search Books By Title");
        $this->console->writeLine("6. Search Books By Author");
        $this->console->writeLine("7. Filter By Category (Enter Category Id)");
        $this->console->writeLine("8. Show Books");
        $this->console->writeLine("9. Logout");

        $choice = $this->console->readInt("Choose an option: ");
        return $this->selectOption($choice);
    }

    private function selectOption(int $option): bool {
        switch($option) {
            case 1:
                $this->showUserInfo();
                return true;
            case 2:
                $this->updateName();
                return true;
            case 3:
                $this->updatePassword();
                return true;
            case 4:
                $this->showBorrowedBooks();
                return true;
            case 5:
                $this->searchBooksByTitle();
                return true;
            case 6:
                $this->searchBooksByAuthor();
                return true;
            case 7:
                $this->filterBooksByCategory();
                return true;
            case 8:
                $this->showBooks();
                return true;
            case 9:
                $this->logout();
                return true;
            default:
                $this->console->error("Invalid input!");
                return true;
        }
    }

    private function showUserInfo(): void {
        $user = $this->authService->getCurrentUser();

        $this->console->writeLine("Id: {$user->getUserId()}");
        $this->console->writeLine("Name: {$user->getUserName()}");
        $this->console->writeLine("Email: {$user->getUserEmail()}");
    }

    private function updateName(): void {
        $user = $this->authService->getCurrentUser();
        $newName = $this->console->readLine("Enter new name: ");

        $this->userService->updateUserName($user->getUserId(), $newName);
    }

    private function updatePassword(): void {
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

    private function showBorrowedBooks(): void {
        $user = $this->authService->getCurrentUser();

        try {
            $books = $this->userService->getBorrowedBooks($user->getUserId());
            foreach ($books as $book) {
                $this->console->writeLine(
                    "{$book->getBookId()} - {$book->getTitle()} by {$book->getAuthor()}"
                );
            }
        }
        catch(Exception $e){
            $this->console->error($e->getMessage());
        }
    }

    private function searchBooksByTitle(): void {
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

    private function searchBooksByAuthor(): void {
        $keyword = $this->console->readLine("Search a book author: ");

        $this->displayBooks($this->bookService->searchByAuthor($keyword));
    }

    private function filterBooksByCategory(): void {
        $id = $this->console->readInt("Enter Category Id: ");

        $this->displayBooks($this->bookService->filterByCategory($id));
    }

    private function showBooks(): void {
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

    private function logout(): void {
        $this->authService->logout();
        $this->console->success("Logout successful");
    }
}