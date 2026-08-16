<?php 

namespace LMS\Respository;

use LMS\Domain\Category;

class CategoryRepository {
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

    private function mapToCategory(array $category): Category {
        return new Category(
            $category['category_id'],
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