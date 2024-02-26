<?php

namespace App\RoleManagement\User;

interface UserInterface
{
    public function findRoleById(int $id): false|array;
    public function updateUserRole(int $user_id, int $role): bool;

}