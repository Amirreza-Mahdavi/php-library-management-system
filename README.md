# PHP Library Management System

A **Command-Line Interface (CLI) Library Management System** built with **PHP 8** using a layered architecture.

Instead of using a database, the application stores data in JSON files, demonstrating repository patterns, dependency injection, DTOs, and clean separation of concerns.

## Features

This project is primarily designed for **Admin/Librarian usage**, while also providing features for regular members.

### Authentication

- User registration
- Login and logout
- Password hashing with `password_hash()`
- Role-based access (Admin / Member)

### Member Features

- View profile
- Update name
- Update password
- Browse categories
- Browse books
- Search by title
- Search by author
- Filter books by category
- View borrowed books

### Admin Features

- Add and remove categories
- Add and remove books
- Add and remove copies
- Update copy status
- Filter copies by status
- View members
- Checkout books for users
- Process returns
- Renew loans
- All Member Features

### Loan Management

- Due dates
- Renewals
- Fine calculation
- Copy availability tracking

## Architecture

The project follows a layered architecture:

```text
CLI -> Service -> Repository -> JSON Files
```

### Layers

| Layer | Responsibility |
|---|---|
| **CLI** | Handles user interaction through the terminal |
| **DTO** | Transfers and validates user input |
| **Service** | Contains application and business logic |
| **Repository** | Reads and writes data to JSON files |
| **Domain** | Contains business entities and domain logic |

## Technologies

- PHP 8
- Composer (PSR-4 Autoloading)
- JSON File Storage
- Object-Oriented Programming
- Dependency Injection
- DTO Pattern
- Repository Pattern

## Running the Project

### Requirements
- PHP 8+
- Composer

### Clone The Repository

```bash
git clone https://github.com/Amirreza-Mahdavi/php-library-management-system.git
```
```bash
cd php-library-management-system
```

### Install dependencies

```bash
composer install
```
### Start the application

```bash
php example/Main.php
```

## CLI Usage

### Demo Credentials

#### Admin

```text
Email: admin@example.com
Password: admin123
```

#### Member

```text
Email: member@example.com
Password: member123
```

### Main Menu

```text
1. Login
2. Register
3. Logout
4. Exit
```

### Member Menu

After logging in as a member:

```text
Member Menu

1. Show User Information
2. Update Name
3. Update Password
4. Show Borrowed Books
...
```

## Data Storage

Instead of using a relational database, the application persists data inside **JSON files**.

For example, a book can be stored as:

```json
{
    "book_id": 1,
    "title": "Book",
    "author": "Author",
    "category_id": 1,
    "language": "English"
}
```

Repositories are responsible for converting between **JSON arrays** and **Domain objects**, keeping persistence concerns separate from the application's business logic.

## Project Structure

```text
php-library-management-system/
├── data/               # JSON data store
├── example/
│   └── Main.php        # Application entry point
├── src/
│   ├── CLI/             # Menus and console interaction
│   ├── DTO/              # Input validation / transport objects
│   ├── Domain/            # Core domain entities
│   ├── Enums/              # Backed enums (roles, statuses)
│   ├── Repository/         # Repository interfaces + JSON implementations
│   ├── Service/             # Business logic
│   └── Traits/               # Shared reusable behavior
├── composer.json
└── README.md
```

## Design Goals

The main goal of this project is to demonstrate how a small PHP application can be structured using clean separation of responsibilities without relying on a relational database.

The project focuses on:

- Separation of concerns
- Dependency injection
- Repository abstraction
- DTO-based input handling
- Domain entities
- Service-layer business logic
- File-based persistence
- Object-oriented PHP