<?php

namespace LMS\CLI;

use LMS\Service\AuthService;
use LMS\Service\BookService;
use LMS\Service\CategoryService;
use LMS\Service\CopyService;
use LMS\Service\LoanService;
use LMS\Service\UserService;
use LMS\CLI\Console;
use LMS\Repository\Impl\BookRepositoryImpl;
use LMS\Repository\Impl\CategoryRepositoryImpl;
use LMS\Repository\Impl\CopyRepositoryImpl;
use LMS\Repository\Impl\LoanRepositoryImpl;
use LMS\Repository\Impl\PaymentRepositoryImpl;
use LMS\Repository\Impl\UserRepositoryImpl;

class Application {
    private AuthService $authService;
    private AuthMenu $authMenu;
    private MemberMenu $memberMenu;
    private AdminMenu $adminMenu;

    public function __construct(){

        $bookRepository = new BookRepositoryImpl();
        $categoryRepository = new CategoryRepositoryImpl();
        $copyRepository = new CopyRepositoryImpl();
        $loanRepository = new LoanRepositoryImpl();
        $paymentRepository = new PaymentRepositoryImpl();
        $userRepository = new UserRepositoryImpl();

        $this->authService = new AuthService($userRepository);
        $bookService = new BookService($bookRepository);
        $categoryService = new CategoryService($categoryRepository);
        $copyService = new CopyService($copyRepository);
        $loanService = new LoanService(
            $bookRepository,
            $userRepository,
            $copyRepository,
            $loanRepository,
            $paymentRepository
        );
        $userService = new UserService(
            $userRepository,
            $loanRepository,
            $copyRepository,
            $bookRepository
        );

        $console = new Console();
        $this->authMenu = new AuthMenu($this->authService, $console);
        $this->adminMenu = new AdminMenu(
            $copyService,
            $loanService,
            $userService,
            $this->authService,
            $bookService,
            $categoryService,
            $console
        );
        $this->memberMenu = new MemberMenu(
            $userService,
            $this->authService,
            $bookService,
            $categoryService,
            $console
        );
    }

    public function run(): void {
        $running = true;

        while ($running) {
            $user = $this->authService->getCurrentUser();
            
            if ($user === null)
                $running = $this->authMenu->showMenu();
            elseif ($user->getUserRoleId() === 1)
                $running = $this->adminMenu->showMenu();
            else
                $running = $this->memberMenu->showMenu();
        }
    }
}