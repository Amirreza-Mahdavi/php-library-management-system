<?php

class Book {

    public function __construct(
        private readonly int $bookId,
        private readonly string $title,
        private readonly string $author,
        private readonly int $categoryId,
        private readonly string $language
    ){}

    public function getBookId(){
        return $this->bookId;
    }
    public function getTitle(){
        return $this->title;
    }
    public function getAuthor(){
        return $this->author;
    }
    public function getCategoryId(){
        return $this->categoryId;
    }
    public function getLanguage(){
        return $this->language;
    }

}