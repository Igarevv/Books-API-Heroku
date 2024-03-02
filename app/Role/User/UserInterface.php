<?php

namespace App\Role\User;

interface UserInterface
{
    public function findRoleById(int $id): false|array;
    public function updateUserRole(int $user_id, int $role): bool;

}