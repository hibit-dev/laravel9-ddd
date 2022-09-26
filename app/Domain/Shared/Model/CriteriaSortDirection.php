<?php

declare(strict_types=1);

namespace App\Domain\Shared\Model;

enum CriteriaSortDirection: string
{
    case ASC = 'ASC';
    case DESC = 'DESC';

    public function value(): string
    {
        return $this->value;
    }
}
