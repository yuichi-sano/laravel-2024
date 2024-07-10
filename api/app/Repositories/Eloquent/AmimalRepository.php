<?php

namespace App\Repositories\Eloquent;

//架空のAmimalモデルをインポート
use App\Models\Aminal;
use App\Repositories\AnimalRepositoryInterface;

class AmimalRepository extends EloquentRepository implements AnimalRepositoryInterface
{
    //モデルだけ架空のAnimalモデルにオーバーライド
    public function __construct(Animal $model)
    {
        $this->model = $model;
    }
}