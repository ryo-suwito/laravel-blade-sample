<?php

namespace App\Actions\MoneyTransfer\Transaction;

use Illuminate\Http\Request;

class Filter
{
  protected $counter = 0;

  protected $filters = [];

  public function __construct(Request $request, array $options = [])
  {
    $this->filters = [
      'export' => $request->get('export', 0),
      'status' => $request->get('status', 'all'),
      'dates_by' => $request->get('dates_by', 'created_at'),
      'start_date' => $request->get('start_date', null),
      'end_date' => $request->get('end_date', null),
      'page' => $request->get('page', 1),
      'search' => $request->get('search', null),
      'search_by' => $request->get('search_by', 'code'),
      'per_page' => $request->get('per_page', 10),
      'providers' => $request->get('providers', []),
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
        ->only('start_date', 'tags', 'providers')
        ->filter(fn ($value) => ! empty($value))
        ->count();
  }
}
