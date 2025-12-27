<?php

namespace App\Services\StoreManagement;

use App\Repositories\StoreManagement\UserSettingRepository;
use App\Services\APIService;
use Exception;
use Illuminate\Http\UploadedFile;

class UserSettingService
{
    protected $response;

    private $repository;

    public function __construct()
    {
        $this->repository = new UserSettingRepository();
    }

    public function __call($name, $arguments)
    {
        if (!method_exists($this->repository, $name)) {
            throw new Exception("Method does not exists on ".get_class($this->repository));
        }

        $this->response = $this->repository->{$name}(...$arguments);

        return $this->response;
    }
}
