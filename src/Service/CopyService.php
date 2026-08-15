<?php

namespace LMS\Service;

use LMS\Repository\CopyRepository;
use LMS\Traits\MetadataTrait;
use LMS\Domain\Copy;
use LMS\DTO\AddCopyRequest;
use LMS\Enums\CopyStatus;

class CopyService {

    private CopyRepository $copyRepository;
    use MetadataTrait;

    public function getCopies(int $bookId): array {
        return $this->copyRepository->findByBookId($bookId);
    }

    public function addCopy(AddCopyRequest $request): void {
        $copy = new Copy(
            $this->getNextId("copy"),
            $request->bookId,
            $request->status,
            $request->location
        );
        $this->copyRepository->save($copy);
    }

    public function removeCopy(int $id): void {
        $this->copyRepository->remove($id);
    }

    public function filterByStatus(CopyStatus $status): array {
        return $this->copyRepository->findByStatus($status);
    }

    public function updateStatus(int $id, CopyStatus $status): void {
        $this->copyRepository->updateStatus($id, $status);
    }
}
