<?php

namespace LMS\Service;

use LMS\Repository\BookRepository;
use LMS\DTO\AddBookRequest;
use LMS\Traits\MetadataTrait;
use LMS\Domain\Book;

class BookService {

    private BookRepository $bookRepository;
    use MetadataTrait;
    
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
}