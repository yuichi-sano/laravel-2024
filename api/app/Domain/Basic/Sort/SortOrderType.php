<?php

namespace App\Domain\Basic\Sort;

class SortOrderType
{
    private ?int $value;
    public static $sortTypeValues = [
        0 => 'asc',
        1 => 'desc'
    ];

    public function __construct($value)
    {
        $this->value = $value;
    }

    public static function create(string $sortOrder)
    {
        foreach (self::$sortTypeValues as $key => $sortTypeValue) {
            if ($sortOrder == $sortTypeValue) {
                return new self($key);
            }
        }
    }

    public function isEmpty(): bool
    {
        return is_null($this->value);
    }

    public function orderBy(): string
    {
        return $this->getValue();
    }

    public function getValue(): string
    {
        foreach (self::$sortTypeValues as $key => $sortTypeValue) {
            if ($key === $this->value) {
                return $sortTypeValue;
            }
        }
        //TODO Exceptions
    }
}
