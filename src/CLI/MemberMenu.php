<?php 

namespace LMS\CLI;

use Exception;
use LMS\Service\UserService;
use LMS\Service\AuthService;
use LMS\Service\BookService;
use LMS\Service\CategoryService;
use LMS\CLI\Console;

class MemberMenu extends UserMenu {
    public function __construct(
        UserService $userService,
        AuthService $authService,
        BookService $bookService,
        CategoryService $categoryService,
        Console $console
    ){
        parent::__construct($authService, $userService, $bookService, $categoryService, $console);
    }

    public function showMenu(): bool {
        $this->console->writeLine("1. Show User Information");
        $this->console->writeLine("2. Update Name");
        $this->console->writeLine("3. Update Password");
        $this->console->writeLine("4. Show Borrowed Books");
        $this->console->writeLine("5. Search Books By Title");
        $this->console->writeLine("6. Search Books By Author");
        $this->console->writeLine("7. Filter By Category (Enter Category Id)");
        $this->console->writeLine("8. Show Books");
        $this->console->writeLine("9. Show Categories");
        $this->console->writeLine("10. Logout");

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
                $this->showCategories();
                return true;
            case 10:
                $this->logout();
                return true;
            default:
                $this->console->error("Invalid input!");
                return true;
        }
    }
}