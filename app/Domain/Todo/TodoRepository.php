<?php

namespace App\Domain\Todo;

use App\Domain\Todo\Aggregate\Todo;
use App\Domain\Todo\ValueObject\Id;

interface TodoRepository
{
    public function create(Todo $todo): void;

    public function update(Todo $todo): void;

       /**
     * @throws UserNotFoundException
     */
    public function findById(Id $userId): Todo;

    public function searchById(Id $userId): ?Todo;

    public function searchByCriteria(UserSearchCriteria $criteria): array;
    
    public function delete(Todo $todo): void;
}
