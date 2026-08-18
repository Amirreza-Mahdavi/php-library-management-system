<?php

namespace LMS\CLI;

use LMS\Repository\BookRepository;
use LMS\Repository\CopyRepository;
use LMS\Repository\LoanRepository;
use LMS\Repository\UserRepository;
use LMS\Repository\PaymentRepository;
use LMS\Service\AuthService;
use LMS\Service\BookService;
use LMS\Service\CopyService;
use LMS\Service\LoanService;
use LMS\Service\UserService;
use LMS\CLI\Console;

class Application {
    private AuthService $authService;
    private AuthMenu $authMenu;
    private MemberMenu $memberMenu;
    private AdminMenu $adminMenu;

    public function __construct(){

        $bookRepository = new BookRepository();
        $copyRepository = new CopyRepository();
        $loanRepository = new LoanRepository();
        $paymentRepository = new PaymentRepository();
        $userRepository = new UserRepository();

        $this->authService = new AuthService($userRepository);
        $bookService = new BookService($bookRepository);
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
            $console
        );
        $this->memberMenu = new MemberMenu(
            $userService,
            $this->authService,
            $bookService,
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