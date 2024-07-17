<?php

namespace App\Repositories\Eloquent;

use App\Models\Project;
use App\Repositories\ProjectRepositoryInterface;
use App\Repositories\Eloquent\EloquentRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectRepository extends EloquentRepository implements ProjectRepositoryInterface
{
    public $project;

    public function __construct(Project $project)
    {
        $this->project = $project
    }
}