<?php

namespace LMS\Repository;

use LMS\Domain\Role;

class RoleRepository {
    private string $file = __DIR__ . '/../../data/roles.json';

    private function mapToRole(array $role): Role {
        return new Role(
            $role['role_id'],
            $role['role_name']
        );
    }
}