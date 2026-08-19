<?php

namespace LMS\Repository;

use Lms\Domain\Copy;
use LMS\Enums\CopyStatus;

interface CopyRepository {
    
    public function findById(int $id): ?Copy;
    public function findByBookId(int $bookId): array;
    public function findByStatus(CopyStatus $status): array;
    public function updateStatus(int $id, CopyStatus $status): void;
    public function save(Copy $copy): void;
    public function remove(int $id): void;

}