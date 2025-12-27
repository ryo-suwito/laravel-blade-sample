<?php

namespace Tests\Feature\APIService\CoreAPI;

use App\Services\API;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PartnerRouteTest extends TestCase
{
    protected $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAsStoreManagementUser();

        $this->service = API::instance('core_api', 'partner');
    }

    public function test_return_paginated_partners()
    {
        $res = $this->service->paginated();

        $this->assertEquals($res->status(), 200);
    }
}
