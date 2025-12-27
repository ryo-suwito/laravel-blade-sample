@php
    function resetUrl(array $params) {
        $url = url()->full();
        $parsedUrl = parse_url($url);

        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);

            foreach($params as $param) {
                unset($queryParams[$param]);
            }

            return http_build_query($queryParams);
        } else {
            return '';
        }
    }
@endphp

<x-app-layout>
    <x-page.header :title="__('cms.Provider Group Detail')">
        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            <x-breadcrumb.link :link="route('money_transfer.transactions.groups.index')" :text="__('cms.Provider Group')"/>
            <x-breadcrumb.active>{{ __("cms.Provider Group Detail") }}</x-breadcrumb.active>
        </x-slot>
    </x-page.header>

    <x-page.content :title="__('cms.Group Transaction')">
        <div class="modal" id="warningModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <form id="formWarningModal" action="" method="post">
                    @csrf
                    <input id="inputRetry" name="retry" type="hidden" value="0" />
                    <input id="inputSuccess" name="success" type="hidden" value="0" />
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Attention!</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure to <span id="actionBody"></span>?</p>
                        </div>
                        <div class="modal-footer">
                            <button id="actionBtn" type="submit" class="btn btn-danger"></button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Batch ID")</label>
                    <div class="col-lg-8">
                        <x-form.readonly-input-or-link 
                            item="{{ $group['transaction']['code'] }}" 
                            name="batch_id" 
                            url="{{ route('money_transfer.transactions.batches.show', ['code' => $group['transaction']['code']]) }}" /> 
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Group ID")</label>
                    <div class="col-lg-8">
                        <input type="text" name="code" class="form-control" required="" value="{{ $group['code'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Transaction Total")</label>
                    <div class="col-lg-8">
                        <input type="text" name="transaction_total" class="form-control" required="" value="{{ $group['item_count'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Amount")</label>
                    <div class="col-lg-8">
                        <input type="text" name="amount" class="form-control" required="" value="{{ number_format($group['amount'], 0, ',', '.') }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Disbursement Fee")</label>
                    <div class="col-lg-8">
                        <input type="text" name="fee" class="form-control" required="" value="{{ number_format($group['fee'], 0, ',', '.') }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Total")</label>
                    <div class="col-lg-8">
                        <input type="text" name="total" class="form-control" required="" value="{{ number_format($group['total'], 0, ',', '.') }}" readonly>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Provider")</label>
                    <div class="col-lg-8">
                        <input type="text" name="provider_name" class="form-control" required="" value="{{ $group['provider']['name'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Provider Fee")</label>
                    <div class="col-lg-8">
                        <input type="text" name="provider_fee" class="form-control" required="" value="{{ number_format($group['provider_fee'], 0, ',', '.') }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Status")</label>
                    <div class="col-lg-8">
                        <input type="text" name="status" class="form-control" required="" value="{{ $group['status'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Created At")</label>
                    <div class="col-lg-8">
                        <input type="text" name="created_at" class="form-control" required="" value="{{ date_format(date_create($group['created_at']), 'd-m-Y H:i:s') }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Updated At")</label>
                    <div class="col-lg-8">
                        <input type="text" name="updated_at" class="form-control" required="" value="{{ date_format(date_create($group['updated_at']), 'd-m-Y H:i:s') }}" readonly>
                    </div>
                </div>
            </div>
        </div> 
        
        <hr>

        @if (! empty($group['deposit']))
            <h4 class="mb-4">{{ __('cms.Top Up Transaction') }}</h4>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">@lang("cms.Top Up ID")</label>
                        <div class="col-lg-8">
                            <x-form.readonly-input-or-link 
                                item="{{ $group['deposit']['code'] ?? '' }}" 
                                name="top_up_id" 
                                url="{{ route('money_transfer.provider_deposits.detail', ['id' => $group['deposit']['id']]) }}" /> 
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">@lang("cms.Bank Transfer")</label>
                        <div class="col-lg-8">
                            <input type="text" name="bank" class="form-control" required="" value="{{ $group['deposit']['bank']['name'] }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">@lang("cms.Bank Account Number")</label>
                        <div class="col-lg-8">
                            <input type="text" name="bank_account_number" class="form-control" required="" value="{{ $group['deposit']['provider_account_number'] }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">@lang("cms.Amount")</label>
                        <div class="col-lg-8">
                            <input type="text" name="amount" class="form-control" required="" value="{{ number_format($group['deposit']['amount'], 0, ',', '.') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">@lang("cms.Unique Code")</label>
                        <div class="col-lg-8">
                            <input type="text" name="unique_code" class="form-control" required="" value="{{ number_format($group['deposit']['unique_code'], 0, ',', '.') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">@lang("cms.Total")</label>
                        <div class="col-lg-8">
                            <input type="text" name="total" class="form-control" required="" value="{{ number_format($group['deposit']['total'], 0, ',', '.') }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">@lang("cms.Status")</label>
                        <div class="col-lg-8">
                                <input type="text" name="status" class="form-control" required="" value="{{ $group['deposit']['status'] ?? '' }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">@lang("cms.Created At")</label>
                        <div class="col-lg-8">
                            <input type="text" name="created_at" class="form-control" required="" value="{{ date_format(date_create($group['deposit']['created_at']), 'd-m-Y H:i:s') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">@lang("cms.Updated At")</label>
                        <div class="col-lg-8">
                            <input type="text" name="updated_at" class="form-control" required="" value="{{ date_format(date_create($group['deposit']['updated_at']), 'd-m-Y H:i:s') }}" readonly>
                        </div>
                    </div>
                </div>
            </div> 

            <hr>
        @endif
        <form action="{{ route('money_transfer.transactions.groups.show', ['code' => $group['code']]) }}" id="form">
        @hasaccess('MONEY_TRANSFER.TRANSFER_PROXIES.VIEW')
            <h4>@lang('cms.Proxy List')</h4>

            <div class="row mb-3">
                <div class="col d-md-flex d-lg-flex" style="gap: 5px;">
                    <div class="input-group" style="max-width: 400px;">
                        <select name="proxy_search_by" id="proxy_search_by" class="form-control">
                            <option value="code" @if (request()->get('proxy_search_by') == 'code') selected @endif>Code</option>
                            <option value="ref_code" @if (request()->get('proxy_search_by') == 'ref_code') selected @endif>Ref Code</option>
                            <option value="provider_trx_code" @if (request()->get('proxy_search_by') == 'provider_trx_code') selected @endif>Provider Transaction Code</option>
                        </select>
                        <div class="btn-group">
                            <input type="proxy_search" placeholder="Search" value="{{ request()->get('proxy_search') }}" name="proxy_search" class="search-input form-control">
                            <i class="search-clear icon-cancel-circle"></i>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" style="min-width: 150px;" type="button" id="dropdownFilter" data-toggle="dropdown" data-flip="false" aria-haspopup="true" aria-expanded="false">
                            <i class="icon-equalizer"></i>
                            Filter @if ($proxyFilterCounter > 0)
                                <span>|</span> <span>{{ $proxyFilterCounter }}</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownFilter">
                            <li class="mb-2 p-2" style="background-color: #4F4A45;">
                                <div class="row text-center">
                                    <div class="col">
                                        <a href="{{ request()->url() . '?' . resetUrl(['proxy_categories','proxy_status'])}}" class="btn btn-danger proxy-btn-reset">Reset</a>
                                    </div>
                                    <div class="col">
                                        <button type="button" class="btn btn-primary" id="btn-apply-filter">Apply</button>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <label class="dropdown-item"><input type="checkbox" id="proxy_status"
                                @if ($proxyFilters['status'])
                                    checked
                                @endif
                                >&nbsp;&nbsp; Status</label>
                            </li>
                            <li class="proxy_status pl-3 submenu
                            @if ($proxyFilters['status'])
                                submenu-active
                            @endif
                            ">
                                <p class="dropdown-item">
                                    <button id="proxy_status_all" type="button" class="btn border border-white rounded btn-select-all" style="font-size: x-small;">Select All</button>
                                </p>
                            </li>
                            <li class="proxy_status pl-3 submenu
                            @if ($proxyFilters['status'])
                                submenu-active
                            @endif
                            ">
                                <label class="dropdown-item"><input type="checkbox" name="proxy_status[]" value="PENDING"
                                @if (in_array('PENDING', $proxyFilters['status']))
                                    checked
                                @endif
                                >&nbsp;&nbsp; PENDING</label>
                            </li>
                            <li class="proxy_status pl-3 submenu
                            @if ($proxyFilters['status'])
                                submenu-active
                            @endif
                            ">
                                <label class="dropdown-item"><input type="checkbox" name="proxy_status[]" value="SUCCESS"
                                @if (in_array('SUCCESS', $proxyFilters['status']))
                                    checked
                                @endif
                                >&nbsp;&nbsp; SUCCESS</label>
                            </li>
                            <li class="proxy_status pl-3 submenu
                            @if ($proxyFilters['status'])
                                submenu-active
                            @endif
                            ">
                                <label class="dropdown-item"><input type="checkbox" name="proxy_status[]" value="FAILED"
                                @if (in_array('FAILED', $proxyFilters['status']))
                                    checked
                                @endif
                                >&nbsp;&nbsp; FAILED</label>
                            </li>
                            <li>
                                <label class="dropdown-item"><input type="checkbox" id="proxy_categories"
                                @if ($proxyFilters['categories'])
                                    checked
                                @endif
                                >&nbsp;&nbsp; Category</label>
                            </li>
                            <li class="proxy_categories pl-3 submenu
                            @if ($proxyFilters['categories'])
                                submenu-active
                            @endif
                            ">
                                <p class="dropdown-item">
                                    <button id="proxy_categories_all" type="button" class="btn border border-white rounded btn-select-all" style="font-size: x-small;">Select All</button>
                                </p>
                            </li>
                            <li class="proxy_categories pl-3 submenu
                            @if ($proxyFilters['categories'])
                                submenu-active
                            @endif
                            ">
                                <label class="dropdown-item"><input type="checkbox" name="proxy_categories[]" value="POOL_TO_PARKING"
                                @if (in_array('POOL_TO_PARKING', $proxyFilters['categories']))
                                    checked
                                @endif
                                >&nbsp;&nbsp; POOL_TO_PARKING</label>
                            </li>
                            <li class="proxy_categories pl-3 submenu
                            @if ($proxyFilters['categories'])
                                submenu-active
                            @endif
                            ">
                                <label class="dropdown-item"><input type="checkbox" name="proxy_categories[]" value="PARKING_TO_PROVIDER"
                                @if (in_array('PARKING_TO_PROVIDER', $proxyFilters['categories']))
                                    checked
                                @endif
                                >&nbsp;&nbsp; PARKING_TO_PROVIDER</label>
                            </li>
                            <li class="proxy_categories pl-3 submenu
                            @if ($proxyFilters['categories'])
                                submenu-active
                            @endif
                            ">
                                <label class="dropdown-item"><input type="checkbox" name="proxy_categories[]" value="PARKING_TO_POOL"
                                @if (in_array('PARKING_TO_POOL', $proxyFilters['categories']))
                                    checked
                                @endif
                                >&nbsp;&nbsp; PARKING_TO_POOL</label>
                            </li>
                        </ul>
                    </div>

                    <button class="btn btn-primary form-control" style="max-width: 150px;" type="submit" id="btn-search"><i class="icon-search4"></i> @lang("cms.Search")</button>
                </div>
            </div>

            @if ($proxyFilterCounter > 0)
            <div class="row mb-2 ml-2">
                <p>Search results based on filters: </p>
                <div class="mx-1">
                    @if ($proxyFilters['categories'])
                        <span class="badge badge-info">{{ 'Categories: ' . implode(', ', $proxyFilters['categories']) }} <span class="filter-clear" data-filter="proxy_categories" style="cursor: pointer;">X</span> </span>
                    @endif

                    @if ($proxyFilters['status'])
                        <span class="badge badge-info">{{ 'Status: ' . implode(', ', $proxyFilters['status']) }} <span class="filter-clear" data-filter="proxy_status" style="cursor: pointer;">X</span> </span>
                    @endif

                </div>
                <p id="proxy_delete_all_filters" style="cursor: pointer;"><u>Delete all filters</u></p>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped proxyTable">
                    <thead>
                        <tr>
                            <th>{{ __("cms.Code") }}</th>
                            <th>{{ __("cms.Ref Code") }}</th>
                            <th>{{ __("cms.Provider Transaction Code") }}</th>
                            <th>{{ __("cms.Source Bank") }}</th>
                            <th>{{ __("cms.Destination Bank") }}</th>
                            <th>{{ __("cms.Amount") }}</th>
                            <th>{{ __("cms.Category") }}</th>
                            <th>{{ __("cms.Created At") }}</th>
                            <th>{{ __("cms.Status") }}</th>
                            <th>{{ __("cms.Actions") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($proxies) == 0)
                            <tr class="text-center">
                                <td colspan="10"> Data Not Found</td>
                            </tr>
                        @endif
                        @foreach($proxies as $proxy)
                        <tr>
                            <td>
                                <a href="{{ route('money_transfer.transfer_proxies.show', ['code' => $proxy['code']]) }}" target="_blank">
                                    {{ $proxy['code'] }}
                                </a>
                            </td>
                            <td>{{ $proxy['ref']['code'] }}</td>
                            <td>{{ $proxy['provider_trx_code'] }}</td>
                            <td>{{ $proxy['source_bank'] }}</td>
                            <td>{{ $proxy['destination_bank'] }}</td>
                            <td>{{ number_format($proxy['amount'], 0, ',', '.') }}</td>
                            <td>{{ $proxy['action'] }}</td>
                            <td>{{ $proxy['created_at'] }}</td>
                            <td>
                                <span class="badge
                                    @if ($proxy['status'] == 'SUCCESS')
                                        bg-success
                                    @elseif (in_array($proxy['status'], ['PENDING', 'ONGOING']))
                                        bg-secondary
                                    @else
                                        bg-danger
                                    @endif
                                ">{{ $proxy['status'] }}</span>
                            </td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                        @hasaccess('MONEY_TRANSFER.TRANSFER_PROXIES.UPDATE')
                                            <div class="d-flex flex-column flex-md-row align-items-md-center">
                                                @if(in_array($proxy['status'], ['FAILED']))
                                                    <div class="px-1 mb-3 mb-md-0">
                                                        <button
                                                            type="button"
                                                            id="btnProxyRetry"
                                                            class="btn btn-warning w-100 w-sm-auto"
                                                            data-toggle="modal"
                                                            data-target="#warningModal"
                                                            data-action="{{ route('money_transfer.transfer_proxies.update', ['id' => $proxy['id']]) }}"
                                                            data-label="Retry">
                                                            Retry
                                                        </button>
                                                    </div>
                                                    <div class="px-1 mb-3 mb-md-0">
                                                        <button
                                                            type="button"
                                                            id="btnProxySuccess"
                                                            class="btn btn-success w-100 w-sm-auto"
                                                            data-toggle="modal"
                                                            data-target="#warningModal"
                                                            data-action="{{ route('money_transfer.transfer_proxies.update', ['id' => $proxy['id']]) }}"
                                                            data-label="Mark As Success">
                                                            Mark As Success
                                                        </button>
                                                    </div>
                                                @else
                                                    <a href="{{ route('money_transfer.transfer_proxies.show', ['code' => $proxy['code']]) }}" class="dropdown-item">
                                                        <i class="icon-search4"></i> {{ __("cms.Detail") }}
                                                    </a>
                                                @endif
                                            </div>
                                        @elseaccess
                                            <a href="{{ route('money_transfer.transfer_proxies.show', ['code' => $proxy['code']]) }}" class="dropdown-item">
                                                <i class="icon-search4"></i> {{ __("cms.Detail") }}
                                            </a>
                                        @endhasaccess
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="float justify-content-between">
                <div class="float-left mt-3">
                    <div class="d-flex">
                        <span class="mr-3" style="margin:auto;">List </span>
                        <select name="proxy_per_page" id="perPageDropdown" class="form-control mr-2">
                            @foreach ($perPages as $item)
                                <option value="{{ $item }}"
                                @if ($item == $proxyFilters['per_page'])
                                    selected
                                @endif
                                >{{ $item }}</option>
                            @endforeach
                        </select>
                        <span class="mr-2" style="margin:auto;">Total </span>
                        <span style="margin:auto;">{{ $proxyPaginator->total() }}</span>
                    </div>
                </div>
                <div class="float-right mt-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="pagination pagination-flat justify-content-end">
                                {{ $proxyPaginator }}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <hr style="margin-top: 80px;">
        @endhasaccess

            <h4 class="mb-4">{{ __('cms.Transaction List') }}</h4>

            <input type="hidden" name="export" value="{{ $filters['export'] }}" id="export">

            <div class="row mb-3">
                
                <div class="col d-md-flex d-lg-flex" style="gap: 5px;">
                    <div class="input-group" style="max-width: 400px;">
                        <select name="search_by" id="search_by" class="form-control">
                            <option value="code" @if ($filters['search_by'] == 'code') selected @endif>Code</option>
                            <option value="provider_transaction_id" @if ($filters['search_by'] == 'provider_transaction_id') selected @endif>Provide Transaction Id</option>
                        </select>
                        <div class="btn-group">
                            <input type="search" placeholder="Search" value="{{ request()->get('search') }}" name="search" class="search-input form-control">
                            <i class="search-clear icon-cancel-circle"></i>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" style="min-width: 150px;" type="button" id="dropdownFilter" data-toggle="dropdown" data-flip="false" aria-haspopup="true" aria-expanded="false">
                            <i class="icon-equalizer"></i>
                            Filter @if ($filterCounter > 0)
                                <span>|</span> <span>{{ $filterCounter }}</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownFilter">
                            <li class="mb-2 p-2" style="background-color: #4F4A45;">
                                <div class="row text-center">
                                    <div class="col">
                                        <a href="{{ request()->url() . '?' . resetUrl(['status']) }}" class="btn btn-danger btn-reset">Reset</a>
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary" id="btn-apply-filter">Apply</button>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <label class="dropdown-item"><input type="checkbox" id="status"
                                @if ($filters['status'])
                                    checked
                                @endif
                                >&nbsp;&nbsp; Status</label>
                            </li>
                            <li class="status pl-3 submenu
                            @if ($filters['status'])
                                submenu-active
                            @endif
                            ">
                                <p class="dropdown-item">
                                    <button id="status_all" type="button" class="btn border border-white rounded btn-select-all" style="font-size: x-small;">Select All</button>
                                </p>
                            </li>
                            <li class="status pl-3 submenu
                            @if ($filters['status'])
                                submenu-active
                            @endif
                            ">
                                <label class="dropdown-item"><input type="checkbox" name="status[]" value="PENDING"
                                @if (in_array('PENDING', $filters['status']))
                                    checked
                                @endif
                                >&nbsp;&nbsp; PENDING</label>
                            </li>
                            <li class="status pl-3 submenu
                            @if ($filters['status'])
                                submenu-active
                            @endif
                            ">
                                <label class="dropdown-item"><input type="checkbox" name="status[]" value="SUCCESS"
                                @if (in_array('SUCCESS', $filters['status']))
                                    checked
                                @endif
                                >&nbsp;&nbsp; SUCCESS</label>
                            </li>
                            <li class="status pl-3 submenu
                            @if ($filters['status'])
                                submenu-active
                            @endif
                            ">
                                <label class="dropdown-item"><input type="checkbox" name="status[]" value="HOLDING"
                                @if (in_array('HOLDING', $filters['status']))
                                    checked
                                @endif
                                >&nbsp;&nbsp; HOLDING</label>
                            </li>
                            <li class="status pl-3 submenu
                            @if ($filters['status'])
                                submenu-active
                            @endif
                            ">
                                <label class="dropdown-item"><input type="checkbox" name="status[]" value="FAILED"
                                @if (in_array('FAILED', $filters['status']))
                                    checked
                                @endif
                                >&nbsp;&nbsp; FAILED</label>
                            </li>
                        </ul>
                    </div>

                    <button class="btn btn-primary form-control" id="btn-search" style="max-width: 150px;" type="submit"><i class="icon-search4"></i> {{ __("cms.Search") }}</button>
                    <button class="btn btn-success form-control" id="btn-export" style="max-width: 150px;" type="submit"><i class="icon-download"></i> {{ __("cms.Export") }}</button>
                </div>
            </div>

            @if ($filterCounter > 0)
            <div class="row mb-2 ml-2">
                <p>Search results based on filters: </p>
                <div class="mx-1">

                    @if ($filters['status'])
                        <span class="badge badge-info">{{ 'Status: ' . implode(', ', $filters['status']) }} <span class="filter-clear" data-filter="status" style="cursor: pointer;">X</span> </span>
                    @endif
                </div>
                <p id="delete_all_filters" style="cursor: pointer;"><u>Delete all filters</u></p>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ __("cms.Code") }}</th>
                            <th>{{ __("cms.Provider Transaction ID") }}</th>
                            <th>{{ __("cms.Beneficiary Name") }}</th>
                            <th>{{ __("cms.Tag") }}</th>
                            <th>{{ __("cms.Beneficiary Bank Type") }}</th>
                            <th>{{ __("cms.Disbursement Amount") }}</th>
                            <th>{{ __("cms.Created By") }}</th>
                            <th>{{ __("cms.Created At") }}</th>
                            <th>{{ __("cms.Updated At") }}</th>
                            <th>{{ __("cms.Status") }}</th>
                            <th>{{ __("cms.Actions") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($items) == 0)
                            <tr class="text-center">
                                <td colspan="11"> Data Not Found</td>
                            </tr>
                        @endif
                        @foreach($items as $item)
                        <tr>
                            <td>{{ $item['code'] }}</td>
                            <td>{{ $item['provider_transaction_id'] ?? '' }}</td>
                            <td>{{ $item['recipient_name'] }}</td>
                            <td>{{ $item['transaction']['entity_type'] }}</td>
                            <td>{{ $item['bank']['name'] }}</td>
                            <td>{{ number_format($item['amount'], 0, ',', '.') }}</td>
                            <td>{{ $item['transaction']['created_by'] }}</td>
                            <td>{{ date_format(date_create($item['created_at']), 'd-m-Y H:i:s') }}</td>
                            <td>{{ date_format(date_create($item['updated_at']), 'd-m-Y H:i:s') }}</td>
                            <td>
                                <span class="badge
                                    @if ($item['status'] == 'SUCCESS')
                                        bg-success
                                    @elseif (in_array($item['status'], ['PENDING', 'HOLDING']))
                                        bg-secondary
                                    @else
                                        bg-danger
                                    @endif
                                ">{{ $item['status'] }}</span>
                            </td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('money_transfer.transactions.items.show', ['code' => $item['code']]) . '?from=provider' }}" class="dropdown-item">
                                                <i class="icon-search4"></i> {{ __("cms.Detail") }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="float justify-content-between">
                <div class="float-left mt-3">
                    <div class="d-flex">
                        <span class="mr-3" style="margin:auto;">List </span>
                        <select name="per_page" id="perPageDropdown" class="form-control mr-2">
                            @foreach ($perPages as $item)
                                <option value="{{ $item }}"
                                @if ($item == $filters['per_page'])
                                    selected
                                @endif
                                >{{ $item }}</option>
                            @endforeach
                        </select>
                        <span class="mr-2" style="margin:auto;">Total </span>
                        <span style="margin:auto;">{{ $paginator->total() }}</span>
                    </div>
                </div>
                <div class="float-right mt-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="pagination pagination-flat justify-content-end">
                                {{ $paginator }}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </x-page.content>

    @swal

    @push('styles')
    <style>
        .dropdown-menu li {
            position: relative;
        }

        .dropdown-menu .dropdown-submenu {
            display: none;
            position: absolute;
            left: 100%;
            top: -7px;
        }

        .submenu {
            display: none;
        }

        .submenu-active {
            display: block;
        }

        .btn-select-all:hover {
            background-color: white;
            border: 1px solid black;
            color: #000000;
        }

        .search-input {
            width: 200px;
        }

        .search-clear {
            position: absolute;
            right: 5px;
            top: 0;
            bottom: 0;
            height: 14px;
            margin: auto;
            font-size: 14px;
            cursor: pointer;
            color: #ffffff;
        }

        .table {
            white-space: nowrap;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function resetUrl()
        {
            if($('#form').prop('action') == "{{ route('money_transfer.transactions.groups.export', ['code' => $group['code']]) }}") {
                $('#export').val(0);
                $('#form').prop('action', "{{ route('money_transfer.transactions.groups.show', ['code' => $group['code']]) }}")
            }
        }

        function setDataModal(btnLabel, route) {
            $("#actionBody").html(btnLabel);
            $("#actionBtn").html(btnLabel);
            $("#formWarningModal").attr('action', route);
            $("#inputRetry").val(btnLabel == 'Retry' ? 1 : 0);
            $("#inputSuccess").val(btnLabel == 'Mark As Success' ? 1 : 0);
        }

        $(document).on('click', '.page-content .dropdown-menu', function (e) {
            e.stopPropagation();
        });

        $('#perPageDropdown').change(function(e) {
            resetUrl()
            $('#form').submit()
        })

        $('#status').on('change', function() {
            if($('#status').is(':checked')) {
                $('.status').css('display', 'block');
            } else {
                $('input[name="status[]"]').prop('checked', false);
                $('.status').css('display', 'none');
            }
        });

        $('#status_all').on('click', function(e) {
            $('input[name="status[]"]').prop('checked', true);
        });

        $('.filter-clear').on('click', function(e) {
            var filter = $(this).data('filter');

            $('input[name="' + filter + '[]"]').prop('checked', false);
            $('.' + filter).css('display', 'none');

            resetUrl()
            $('#form').submit()
        });

        $('#delete_all_filters').on('click', function(e) {
            window.location.href = $('.btn-reset').attr('href');
        });

        $('#btn-export').on('click', function(e) {
            $('#export').val(1);

            $('#form').prop('action', "{{ route('money_transfer.transactions.groups.export', ['code' => $group['code']]) }}")

            $('#form').submit()

            Swal.fire({
                'text': "The export request is being processed please wait",
                'icon': 'success',
                'toast': true,
                'timer': 5000,
                'showConfirmButton': false,
                'position': 'top-right',
            });
        })

        $('#btn-search').on('click', function(e) {
            $(this).text("Loading...");
            $(this).prop('disabled', true);

            resetUrl()
            $('#form').submit()
        })

        $('#btn-apply-filter').on('click', function(e) {
            $(this).text("Loading...");
            $(this).prop('disabled', true);

            resetUrl()
            $('#form').submit()
        })

        $(".search-clear").click(function(){
            $(".search-input").val('');
        });

        const btnListIdForModalWarning = ["btnProxyRetry", "btnProxySuccess",];

        $("#" + btnListIdForModalWarning.join(",#")).click(function(){
            setDataModal($(this).data('label'), $(this).data('action'));
        });

        $('#formWarningModal').submit(function() {
            $(this).find(':button[type=submit]').prop('disabled', true);
            $(this).find(':button[type=submit]').html('Loading..');
        })

    </script>

    <script>
        $('#proxy_categories').on('change', function() {
            if($('#proxy_categories').is(':checked')) {
                $('.proxy_categories').css('display', 'block');
            } else {
                $('input[name="proxy_categories[]"]').prop('checked', false);
                $('.proxy_categories').css('display', 'none');
            }
        });

        $('#proxy_categories_all').on('click', function(e) {
            $('input[name="proxy_categories[]"]').prop('checked', true);
        });

        $('#proxy_status').on('change', function() {
            if($('#proxy_status').is(':checked')) {
                $('.proxy_status').css('display', 'block');
            } else {
                $('input[name="proxy_status[]"]').prop('checked', false);
                $('.proxy_status').css('display', 'none');
            }
        });

        $('#proxy_status_all').on('click', function(e) {
            $('input[name="proxy_status[]"]').prop('checked', true);
        });

        $('#proxy_delete_all_filters').on('click', function(e) {
            window.location.href = $('.proxy-btn-reset').attr('href');
        });
    </script>
    @endpush

</x-app-layout>
