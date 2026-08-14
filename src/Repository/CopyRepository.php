<?php

namespace LMS\Repository;

use LMS\Domain\Copy;

class CopyRepository {
    private string $file = __DIR__ . '/../../data/copies.json';

    private function mapToCopy(array $copy): Copy {
        return new Copy(
            $copy['copy_id'],
            $copy['book_id'],
            $copy['status'],
            $copy['location']
        );
    }
}