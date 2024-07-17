<?php

namespace App\Repositories\Eloquent;

use App\Models\Result;
use App\Repositories\ResultRepositoryInterface;
use App\Repositories\Eloquent\EloquentRepository;
use Illuminate\pagination\LengthAwarePaginator;

class ResultRepository extends EloquentRepository implements ResultRepositoryInterface
{
    public $result;

    public function _construct(Result $result)
    {
        $this->result = $result;
    }
}