<?php

namespace App\Repositories\Eloquent;

use App\Models\Position;
use App\Repositories\PositionRepositoryInterface;
use App\Repositories\Eloquent\EloquentRepository;
use Illuminate\pagination\LengthAwarePaginator;

class PositionRepository extends EloquentRepository implements PositionRepositoryInterface
{
    public $position;

    public function __construct(Position $position)
    {
        $this->position = $position;
    }
}