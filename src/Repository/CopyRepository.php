<?php

namespace LMS\Repository;

use LMS\Domain\Copy;

class CopyRepository {
    private string $file = __DIR__ . '/../../data/copies.json';

    public function findByBookId(int $bookId): array {
        $data = json_decode(file_get_contents($this->file), true, LOCK_EX);
        $copies = [];

        foreach ($data as $copy) {
            if ($copy['book_id'] === $bookId)
                $copies[] = $this->mapToCopy($copy);
        }
        return $copies;
    }

    private function mapToCopy(array $copy): Copy {
        return new Copy(
            $copy['copy_id'],
            $copy['book_id'],
            $copy['status'],
            $copy['location']
        );
    }

    private function mapToStorage(Copy $copy): array {
        return [
            'copy_id' => $copy->getCopyId(),
            'book_id' => $copy->getCopyBookId(),
            'status' => $copy->getCopyStatus(),
            'location' => $copy->getCopyLocation()
        ];
    }
}