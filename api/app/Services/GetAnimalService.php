<?php

namespace App\Services;
use App\Services\GetAnimalServiceInterface;
use App\Repositories\AnimalRepositoryInterface;

class GetAnimalService implements GetAnimalServiceInterface
{
    protected $animalRepository;

    public function __construct(AnimalRepositoryInterface $animalRepository)
    {
        $this->animalRepository = $animalRepository;
    }

    public function getAnimalData()
    {
        return $this->animalRepository->all(['*']);
    }
}