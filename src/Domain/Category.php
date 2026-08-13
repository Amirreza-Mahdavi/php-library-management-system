<?php

class Category {
    
    public function __construct(
        private readonly int $categoryId,
        private readonly string $categoryName
    ){}

    public function getCategoryId(){
        return $this->categoryId;
    }
    public function getCategoryName(){
        return $this->categoryName;
    }
}