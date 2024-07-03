<?php

namespace App\Http\Requests\ValueObjects\Auth;

use App\Http\Requests\ValueObjects\AbstractValueObject;

class UserValueObject extends AbstractValueObject
{
    
    // 
    protected string $firstName;
       

    // 
    protected string $lastName;
       

    // 
    protected int $age;
       

    // 
    protected bool $isActive;
       

    // 
    protected UserAddressValueObject $address;
       

    // 
    protected UserPhoneNumbersValueObject $phoneNumbers;
       

    
    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }
    

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }
    

    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->age;
    }
    

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }
    

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }
    

    /**
     * @return mixed
     */
    public function getPhoneNumbers()
    {
        return $this->phoneNumbers;
    }
    
}