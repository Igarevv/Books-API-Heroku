<?php

namespace App\Http\Exceptions;

use Exception;

use docker\vendor\psr\container\src\ContainerExceptionInterface;
class ContainerException extends Exception implements ContainerExceptionInterface
{

}