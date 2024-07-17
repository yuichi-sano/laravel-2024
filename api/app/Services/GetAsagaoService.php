<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\Eloquent\ProductRepository;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class GetAsagaoService implements GetAsagaoServiceInterface
{
    public $productRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository
    )
    {
        $this->productRepository = $productRepository;
    }

    public function getAsagao()
    {
        $asagaos = $productRepository->all();
        return $asagaos;
    }
}