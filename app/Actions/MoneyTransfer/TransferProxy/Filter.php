<?php

namespace App\Actions\MoneyTransfer\TransferProxy;

use Illuminate\Http\Request;

class Filter
{
  protected $counter = 0;

  protected $filters = [];

  public function __construct(Request $request, array $options = [])
  {
    $prefix = $options['prefix'] ?? '';
    $status = $request->get($prefix.'status', []);

    $this->filters = [
      'status' => is_array($status) ? $status : [$status] ,
      'dates_by' => $request->get($prefix.'dates_by', 'created_at'),
      'start_date' => $request->get($prefix.'start_date', null),
      'end_date' => $request->get($prefix.'end_date', null),
      'export' => $request->get($prefix.'export', 0), 
      'page' => $request->get($prefix.'page', 1),
      'search' => $request->get($prefix.'search', null),
      'search_by' => $request->get($prefix.'search_by', 'code'),
      'per_page' => $request->get($prefix.'per_page', 10),
      'categories' => $request->get($prefix.'categories', []),
      'providers' => $request->get($prefix.'providers', []),
    ];
  }

  public function values(): array
  {
    return $this->filters;
  }

  public function counter()
  {
    return collect($this->filters)
        ->only('start_date', 'categories', 'providers')
        ->filter(fn ($value) => ! empty($value))
        ->count();
  }
}
