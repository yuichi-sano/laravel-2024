<?php

namespace App\Repositories\Eloquent;

use App\Models\Scaffold;
use App\Repositories\ScaffoldRepositoryInterface;
use App\Repositories\Eloquent\EloquentRepository;
use Illuminate\pagination\LengthAwarePaginator;

class ScaffoldRepository extends EloquentRepository implements ScaffoldRepositoryInterface
{
    public $scaffold;

    public function __construct(Scaffold $scaffold)
    {
        $this->scaffold = $scaffold;
    }
}