<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator as LaravelPaginator;

class Paginator
{
    public static function fromResponse(Response $response, array $options = [])
    {
        LaravelPaginator::defaultView('pagination::bootstrap-4-flat');

        $paginator = is_null($response->json('meta')) ? $response->json('result') : $response->json('meta');
        $items = is_null($response->json('meta')) ? $paginator['data'] : $response->json('result');

        $paginator = new LengthAwarePaginator(
            $items,
            $paginator['total'],
            $paginator['per_page'],
            $paginator['current_page'],
            $options
        );

        $paginator->setPath('');
        $paginator->withQueryString();

        return $paginator;
    }
}
