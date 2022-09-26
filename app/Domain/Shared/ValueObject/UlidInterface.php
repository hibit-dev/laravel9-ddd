<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

interface UlidInterface
{
    public function __toString(): string;

    public static function random(): static;

    public function value(): string;

    public function equals(UlidInterface $other): bool;

    public static function fromPrimitives(string $value): static;
}
