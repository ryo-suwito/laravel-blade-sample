<?php

namespace App\Actions\MerchantAcquisition;

use Illuminate\Http\Request;

class Filter
{
  protected $counter = 0;

  protected $filters = [];

  protected Request $request;

  public function __construct(Request $request, string $tableName)
  {
    $this->request = $request;

    $this->filters = [
      'status' => $request->get('status', ['PENDING', 'APPROVED', 'REJECTED']),
      'dates_by' => $request->get('dates_by', 'created_at'),
      'start_date' => $request->get('start_date') ?? now()->subDays(7)->toDateString(),
      'end_date' => $request->get('end_date') ?? now()->toDateString(),
      'table_name' => $tableName,
      'page' => $request->get('page', 1),
      'search' => $request->get('name', null),
      'per_page' => $request->get('per_page', 10),
      'request_action'  => $request->get('request_action', ['CREATE', 'UPDATE', 'DELETE'])
    ];
  }

  public function values(): array
  {
    return $this->filters;
  }

  public function counter()
  {
    return collect($this->filters)
        ->only('status', 'dates_by')
        ->filter(fn ($value) => ! empty($value))
        ->count();
  }
}
