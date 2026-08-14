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
}