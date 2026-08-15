<?php

namespace LMS\Repository;

use LMS\Domain\Copy;
use LMS\Enums\CopyStatus;

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

    public function findByStatus(CopyStatus $status): array {
        $data = json_decode(file_get_contents($this->file), true) ?? [];
        $copies = [];

        foreach ($data as $copy) {
            if ($copy['status'] === $status)
                $copies[] = $this->mapToCopy($copy);
        }
        return $copies;
    }

    public function updateStatus(int $id, CopyStatus $status): void {
        $data = json_decode(file_get_contents($this->file), true) ?? [];

        foreach ($data as &$copy) {
            if($copy['copy_id'] === $id){
                $copy['status'] = $status->value;
                break;
            }
            unset($copy);
        }
        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function save(Copy $copy): void {
        $data = json_decode(file_get_contents($this->file), true) ?? [];
        $data[] = $this->mapToStorage($copy);

        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function remove(int $id): void {
        $data = json_decode(file_get_contents($this->file), true) ?? [];
        $newData = [];

        foreach ($data as $copy) {
            if($copy['copy_id'] !== $id) 
                $newData[] = $copy;
        }
        file_put_contents($this->file, json_encode($newData, JSON_PRETTY_PRINT));
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