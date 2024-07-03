<?php

namespace App\Domain\Basic;

class Address
{
    public string $personal;
    public string $address;
    public function __construct(string $address, string $personal)
    {
        $this->address = $address;
        $this->personal = $personal;
    }

    /**
     * @return string
     */
    public function getPersonal(): string
    {
        return $this->personal;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }
}
