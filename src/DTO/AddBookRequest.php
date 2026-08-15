<?php

namespace LMS\DTO;

class AddBookRequest {

    public function __construct(
        public string $title,
        public string $author,
        public int $categoryId,
        public string $language 
    ){}
    
}