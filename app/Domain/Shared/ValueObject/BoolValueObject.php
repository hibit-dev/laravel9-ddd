<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

abstract class BoolValueObject
{
    protected bool $value;

    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return (string) $this->value();
    }

    public function value(): bool
    {
        return $this->value;
    }
}
