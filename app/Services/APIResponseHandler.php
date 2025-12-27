<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class APIResponseHandler
{
    protected $response;

    protected $successCallback;

    protected $failedCallback;

    protected $failedView;

    protected array $viewData;

    public function __construct(Response $response, $successCallback = null, $failedCallback = null)
    {
        $this->response = $response;
        $this->successCallback = $successCallback;
        $this->failedCallback = $failedCallback;
    }

    protected function defaultSuccessCallback()
    {
        if (request()->wantsJson()) {
            abort(response()->json($this->response->json()));
        }

        toast(
            'success',
            ! empty($this->response->json('status_message'))
                ? $this->response->json('status_message')
                : __('Successful!')
        );
    }

    protected function defaultFailedCallback()
    {
        $message = ! empty($this->response->json('status_message'))
            ? $this->response->json('status_message')
            : 'Oops! Something went wrong.';

        $this->createErrorLog();

        $callback = $this->response->status();

        if ($this->response->status() == 401) {
            session()->invalidate();
            session()->regenerateToken();

            if (request()->wantsJson()) {
                abort(response()->json($this->response->json(), 401));
            }

            $callback = redirect()->route(config('api_gateway.redirect.login'));
        } elseif ($this->response->status() == 422) {
            if (request()->wantsJson()) {
                abort(response()->json($this->response->json(), 422));
            }

            toast('error', $this->response->json('status_message') ?? 'Unprocessable Entity');

            $callback = back()->withInput()->withErrors($this->response->json('result'));
        }

        abort($callback, $message);
    }

    protected function createErrorLog()
    {
        $request = $this->response->transferStats->getRequest();

        Log::channel('api_gateway')->error(sprintf('%s %s %d',
            $request->getMethod(),
            $request->getURI(),
            $this->response->status(),
        ));
    }

    protected function onSuccess()
    {
        if ($this->successCallback === false) {
            return;
        }

        if (
            $this->successCallback !== null
            && is_callable($this->successCallback)
        ) {
            $callback = $this->successCallback;

            return $callback($this->response);
        }

        $this->defaultSuccessCallback();
    }

    protected function onFailed()
    {
        $this->createErrorLog();

        if ($this->failedView != null) {
            $data = $this->viewData ?? [
                'response' => $this->response,
            ];

            die(view($this->failedView, $data));
        }

        if ($this->failedCallback === false) {
            return;
        }

        if (
            $this->failedCallback !== null
            && is_callable($this->failedCallback)
        ) {
            $callback = $this->failedCallback;

            return $callback($this->response);
        }

        $this->defaultFailedCallback();
    }

    public function failedView($view = null)
    {
        $this->failedView = $view ?? 'errors.api-gateway';

        return $this;
    }

    public function with(array $data)
    {
        $this->viewData = $data;

        return $this;
    }

    public function __destruct()
    {
        if ($this->response->successful()) {
            return $this->onSuccess();
        }

        $this->onFailed();
    }
}
