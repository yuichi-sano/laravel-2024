<?php

namespace App\Http\Requests\ValueObjects\Auth;

use App\Http\Requests\ValueObjects\AbstractValueObject;

class UserAddressValueObject extends AbstractValueObject
{
    
    // 
    protected string $street;
       

    // 
    protected string $city;
       

    // 
    protected string $state;
       

    // 
    protected string $postalCode;
       

    
    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }
    

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }
    

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }
    

    /**
     * @return mixed
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }
    
}