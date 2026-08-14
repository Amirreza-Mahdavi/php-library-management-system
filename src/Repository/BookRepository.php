<?php

namespace LMS\Repository;

use LMS\Domain\Book;

class BookRepository {
    private string $file = __DIR__ . '/../../data/books.json';

    public function findAll(): array {
        $data = json_decode(file_get_contents($this->file), true, LOCK_EX);

        return array_map(
            fn($book) => $this->mapToBook($book),
            $data
        );
    }

    public function findById(int $id): ?Book {
        $data = json_decode(file_get_contents($this->file), true, LOCK_EX);

        foreach ($data as $book) {
            if ($book['book_id'] === $id)
                return $this->mapToBook($book);
        }
        return null;
    }

    public function findByCategoryId(int $categoryId): array {
        $data = json_decode(file_get_contents($this->file), true, LOCK_EX);
        $books = [];

        foreach ($data as $book) {
            if ($book['category_id'] === $categoryId)
                $books[] = $this->mapToBook($book);
        }
        return $books;
    }

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