<?php

namespace Tests\Feature\APIService\CoreAPI;

use App\Services\API;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class BeneficiaryRouteTest extends TestCase
{
    protected $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAsStoreManagementUser();

        $this->service = API::instance('core_api', 'beneficiary');
    }

    public function test_return_paginated_beneficiaries()
    {
        $res = $this->service->paginated();

        $this->assertEquals($res->status(), 200);
    }
}
