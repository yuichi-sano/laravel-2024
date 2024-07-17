<?php

namespace App\Repositories\Eloquent;

use App\Models\Total;
use App\Repositories\TotalRepositoryInterface;
use App\Repositories\Eloquent\EloquentRepository;
use Illuminate\pagination\LengthAwarePaginator;

class TotalRepository extends EloquentRepository implements TotalRepositoryInterface
{
    public $total;

    public function _construct(Total $total)
    {
        $this->total = $total;
    }
}