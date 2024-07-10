<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\GetAnimalService;
use App\Repositories\AnimalRepositoryInterface;

class GetAnimalServiceTest extends TestCase
{

    public function testGetAnimalData()
    {
        $mockRepository = $this->createMock(AnimalRepositoryInterface::class);

        $mockData = [
            ['id' => 1, 'name' => 'Cat'],
            ['id' => 2, 'name' => 'Dog'],
        ];

        $mockRepository->method('all')->willReturn($mockData);


        $service = new GetAnimalService($mockRepository);

        $result = $service->getAnimalData();

        $this->assertEquals($mockData, $result);
    }
}