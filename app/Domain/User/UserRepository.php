<?php

namespace App\Domain\User;

use App\Domain\User\Aggregate\User;
use App\Domain\User\ValueObject\Id;
use App\Domain\User\Exception\UserNotFoundException;

interface UserRepository
{
    public function create(User $user): void;

    public function update(User $user): void;

    /**
     * @throws UserNotFoundException
     */
    public function findById(Id $userId): User;

    public function searchById(Id $userId): ?User;

    public function searchByCriteria(UserSearchCriteria $criteria): array;

    public function delete(User $user): void;
}
