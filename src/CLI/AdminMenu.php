<?php

namespace LMS\CLI;

use DateTimeImmutable;
use Exception;
use LMS\Service\UserService;
use LMS\Service\AuthService;
use LMS\Service\BookService;
use LMS\CLI\Console;
use LMS\DTO\AddBookRequest;
use LMS\Enums\CopyStatus;
use LMS\DTO\AddCopyRequest;
use LMS\DTO\CreateLoanRequest;
use LMS\Enums\LoanStatus;
use LMS\Service\CopyService;
use LMS\Service\LoanService;


class AdminMenu extends UserMenu {
    public function __construct(
        private readonly CopyService $copyService,
        private readonly LoanService $loanService,
        UserService $userService,
        AuthService $authService,
        BookService $bookService,
        Console $console,
    ){
        parent::__construct($authService, $userService, $bookService, $console);
    }

    public function showMenu(): bool {
        $this->console->writeLine("1. Show User Information");
        $this->console->writeLine("2. Update Name");
        $this->console->writeLine("3. Update Password");
        $this->console->writeLine("4. Show Borrowed Books (Enter A User Id)");
        $this->console->writeLine("5. Search Books By Title");
        $this->console->writeLine("6. Search Books By Author");
        $this->console->writeLine("7. Filter By Category (Enter Category Id)");
        $this->console->writeLine("8. Show Books");
        $this->console->writeLine("9. Show Members");
        $this->console->writeLine("10. Add Book");
        $this->console->writeLine("11. Remove Book");
        $this->console->writeLine("12. Add Copy");
        $this->console->writeLine("13. Remove Copy");
        $this->console->writeLine("14. Filter Copies By Status");
        $this->console->writeLine("15. Update Copy Status");
        $this->console->writeLine("16. Checkout Book");
        $this->console->writeLine("17. Return Book");
        $this->console->writeLine("18. Renew Loan");
        $this->console->writeLine("19. Logout");

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
                $this->showMembers();
                return true;
            case 10:
                $this->addBook();
                return true;
            case 11: 
                $this->removeBook();
                return true;
            case 12:
                $this->addCopy();
                return true;
            case 13:
                $this->removeCopy();
                return true;
            case 14:
                $this->filterCopiesByStatus();
                return true;
            case 15:
                $this->updateCopyStatus();
                return true;
            case 16:
                $this->checkoutBook();
                return true;
            case 17:
                $this->returnBook();
                return true;
            case 18:
                $this->renewLoan();
                return true;
            case 19:
                $this->logout();
                return true;
            default:
                $this->console->error("Invalid input!");
                return true;
        }
    }

    protected function showBorrowedBooks(): void {
        $id = $this->console->readInt("Enter user id: ");
        try {
            $books = $this->userService->getBorrowedBooks($id);
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

    private function showMembers(): void {
        $members = $this->userService->getMembers();
        if($members === null)
            $this->console->error("No users found");

        foreach ($members as $member) {
            $this->console->writeLine(
                "{$member->getUserId()} - {$member->getUserName()} - {$member->getUserEmail()}"
            );
        }
    }

    private function addBook(): void {
        $title = $this->console->readLine("Enter book title: ");
        $author = $this->console->readLine("Enter book author: ");
        $categoryId = $this->console->readInt("Enter category id: ");
        $language = $this->console->readLine("Enter book language: ");

        $request = new AddBookRequest($title, $author, $categoryId, $language);

        $this->bookService->addBook($request);
        $this->console->success("Successfully added book");
    }

    private function removeBook(): void {
        $id = $this->console->readInt("Enter book id: ");

        $this->bookService->removeBook($id);
        $this->console->success("Successfully removed book");
    }

    private function addCopy(): void {
        $bookId = $this->console->readInt("Enter book id: ");

        $id = $this->showCopyStatusMenu();
    
        $status = $this->selectCopyStatus($id);
        $location = $this->console->readLine("Enter copy location");

        $request = new AddCopyRequest($bookId, $status, $location);

        $this->copyService->addCopy($request);
        $this->console->success("Successfully added copy");
    }

    private function showCopyStatusMenu(): int {
        $this->console->writeLine("Select a status,");
        $this->console->writeLine("1. Available");
        $this->console->writeLine("2. Unavailable");
        $this->console->writeLine("3. Borrowed");
        return $this->console->readInt("Enter the selected status id: ");
    }

    private function selectCopyStatus(int $id): CopyStatus {
        switch($id){
            case 1:
                return CopyStatus::Available;
            case 2:
                return CopyStatus::Unavailable;
            case 3:
                return CopyStatus::Borrowed;
            default:
            throw new Exception("Invalid input");
        }
    }

    private function removeCopy(): void {
        $id = $this->console->readInt("Enter copy id: ");

        $this->copyService->removeCopy($id);
        $this->console->success("Successfully removed copy");
    }

    private function filterCopiesByStatus(): void {
        $id = $this->showCopyStatusMenu();
        $status = $this->selectCopyStatus($id);

        $copies = $this->copyService->filterByStatus($status);
        if(empty($copies))
            $this->console->error("No copies found");

        foreach ($copies as $copy) {
            $book = $this->bookService->findById($copy->getCopyBookId());
            $bookTitle = $book?->getTitle() ?? "Unknown";
            $this->console->writeLine(
                "{$copy->getCopyId()} - $bookTitle"
            );
        }
    }

    private function updateCopyStatus(): void {
        $copyId = $this->console->readInt("Enter copy id: ");
    
        $statusId = $this->showCopyStatusMenu();
        $status = $this->selectCopyStatus($statusId);

        $this->copyService->updateStatus($copyId, $status);
        $this->console->success("Successfully updated status");
    }

    private function checkoutBook(): void {
        $userId = $this->console->readInt("Enter user id: ");
        $copyId = $this->console->readInt("Enter copy id: ");
        $date = new DateTimeImmutable($this->console->readLine("Enter due date (for example: 2027-07-07): "));

        $statusId = $this->showLoanStatusMenu();
        $status = $this->selectLoanStatus($statusId);

        $amount = (float) $this->console->readLine("Enter loan fee: ");

        $request = new CreateLoanRequest($userId, $copyId, $date, $status);

        try {
            $this->loanService->checkoutBook($request, $amount);
            $this->console->success("Checkout successful");
        }
        catch(Exception $e){
            $this->console->error($e->getMessage());
        }
    }

    private function returnBook(): void {
        $userId = $this->console->readInt("Enter user id: ");
        $copyId = $this->console->readInt("Enter copy id: ");

        try {
            $this->loanService->returnBook($userId, $copyId);
            $this->console->success("Copy returned successfully");
        }
        catch(Exception $e){
            $this->console->error($e->getMessage());
        }
    }

    private function renewLoan(): void {
        $userId = $this->console->readInt("Enter user id: ");
        $copyId = $this->console->readInt("Enter copy id: ");

        try {
            $this->loanService->renewLoan($userId, $copyId);
            $this->console->success("Loan renewed successfully");
        }
        catch(Exception $e){
            $this->console->error($e->getMessage());
        }
    }

    private function showLoanStatusMenu(): int {
        $this->console->writeLine("Select a status,");
        $this->console->writeLine("1. Borrowed");
        $this->console->writeLine("2. Returned");
        $this->console->writeLine("3. Overdue");
        $this->console->writeLine("4. Lost");
        return $this->console->readInt("Enter the selected status id: ");
    }

    private function selectLoanStatus(int $id): LoanStatus {
        switch($id){
            case 1:
                return LoanStatus::Borrowed;
            case 2:
                return LoanStatus::Returned;
            case 3:
                return LoanStatus::Overdue;
            case 4:
                return LoanStatus::Lost;
            default:
            throw new Exception("Invalid input");
        }
    }
}