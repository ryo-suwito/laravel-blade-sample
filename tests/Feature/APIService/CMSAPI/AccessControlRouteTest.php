<?php

namespace Tests\Feature\APIService\CMSAPI;

use App\Services\API;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AccessControlRouteTest extends TestCase
{
    protected $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAsStoreManagementUser();

        $this->service = API::instance('cms_api', 'access_control');
    }

    public function test_return_all_access_controls()
    {
        $res = $this->service->all();

        $this->assertEquals($res->status(), 200);
    }

    public function test_return_grouping_access_controls()
    {
        $res = $this->service->grouping();

        $this->assertEquals($res->status(), 200);
    }
}
