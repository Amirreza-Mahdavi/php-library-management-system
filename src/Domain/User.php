<?php

class User {

    public function __construct(
        private readonly int $userId,
        private string $name,
        private readonly string $email,
        private readonly int $roleId,
        private string $password
    ){}

    public function getUserId(){
        return $this->userId;
    }
    public function getUserName(){
        return $this->name;
    }
    public function getUserEmail(){
        return $this->email;
    }
    public function getUserRoleId(){
        return $this->roleId;
    }
    public function getUserPassword(){
        return $this->password;
    }

}