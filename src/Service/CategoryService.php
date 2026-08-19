<?php

namespace LMS\Service;

use LMS\Repository\CategoryRepository;
use LMS\Domain\Category;
use LMS\Traits\MetadataTrait;

class CategoryService {
    use MetadataTrait;

    public function __construct(
        private readonly CategoryRepository $categoryRepository
    ){}

    public function getCategories(): array {
        return $this->categoryRepository->findAll();
    }

    public function addCategory(string $name): void {
        $category = new Category(
            $this->getNextId("category"),
            $name
        );
        $this->categoryRepository->save($category);
    }

    public function removeCategory(int $id): void {
        $this->categoryRepository->remove($id);
    }
}