<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

class BooleanValueObject
{
    protected bool $value;

    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value() ? 'true' : 'false';
    }

    public static function fromBoolean(bool $value): static
    {
        return new static($value);
    }

    public function value(): bool
    {
        return $this->value;
    }

    public function equals(BooleanValueObject $booleanValueObject): bool
    {
        return $this->value === $booleanValueObject->value;
    }

    public function isTrue(): bool
    {
        return true === $this->value;
    }

    public function isFalse(): bool
    {
        return false === $this->value;
    }
}
