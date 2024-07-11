<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function all(array $columns)
    {
        return $this->product->all($columns);
    }

    public function create(array $data)
    {
        return $this->product->create($data);
    }

    public function update(array $whereClause, array $data)
    {
        return $this->product->where($whereClause)->update($data);
    }
}