<?php

namespace App\Actions\MerchantAcquisition;

use Illuminate\Http\Request;

class FilterDeletePten
{
  protected $counter = 0;

  protected $filters = [];

  protected Request $request;

  public function __construct(Request $request)
  {
    $this->request = $request;

    $this->filters = [
      'status' => $request->get('status', ['APPROVED', 'APPROVED_PROCESSED', 'REJECTED_DELETE_PTEN']),
      'type' => $request->get('type', null),
      'search' => $request->get('search', null),
      'page' => $request->get('page', 1),
      'per_page' => $request->get('per_page', 10)
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
