<?php

namespace Tests\Feature\APIService\StoreManagement;

use App\Services\API;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UserRouteTest extends TestCase
{
    protected $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAsStoreManagementUser();

        $this->service = API::instance('store_management', 'user');
    }

    public function test_return_paginated_users()
    {
        $res = $this->service->paginated();

        $this->assertEquals($res->status(), 200);
    }

    public function test_return_found_user()
    {
        $res = $this->service->find(1);

        $this->assertEquals($res->status(), 200);
    }

    public function test_return_success_sync_roles()
    {
        $res = $this->service->syncRoles(1, [
            [
                'role_id' => 1,
                'target_type' => 'YUKK_CO',
                'target_id' => null,
            ],
        ]);

        $this->assertEquals($res->status(), 200);
    }

    public function test_return_user_profile()
    {
        $res = $this->service->profile();

        $this->assertEquals($res->status(), 200);
    }

    public function test_return_invalid_data_on_create_user()
    {
        $res = $this->service->create([]);

        $this->assertEquals($res->status(), 422);
    }

    public function test_return_invalid_data_on_update_user()
    {
        $res = $this->service->update(1, []);

        $this->assertEquals($res->status(), 422);
    }

    public function test_return_invalid_data_on_import_users()
    {
        $res = $this->service->bulkPreview(
            UploadedFile::fake()->createWithContent(
                'test.csv', implode("\n", [])
            )
        );

        $this->assertEquals($res->status(), 422);
    }

    public function test_return_invalid_data_on_bulk_create()
    {
        $res = $this->service->bulkCreate([]);

        $this->assertEquals($res->status(), 422);
    }

    public function test_return_invalid_data_on_sync_roles()
    {
        $res = $this->service->syncRoles(1, [
            [
                'role_id' => 1,
                'target_type' => 'YUKK_CO',
                'target_id' => null,
            ],
        ]);

        $this->assertEquals($res->status(), 200);
    }

    public function test_return_invalid_data_on_reset_password()
    {
        $res = $this->service->resetPassword(1, []);

        $this->assertEquals($res->status(), 422);
    }

    public function test_return_invalid_on_change_password()
    {
        $res = $this->service->changePassword([]);

        $this->assertEquals($res->status(), 422);
    }
}
