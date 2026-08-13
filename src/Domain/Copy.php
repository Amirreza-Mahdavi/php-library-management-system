<?php

class Copy {

    public function __construct(
        private readonly int $copyId,
        private readonly int $bookId,
        private CopyStatus $status,
        private string $location
    ){}

    public function getCopyId(){
        return $this->copyId;
    }
    public function getCopyBookId(){
        return $this->bookId;
    }
    public function getCopyStatus(){
        return $this->status;
    }
    public function getCopyLocation(){
        return $this->location;
    }
    
}