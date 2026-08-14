<?php

namespace LMS\Repository;

use LMS\Domain\Book;

class BookRepository {
    private string $file = __DIR__ . '/../../data/books.json';

    private function mapToBook(): array {
        $books = [];
        $data = json_decode(file_get_contents($this->file), true);

        foreach ($data as $book) {
            $books[] = new Book(
                $book['book_id'],
                $book['title'],
                $book['author'],
                $book['category_id'],
                $book['language']
            );
        }
        return $books;
    }
}