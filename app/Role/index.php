<?php

use App\Core\Container\Container;
use App\Role\Exception\RoleManagementException;
use App\Role\User\Role;
use App\Role\User\User;

define('APP_PATH', realpath(dirname(__DIR__, 2)));

require APP_PATH . '/vendor/autoload.php';


$container = new Container();

$user = new User($container);

try{
    $userId = isset($argv[1]) && is_numeric($argv[1]) ? $argv[1] : 0;
    $role = $argv[2] ?? '';

    (new Role($user))->changeRole($userId, $role);

    echo 'Role was successfully changed. User must re-login to apply changes.' . PHP_EOL;
} catch(RoleManagementException $e){
    echo $e->getMessage() . PHP_EOL;
}