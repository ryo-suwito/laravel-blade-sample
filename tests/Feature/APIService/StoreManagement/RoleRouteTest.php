<?php

namespace Tests\Feature\APIService\StoreManagement;

use App\Services\API;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class RoleRouteTest extends TestCase
{
    protected $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAsStoreManagementUser();

        $this->service = API::instance('store_management', 'role');
    }

    public function test_return_paginated_roles()
    {
        $res = $this->service->paginated();

        $this->assertEquals($res->status(), 200);
    }

    public function test_return_found_role()
    {
        $res = $this->service->find(1);

        $this->assertEquals($res->status(), 200);
    }

    public function test_return_role_target_types()
    {
        $res = $this->service->getTargetTypes();

        $this->assertEquals($res->status(), 200);
    }

    public function test_return_invalid_data_on_create_role()
    {
        $res = $this->service->create([]);

        $this->assertEquals($res->status(), 422);
    }

    public function test_return_invalid_data_on_update_role()
    {
        $res = $this->service->update(1, []);

        $this->assertEquals($res->status(), 422);
    }
}
