<?php

namespace App\Domain\Todo;

use App\Domain\Todo\Aggregate\Todo;
use App\Domain\Todo\ValueObject\Id;

interface TodoRepository
{
    public function create(Todo $todo): void;

    public function update(Todo $todo): void;

       /**
     * @throws TodoNotFoundException
     */
    public function findById(Id $id): Todo;

    public function delete(Todo $todo): void;
}
