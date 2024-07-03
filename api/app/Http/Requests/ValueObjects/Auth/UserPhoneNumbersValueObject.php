<?php

namespace App\Http\Requests\ValueObjects\Auth;

use App\Http\Requests\ValueObjects\AbstractValueObject;

class UserPhoneNumbersValueObject extends AbstractValueObject
{
    
    // 
    protected string $type;
       

    // 
    protected string $number;
       

    
    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }
    

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }
    
}