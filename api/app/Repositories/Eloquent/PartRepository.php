<?php

namespace App\Repositories\Eloquent;

use App\Models\Part;
use App\Repositories\PartRepositoryInterface;
use App\Repositories\Eloquent\EloquentRepository;
use Illuminate\pagination\LengthAwarePaginator;

class PartRepository extends EloquentRepository implements PartRepositoryInterface
{
    public $part;

    public function __construct(Part $part)
    {
        $this->part = $part;
    }
}