<?php

namespace LMS\Repository;

use LMS\Domain\Book;

interface BookRepository {

    public function findAll(): array;
    public function findById(int $id): ?Book;
    public function findByCategoryId(int $categoryId): array;
    public function searchByTitle(string $keyword): array;
    public function searchByAuthor(string $keyword): array;
    public function save(Book $book): void;
    public function remove(int $id): void;
    
}