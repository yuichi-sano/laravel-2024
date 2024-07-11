<?php

namespace App\Repositories\Eloquent;

use App\Models\Result;
use App\Repositories\ResultRepositoryInterface;
use Illuminate\pagination\LengthAwarePaginator;

class ResultRepository implements ResultRepositoryInterface
{
    protected $result;

    public function _construct(Result $result)
    {
        $this->result = $result;
    }

    public function all(array $columns)
    {
        return $this->result->all($columns);
    }

    public function create(array $data)
    {
        return $this->result->create($data);
    }

    public function update(array $whereClause, array $data)
    {
        return $this->result->where($whereClause)->update($data);
    }
}