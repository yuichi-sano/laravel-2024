<?php

namespace App\Repositories\Eloquent;

use App\Models\Project;
use App\Repositories\ProjectRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectRepository implements ProjectRepositoryInterface
{
    protected $project;

    public function __construct(Project $project)
    {
        $this->project = $project
    }

    public function all(array $columns)
    {
        return $this->project->all($columns);
    }

    public function create(array $data)
    {
        return $this->project->create($data);
    }

    public function update(array $whereClause, array $data)
    {
        return $this->project->where($whereClause)->update($data);
    }
}