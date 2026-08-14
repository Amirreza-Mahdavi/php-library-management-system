<?php

namespace LMS\Repository;

use LMS\Domain\Role;

class RoleRepository {
    private string $file = __DIR__ . '/../../data/roles.json';

    private function mapToRole(): array {
        $roles = [];
        $data = json_decode(file_get_contents($this->file), true);

        foreach ($data as $role) {
            $roles[] = new Role(
                $role['role_id'],
                $role['role_name']
            );
        }
        return $roles;
    }
}