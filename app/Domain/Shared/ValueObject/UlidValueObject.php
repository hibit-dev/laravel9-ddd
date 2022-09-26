<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use App\Domain\Shared\Exception\InvalidArgumentException;
use Stringable;
use Symfony\Component\Uid\Ulid;

class UlidValueObject implements Stringable, UlidInterface
{
    private string $value;

    final public function __construct(string $value)
    {
        $this->guard($value);

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private function guard(string $value): void
    {
        if (false === Ulid::isValid($value)) {
            throw new InvalidArgumentException(sprintf('Value <%s> is not a valid ULID', $value));
        }
    }

    public static function random(): static
    {
        return new static((new Ulid())->__toString());
    }

    public static function fromPrimitives(string $value): static
    {
        return new static($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(UlidInterface $other): bool
    {
        return $this->value() === $other->value();
    }
}
