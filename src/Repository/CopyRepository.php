<?php

namespace LMS\Repository;

use LMS\Domain\Copy;

class CopyRepository {
    private string $file = __DIR__ . '/../../data/copies.json';

    private function mapToCopy(): array {
        $copies = [];
        $data = json_decode(file_get_contents($this->file), true);

        foreach ($data as $copy) {
            $copies[] = new Copy(
                $copy['copy_id'],
                $copy['book_id'],
                $copy['status'],
                $copy['location']
            );
        }
        return $copies;
    }
}