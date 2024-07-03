<?php

namespace App\Domain\Basic\Page;

class Page
{
    private ?int $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public static function create(string $value): self
    {
        return new self((int)$value);
    }

    public function isEmpty(): bool
    {
        return is_null($this->value);
    }

    public function isGreaterThanZero(): bool
    {
        return $this->value > 0;
    }

    public function isFirst(): bool
    {
        return $this->value === 1;
    }

    public function next(): Page
    {
        return new Page($this->value + 1);
    }

    public function previous(): Page
    {
        return new Page($this->value - 1);
    }

    public function toInteger(): int
    {
        return $this->value;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }
}