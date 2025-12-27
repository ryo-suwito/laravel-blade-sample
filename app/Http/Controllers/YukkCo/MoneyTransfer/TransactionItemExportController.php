<?php

namespace App\Http\Controllers\YukkCo\MoneyTransfer;

use App\Actions\MoneyTransfer\Transaction\Filter;
use App\Http\Controllers\Controller;
use App\Services\MoneyTransfer\TransactionService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class TransactionItemExportController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new TransactionService();
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        try {
            $filters = new Filter($request);

            $response = $this->service->paginated($filters->values());

            $contents = base64_decode($response->json('result.base64_contents'));

            $filename = $response->json('result.filename');

            Storage::put("tmp/$filename", $contents);

            return response()->download(Storage::path("tmp/$filename"))->deleteFileAfterSend();
        } catch(Throwable $e) {
            if ($e instanceof RequestException) {
                if ($e->response->status() == 401) {
                    $this->logout();
                }
            }

            Log::error($e, [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);

            toast('error', 'There is something wrong with our server. Please try again later.');
            return redirect('dashboard');
        }
    }
}
