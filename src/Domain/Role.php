<?php

namespace LMS\Domain;

use LMS\Enums\RoleName;

class Role {

    public function __construct(
        private readonly int $roleId,
        private readonly RoleName $roleName
    ){}

    public function getRoleId(){
        return $this->roleId;
    }
    public function getRoleName(){
        return $this->roleName;
    }

}