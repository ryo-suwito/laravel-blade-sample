<?php

namespace App\Actions\MoneyTransfer\TransactionGroup;

use Illuminate\Http\Request;

class ItemsFilter
{
  protected $counter = 0;

  protected $filters = [];

  protected Request $request;

  public function __construct(Request $request)
  {
    $this->request = $request;

    $this->filters = [
      'status' => $request->get('status', []),
      'page' => $request->get('page', 1),
      'search' => $request->get('search', null),
      'search_by' => $request->get('search_by', 'code'),
      'per_page' => $request->get('per_page', 10),
      'export' => $request->get('export', 0),
    ];
  }

  public function values(): array
  {
    return $this->filters;
  }

  public function counter()
  {
    return collect($this->filters)
        ->only('status')
        ->filter(fn ($value) => ! empty($value))
        ->count();
  }
}
