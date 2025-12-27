<?php

namespace App\Actions\MoneyTransfer\TransactionBatch;

use Illuminate\Http\Request;

class Filter
{
  protected $counter = 0;

  protected $filters = [];

  protected Request $request;

  public function __construct(Request $request)
  {
    $this->request = $request;

    $this->filters = [
      'status' => $request->get('status', []),
      'dates_by' => $request->get('dates_by', 'created_at'),
      'start_date' => $request->get('start_date', null),
      'end_date' => $request->get('end_date', null),
      'page' => $request->get('page', 1),
      'search' => $request->get('search', null),
      'search_by' => $request->get('search_by', 'code'),
      'per_page' => $request->get('per_page', 10),
      'tags' => $request->get('tags', []),
    ];
  }

  public function values(): array
  {
    return $this->filters;
  }

  public function counter()
  {
    return collect($this->filters)
        ->only('status', 'start_date', 'tags')
        ->filter(fn ($value) => ! empty($value))
        ->count();
  }
}
