<?php

namespace LMS\Repository;

use LMS\Domain\Category;

interface CategoryRepository {

    public function findAll(): array;
    public function findById(int $id): ?Category;
    public function save(Category $category): void;
    public function remove(int $id): void;

}