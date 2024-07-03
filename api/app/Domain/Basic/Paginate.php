<?php

namespace App\Domain\Basic;

class Paginate
{
    protected $page;
    protected $perPage;

    public function __construct(int $page, int $perPage)
    {
        $this->page = $page;
        $this->perPage = $perPage;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }
}
