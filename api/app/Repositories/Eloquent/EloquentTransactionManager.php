<?php

namespace App\Repositories\Eloquent;

/**
 * リポジトリとして一般的と見られるトランザクション管理関数のみをEntityManagerから実施します。
 * @NOTE 悲観ロックなどはこのManagerは通さず、各repositoryにて実施ください。($this->lock($this->getEntity,)
 */
class EloquentTransactionManager implements \App\Services\Helper\TransactionManagerInterface
{
    public static function startTransaction(): void
    {
        \DB::beginTransaction();
    }

    public static function commit(): void
    {
        \DB::commit();
    }

    public static function getNestingLevel(): string
    {
        return \DB::connection(\DB::getDefaultConnection())->transactionLevel();
    }

    public static function rollback(): void
    {
        \DB::rollBack();
    }

    public static function wrapInTransaction(callable $func): void
    {
        \DB::transaction($func);
    }

    public static function close(): void
    {
        \DB::disconnect();
    }


    /**
     * コネクションを返却します。
     * @return Connection
     */
    public static function getConnection(): Connection
    {
        return \DB::connection();
    }


    public static function reConnect(): void
    {
        \DB::reconnect();
    }

}
