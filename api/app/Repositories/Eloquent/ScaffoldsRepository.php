<?php

namespace App\Repositories\Eloquent;

use App\Models\Scaffold;
use App\Repositories\ScaffoldRepositoryInterface;
use Illuminate\pagination\LengthAwarePaginator;

class ScaffoldRepository implements ScaffoldRepositoryInterface
{
    protected $scaffold;

    public function __construct(Scaffold $scaffold)
    {
        $this->scaffold = $scaffold;
    }

    public function all(array $columns)
    {
        return $this->scaffold->all($columns);
    }
}