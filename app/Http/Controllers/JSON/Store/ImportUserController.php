<?php

namespace App\Http\Controllers\JSON\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\User\ImportRequest;

class ImportUserController extends Controller
{
    protected $users;

    public function __construct()
    {
        $this->users = api('store_management', 'user');
    }

    public function import(ImportRequest $request)
    {
        $users = $request->get('users');

        $this->mappingUserRoles($users);

        $response = $this->users->bulkCreate([
            'users' => $users,
        ]);

        apiResponseHandler($response);
    }

    public function mappingUserRoles(&$users)
    {
        foreach ($users as $i => $user) {
            $roles = [];

            foreach ($user['roles'] as $role) {
                if (!isset ($role['targets'])) {
                    $roles[] = [
                        'id' => (int) $role['id'],
                        'target_type' => $role['target_type'],
                        'target_id' => null,
                    ];

                    continue;
                }

                foreach ($role['targets'] as $target) {
                    $roles[] = [
                        'id' => (int) $role['id'],
                        'target_type' => $role['target_type'],
                        'target_id' => (int) $target['id'],
                    ];
                }
            }

            $users[$i]['roles'] = $roles;
        }
    }
}
