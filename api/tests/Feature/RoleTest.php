<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Role;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class RoleTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_can_get_all_roles()
    {
        Role::factory()->count(5)->create();

        $response = $this->getJson('/api/roles');

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
    }

    public function test_can_get_single_role()
    {
        $role = Role::factory()->create();

        $response = $this->getJson('/api/roles/' . $role->id);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $role->id,
                'name' => $role->name,
                'description' => $role->description,
            ],
        ]);
    }

    public function test_can_create_role()
    {
        $roleData = [
            'name' => 'テスト役割',
            'description' => 'テスト説明',
        ];

        $response = $this->postJson('/api/roles/register', $roleData);

        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'name' => 'テスト役割',
                'description' => 'テスト説明',
            ],
        ]);

        $this->assertDatabaseHas('roles', [
            'name' => 'テスト役割',
            'description' => 'テスト説明',
        ]);
    }

    public function test_can_update_role()
    {
        $role = Role::factory()->create();

        $updateData = [
            'name' => 'テスト役割更新',
            'description' => 'テスト説明更新',
        ];

        $response = $this->putJson('/api/roles/' . $role->id . '/update', $updateData);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $role->id,
                'name' => 'テスト役割更新',
                'description' => 'テスト説明更新',
            ],
        ]);

        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => 'テスト役割更新',
            'description' => 'テスト説明更新',
        ]);
    }

    public function test_can_delete_role()
    {
        $role = Role::factory()->create();

        $response = $this->deleteJson('/api/roles/' . $role->id . '/delete');

        $response->assertStatus(204);

        $this->assertDatabaseMissing('roles', [
            'id' => $role->id,
        ]);
    }

    public function test_can_sort_roles()
    {
        $roles = Role::factory()->count(5)->create();

        $response = $this->getJson('/api/roles/sort?sortField=name&sortOrder=asc');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'description', 'createdAt', 'updatedAt']
            ],
            'pageInfo' => ['page', 'limit', 'totalPage', 'totalCount']
        ]);

        $sortedRoles = $roles->sortBy('name')->values();

        foreach ($sortedRoles as $index => $role) {
            $this->assertEquals($role->name, $response->json('data')[$index]['name']);
        }
    }

    public function test_can_paginate_roles()
    {
        Role::factory()->count(20)->create();

        $response = $this->getJson('/api/roles/sort?page=2&perPage=5');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'description', 'createdAt', 'updatedAt']
            ],
            'pageInfo' => ['page', 'limit', 'totalPage', 'totalCount']
        ]);

        $this->assertCount(5, $response->json('data'));
        $this->assertEquals(2, $response->json('pageInfo.page'));
        $this->assertEquals(5, $response->json('pageInfo.limit'));
    }

    public function test_can_filter_roles()
    {
        Role::factory()->create(['name' => 'TestRole1', 'description' => 'TestDescription1']);
        Role::factory()->create(['name' => 'TestRole2', 'description' => 'TestDescription2']);
        Role::factory()->count(3)->create();

        $response = $this->getJson('/api/roles/sort?filters[name][]=TestRole1,TestRole2&filters[description][]=TestDescription1,TestDescription2');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'description', 'createdAt', 'updatedAt']
            ],
            'pageInfo' => ['page', 'limit', 'totalPage', 'totalCount']
        ]);

        $this->assertCount(2, $response->json('data'));
        foreach ($response->json('data') as $role) {
            $this->assertContains($role['name'], ['TestRole1', 'TestRole2']);
            $this->assertContains($role['description'], ['TestDescription1', 'TestDescription2']);
        }
    }
}
