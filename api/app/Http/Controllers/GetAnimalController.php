<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GetAnimalServiceInterface;
use App\Services\FileLoggerServiceInterface;


class GetAnimalController extends Controller
{
    //FileLoggerServiceInterfaceはコントローラー内で使いまわす想定
    protected $logger;

    public function __construct(FileLoggerServiceInterface $logger)
    {
        $this->logger = $logger;
    }

    //GetAnimalServiceInterfaceはコントローラー内で一度しか使わない想定
    public function getAllAminal(GetAnimalServiceInterface $getAnimal)
    {
        $allAnimals = $getAnimal->getAnimalData();
        $this->logger->info($allAnimals);
    }
}
