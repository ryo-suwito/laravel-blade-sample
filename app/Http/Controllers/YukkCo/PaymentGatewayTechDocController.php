<?php

namespace App\Http\Controllers\YukkCo;

use App\Helpers\H;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PaymentGatewayTechDocController extends Controller
{
    public function index(Request $request)
    {
        return view('yukk_co.tech_docs.index');
    }

    public function store(Request $request)
    {
        if (config('app.env') == 'sandbox') {
            H::flashFailed('You cant upload tech docs from this environment, plase go to production environment', true);
            return back();
        }

        $file = $request->file('file');
        $file->storeAs('public/tech-docs', $file->getClientOriginalName());
        //TODO Versioning file

        H::flashSuccess('Successfully uploading file', true);
        return back();
    }

    public function show(Request $request)
    {
        $allFiles = Storage::files('public/tech-docs');

        rsort($allFiles, SORT_DESC);

        $file = data_get($allFiles, 0);

        if ($file == null) {
            H::flashFailed('Tech docs has not been uploaded yet', true);
            return back();
        }

        return Storage::download($file);
    }

    public function latestFile(Request $request)
    {
        $allFiles = Storage::files('public/tech-docs');

        rsort($allFiles, SORT_DESC);

        return encrypt(data_get($allFiles, 0));
    }

    public function getFileToken()
    {
        $token = encrypt([
            'expired_at' => now()->addSeconds(60)
        ]);

        $files = collect(Storage::files('tech-docs/payment-gateway'));
        $filename = basename($files->last());

        return response()->json(['data' => compact('filename','token')]);
    }

    public function downloadFile($token)
    {
        $data = decrypt($token);

        if (now() > $data['expired_at']) {
            abort(404);
        }

        $files = collect(Storage::files('tech-docs/payment-gateway'));

        return Storage::download($files->last());
    }
}
