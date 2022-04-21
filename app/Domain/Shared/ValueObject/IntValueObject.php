<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

abstract class IntValueObject
{
    protected int $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return (string) $this->value();
    }

    public function value(): int
    {
        return $this->value;
    }
}
