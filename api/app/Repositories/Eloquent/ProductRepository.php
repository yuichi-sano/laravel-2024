<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\Eloquent\EloquentRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository extends EloquentRepository implements ProductRepositoryInterface
{
    public $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }
}