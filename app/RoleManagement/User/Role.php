<?php

namespace App\RoleManagement\User;

use App\RoleManagement\Exception\RoleManagementException;

class Role
{

    protected UserInterface $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function changeRole(int $userId, string $role): void
    {
        if ( ! $role && ! $userId) {
            throw new RoleManagementException('User id and new role is required!');
        }
        $binRole = $role === 'admin' ? 1 : 0;

        $currentRole = $this->user->findRoleById($userId);
        if (! $currentRole) {
            throw new RoleManagementException('User not found');
        }

        if ($currentRole['is_admin'] === $binRole) {
            $errorMessage = $binRole === 1 ? 'User is already an admin' : 'User is already a default user';
            throw new RoleManagementException($errorMessage);
        }

        $this->user->updateUserRole($userId, $binRole);
    }

}