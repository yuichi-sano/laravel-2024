<?php

namespace App\Supports;

use Illuminate\Support\Facades\Facade;
use App\Services\Helper\TransactionManagerInterface;

/**
 * @method static TransactionManagerInterface startTransaction()
 * @method static TransactionManagerInterface commit()
 * @method static TransactionManagerInterface getNestingLevel()
 * @method static TransactionManagerInterface rollback()
 * @method static TransactionManagerInterface getConnection()
 * @method static TransactionManagerInterface reConnect()
 */
class TransactionManager extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TransactionManagerInterface::class;
    }
}
