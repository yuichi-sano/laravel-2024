<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Company;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class CompanyTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_can_get_all_companies()
    {
        Company::factory()->count(5)->create();

        $response = $this->getJson('/api/companies');

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
    }

    public function test_can_get_single_company()
    {
        $company = Company::factory()->create();

        $response = $this->getJson('/api/companies/' . $company->id);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $company->id,
                'name' => $company->name,
                'industry' => $company->industry,
                'code' => $company->code,
                'isPartner' => $company->is_partner,
            ],
        ]);
    }

    public function test_can_create_company()
    {
        $companyData = [
            'name' => 'テスト企業',
            'industry' => 'テスト業種',
            'code' => 'TEST',
            'isPartner' => true,
        ];

        $response = $this->postJson('/api/companies/register', $companyData);

        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'name' => 'テスト企業',
                'industry' => 'テスト業種',
                'code' => 'TEST_' . now()->format('Ymd') . '_1',
                'isPartner' => true,
            ],
        ]);

        $this->assertDatabaseHas('companies', [
            'name' => 'テスト企業',
            'industry' => 'テスト業種',
            'code' => 'TEST_' . now()->format('Ymd') . '_1',
            'is_partner' => true,
        ]);
    }

    public function test_can_update_company()
    {
        $company = Company::factory()->create();

        $updateData = [
            'name' => 'テスト企業更新',
            'industry' => 'テスト業種更新',
            'isPartner' => false,
        ];

        $response = $this->putJson('/api/companies/' . $company->id . '/update', $updateData);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $company->id,
                'name' => 'テスト企業更新',
                'industry' => 'テスト業種更新',
                'code' => $company->code,
                'isPartner' => false,
            ],
        ]);

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => 'テスト企業更新',
            'industry' => 'テスト業種更新',
            'code' => $company->code,
            'is_partner' => false,
        ]);
    }

    public function test_can_delete_company()
    {
        $company = Company::factory()->create();

        $response = $this->deleteJson('/api/companies/' . $company->id . '/delete');

        $response->assertStatus(204);

        $this->assertDatabaseMissing('companies', [
            'id' => $company->id,
        ]);
    }

    public function test_can_sort_companies()
    {
        $companies = Company::factory()->count(5)->create();

        $response = $this->getJson('/api/companies/sort?sortField=name&sortOrder=asc');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'industry', 'code', 'isPartner', 'createdAt', 'updatedAt']
            ],
            'pageInfo' => ['page', 'limit', 'totalPage', 'totalCount']
        ]);

        $sortedCompanies = $companies->sortBy('name')->values();

        foreach ($sortedCompanies as $index => $company) {
            $this->assertEquals($company->name, $response->json('data')[$index]['name']);
        }
    }

    public function test_can_paginate_companies()
    {
        Company::factory()->count(20)->create();

        $response = $this->getJson('/api/companies/sort?page=2&perPage=5');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'industry', 'code', 'isPartner', 'createdAt', 'updatedAt']
            ],
            'pageInfo' => ['page', 'limit', 'totalPage', 'totalCount']
        ]);

        $this->assertCount(5, $response->json('data'));
        $this->assertEquals(2, $response->json('pageInfo.page'));
        $this->assertEquals(5, $response->json('pageInfo.limit'));
    }

    public function test_can_filter_companies()
    {
        Company::factory()->create(['name' => 'TestCompany1', 'industry' => 'TestIndustry1']);
        Company::factory()->create(['name' => 'TestCompany2', 'industry' => 'TestIndustry2']);
        Company::factory()->count(3)->create();

        $response = $this->getJson('/api/companies/sort?filters[name][]=TestCompany1,TestCompany2&filters[industry][]=TestIndustry1,TestIndustry2');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'industry', 'code', 'isPartner', 'createdAt', 'updatedAt']
            ],
            'pageInfo' => ['page', 'limit', 'totalPage', 'totalCount']
        ]);

        $this->assertCount(2, $response->json('data'));
        foreach ($response->json('data') as $company) {
            $this->assertContains($company['name'], ['TestCompany1', 'TestCompany2']);
            $this->assertContains($company['industry'], ['TestIndustry1', 'TestIndustry2']);
        }
    }
}
