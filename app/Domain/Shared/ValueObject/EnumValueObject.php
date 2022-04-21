<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use Exception;

abstract class EnumValueObject extends StringValueObject
{
    const AVAILABLE_VALUES = [];

    protected string $value;

    public function __construct(string $value)
    {
        if (!in_array($value, static::AVAILABLE_VALUES)) {
            throw new Exception('Not found value in ENUM');
        }

        parent::__construct($value);
    }

    public function __toString()
    {
        return $this->value();
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $otherEnum): bool
    {
        return $this->value() === $otherEnum->value();
    }

    public function notEquals(self $otherEnum): bool
    {
        return !$this->equals($otherEnum);
    }
}
