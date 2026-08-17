<?php

namespace LMS\Service;

use LMS\Repository\BookRepository;
use LMS\DTO\AddBookRequest;
use LMS\Traits\MetadataTrait;
use LMS\Domain\Book;

class BookService {
    use MetadataTrait;

    public function __construct(
        private readonly BookRepository $bookRepository
    ){}

    public function findById(int $id): ?Book {
        return $this->bookRepository->findById($id);
    }
    
    public function addBook(AddBookRequest $request): void {
        $book = new Book(
            $this->getNextId("book"),
            $request->title,
            $request->author,
            $request->categoryId,
            $request->language
        );
        $this->bookRepository->save($book);
    }

    public function removeBook(int $id): void {
        $this->bookRepository->remove($id);
    }

    public function getBooks(): array {
        return $this->bookRepository->findAll();
    }

    public function searchByTitle(string $keyword): array {
        return $this->bookRepository->searchByTitle($keyword);
    }

    public function searchByAuthor(string $keyword): array {
        return $this->bookRepository->searchByAuthor($keyword);
    }

    public function filterByCategory(int $categoryId): array {
        return $this->bookRepository->findByCategoryId($categoryId);
    }
}