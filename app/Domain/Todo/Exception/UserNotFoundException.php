<?php

declare(strict_types=1);

namespace App\Domain\Todo\Exception;

use App\Domain\Shared\Exception\NotFoundException;

class UserNotFoundException extends NotFoundException
{
}
