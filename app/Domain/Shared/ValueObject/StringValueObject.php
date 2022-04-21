<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

abstract class StringValueObject
{
    protected string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return $this->value();
    }

    public function value(): string
    {
        return $this->value;
    }

    public function empty(): bool
    {
        return empty($this->value());
    }
}
