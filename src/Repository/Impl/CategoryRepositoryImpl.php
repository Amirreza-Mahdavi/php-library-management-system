<?php 

namespace LMS\Repository\Impl;

use LMS\Domain\Category;
use LMS\Repository\CategoryRepository;

class CategoryRepositoryImpl implements CategoryRepository {
    private string $file = __DIR__ . '/../../data/categories.json';

    public function findAll(): array {
         $data = json_decode(file_get_contents($this->file), true) ?? [];

        return array_map(
            fn($category) => $this->mapToCategory($category),
            $data
        );
    }

    public function findById(int $id): ?Category {
         $data = json_decode(file_get_contents($this->file), true) ?? [];

        foreach ($data as $category) {
            if ($category['category_id'] === $id)
                return $this->mapToCategory($category);
        }
        return null;
    }

    public function save(Category $category): void {
        $data = json_decode(file_get_contents($this->file), true) ?? [];
        $data[] = $this->mapToStorage($category);

        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function remove(int $id): void {
        $data = json_decode(file_get_contents($this->file), true) ?? [];
        $newData = [];

        foreach ($data as $category) {
            if($category['copy_id'] !== $id) 
                $newData[] = $category;
        }
        file_put_contents($this->file, json_encode($newData, JSON_PRETTY_PRINT));
    }

    private function mapToCategory(array $category): Category {
        return new Category(
            (int) $category['category_id'],
            $category['category_name']
        );
    }

    private function mapToStorage(Category $category): array {
        return [
            'category_id' => $category->getCategoryId(),
            'category_name' => $category->getCategoryName()
        ];
    }
}