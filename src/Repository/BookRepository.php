<?php

namespace LMS\Repository;

use LMS\Domain\Book;

class BookRepository {
    private string $file = __DIR__ . '/../../data/books.json';

    private function mapToBook(array $book): Book {
        return new Book(
            $book['book_id'],
            $book['title'],
            $book['author'],
            $book['category_id'],
            $book['language']
        );
    }

    private function mapToStorage(Book $book): array {
        return [
            'book_id' => $book->getBookId(),
            'title' => $book->getTitle(),
            'author' => $book->getAuthor(),
            'category_id' => $book->getCategoryId(),
            'language' => $book->getLanguage()
        ];
    }
}