<?php

namespace App\Http\Controllers\Partners;

use App\Helpers\H;
use App\Helpers\S;
use App\Http\Controllers\Controller;
use App\Services\APIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PaymentGatewayCredentialController extends Controller
{
    public function index(Request $request)
    {
        $partnerId = S::getTargetId();

        $response = (new APIService)->client()->get('pg-core-service/credentials', [
            'partner_id' => $partnerId
        ]);

        return view('partners.pg_credentials.index', [
            'clientId' => $response->json('result.client.id'),
            'clientSecret' => $response->json('result.client.secret'),
            'mids' => $response->json('result.mids'),
        ]);
    }

    public function techDocs()
    {
        if (isProductionMode()) {
            $files = collect(Storage::files('tech-docs/payment-gateway'));

            return Storage::download($files->sort()->last());
        }

        $response = Http::get(
            config('services.app.production.url') . '/partner/tech-docs/token'
        );

        $fileName = $response->json('data.filename');
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);

        copy(config('services.app.production.url') . '/partner/tech-docs/file/' . $response->json('data.token'), $tempFile);

        return response()->download($tempFile, $fileName);
    }
}
