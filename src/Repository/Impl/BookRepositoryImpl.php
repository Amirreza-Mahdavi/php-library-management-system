<?php

namespace LMS\Repository\Impl;

use LMS\Repository\BookRepository;
use LMS\Domain\Book;

class BookRepositoryImpl implements BookRepository {
    private string $file = __DIR__ . '/../../../data/books.json';

    public function findAll(): array {
         $data = json_decode(file_get_contents($this->file), true) ?? [];

        return array_map(
            fn($book) => $this->mapToBook($book),
            $data
        );
    }

    public function findById(int $id): ?Book {
         $data = json_decode(file_get_contents($this->file), true) ?? [];

        foreach ($data as $book) {
            if ($book['book_id'] === $id)
                return $this->mapToBook($book);
        }
        return null;
    }

    public function findByCategoryId(int $categoryId): array {
         $data = json_decode(file_get_contents($this->file), true) ?? [];
        $books = [];

        foreach ($data as $book) {
            if ($book['category_id'] === $categoryId)
                $books[] = $this->mapToBook($book);
        }
        return $books;
    }

    public function searchByTitle(string $keyword): array {
        $data = json_decode(file_get_contents($this->file), true) ?? [];
        $books = [];

        foreach ($data as $book){
            if(mb_stripos($book['title'], $keyword) !== false)
                $books[] = $this->mapToBook($book);
        }
        return $books;
    }

    public function searchByAuthor(string $keyword): array {
        $data = json_decode(file_get_contents($this->file), true) ?? [];
        $books = [];

        foreach ($data as $book){
            if(mb_stripos($book['author'], $keyword) !== false)
                $books[] = $this->mapToBook($book);
        }
        return $books;
    }

    public function save(Book $book): void {
        $data = json_decode(file_get_contents($this->file), true) ?? [];
        $data[] = $this->mapToStorage($book);

        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function remove(int $id): void {
        $data = json_decode(file_get_contents($this->file), true) ?? [];
        $newData = [];
        
        foreach ($data as $book) {
            if($book['book_id'] !== $id)
                $newData[] = $book;
        }
        file_put_contents($this->file, json_encode($newData, JSON_PRETTY_PRINT));
    }

    private function mapToBook(array $book): Book {
        return new Book(
            (int) $book['book_id'],
            $book['title'],
            $book['author'],
            (int) $book['category_id'],
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