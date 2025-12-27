<?php

namespace App\Repositories\StoreManagement;

use App\Services\APIService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class UserRepository extends APIService {
    public function paginated(array $params = [])
    {
        return $this->client()->get('store/users', $params);
    }

    public function find($id)
    {
        return $this->client()->segment([
            'id' => 3,
        ])->get('store/users/' . $id . '/find');
    }

    public function create(array $body)
    {
        return $this->client()->post('store/users/create', $body);
    }

    public function update($id, array $body)
    {
        return $this->client()->segment([
            'id' => 3,
        ])->put('store/users/' . $id . '/update', $body);
    }

    public function syncRoles($id, array $body)
    {
        return $this->client()->segment([
            'id' => 3,
        ])->post('store/users/' . $id . '/roles/sync', $body);
    }

    public function bulkPreview(UploadedFile $file)
    {
        return $this->client()->attach('file', $file->get(), 'file.csv')->post('store/users/bulk/preview');
    }

    public function bulkCreate(array $body)
    {
        return $this->client()->post('store/users/bulk', $body);
    }

    public function resetPassword($id, array $body)
    {
        return $this->client()->segment([
            'id' => 3,
        ])->post('store/users/' . $id . '/reset-password', $body);
    }

    public function profile()
    {
        return $this->client()->get('store/my-profile');
    }

    public function changePassword(array $body)
    {
        return $this->client()->post('store/change-password', $body);
    }

    public function updateProfile(array $params = [])
    {
        return $this->client()->put('store/my-profile/update', $params);
    }

    public function toggleActive($id, array $params = []) {
        return $this->client()->segment([
            'id' => 3
        ])->put('store/users/'. $id. '/active/toggle', $params);
    }

    public function productionCheck($username) {
        $response = $this->client()->post('store/auth/catch/environment', [
            "username" => $username,
            "production_check" => 1
        ]);

        if (!$response->ok()) {
            Log::error('PRODUCTION CHECK', $response->json() ?? []);
            abort(403);
        }

        session()->put('jwt_token', $response->json('result.jwt_token'));

        $response = $this->client()->post('store/users/has-roles', [
            'username' => $username
        ]);

        session()->forget('jwt_token');

        return $response;
    }
}
