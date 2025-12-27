<x-app-layout>
    <x-page.header :title="__('cms.Transaction')">
        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            <x-breadcrumb.active>{{ __("cms.Transaction") }}</x-breadcrumb.active>
        </x-slot>

    </x-page.header>

    <x-page.content>
        <div class="modal" id="warningModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <form id="formWarningModal" action="" method="post">
                    @csrf
                    <input id="inputRetry" name="retry" type="hidden" value="0" />
                    <input id="inputSuccess" name="success" type="hidden" value="0" />
                    <input id="inputFailed" name="failed" type="hidden" value="0" />
                    <input name="provider" type="hidden" value="{{ implode(',', $filters['providers']) }}" />

                    <div id="selectedTransactions"></div>
                    
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Attention!</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure to Bulk <span id="actionBody"></span>?</p>
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
            <a href="{{ route('money_transfer.transactions.index') }}" class="btn @if($filters['status'] == 'all') btn-secondary @else btn-outline-secondary @endif mr-2">
                All @if(($statusCounter['success']+$statusCounter['pending']+$statusCounter['failed']+$statusCounter['holding']) > 0)<span class="badge badge-success ml-2">{{ ($statusCounter['success']+$statusCounter['pending']+$statusCounter['failed']+$statusCounter['holding']) }}</span>@endif
            </a>
            @foreach($statusCounter as $key => $stat)
                <a href="{{ $filters['status'] == $key ? '#' : route('money_transfer.transactions.index').'?status='.$key }}" class="btn @if($filters['status'] == $key) btn-secondary @else btn-outline-secondary @endif mr-2">
                    {{ ucfirst($key) }} @if($stat > 0)<span class="badge badge-success ml-2">{{ $stat }}</span>@endif
                </a>
            @endforeach
        </div>
        <hr>
        <form action="{{ route('money_transfer.transactions.index').'?tab='.$filters['status'] }}" method="get" id="form">
            
            <input type="hidden" name="export" value="{{ $filters['export'] }}" id="export">
            <input type="hidden" name="status" value="{{ $filters['status'] }}">

            <div class="row mb-3">
                <div class="col d-md-flex d-lg-flex" style="gap: 5px;">
                    <div class="input-group" style="max-width: 400px;">
                        <select name="search_by" id="search_by" class="form-control">
                            <option value="code" @if (request()->get('search_by') == 'code') selected @endif>Code</option>
                            <option value="provider_transaction_id" @if (request()->get('search_by') == 'provider_transaction_id') selected @endif>Provider Transaction ID</option>
                            <option value="recipient_name" @if (request()->get('search_by') == 'recipient_name') selected @endif>Beneficiary Name</option>
                        </select>
                        <div class="btn-group">
                            <input id="searchinput" type="search" placeholder="Search" value="{{ request()->get('search') }}" name="search" class="form-control">
                            <i id="searchclear" class="icon-cancel-circle"></i>
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
                                        <a href="{{ request()->url() . '?search_by=' . $filters['search_by'] . '&search=' . $filters['search'] }}" class="btn btn-danger btn-reset">Reset</a>
                                    </div>
                                    <div class="col">
                                        <button type="button" class="btn btn-primary" id="btn-apply-filter">Apply</button>
                                    </div>
                                </div>
                            </li>
                            <!-- start of dates -->
                            <li>
                                <label class="dropdown-item"><input type="checkbox" id="date_filter"
                                @if ($filters['dates_by'] && ($filters['start_date'] || $filters['end_date']) )
                                    checked
                                @endif
                                >&nbsp;&nbsp; Dates</label>
                            </li>
                            <li class="dates_by pl-3 submenu
                            @if ($filters['dates_by'])
                                submenu-active
                            @endif
                            ">
                                <label class="dropdown-item"><input type="radio" name="dates_by" id="dates_by_created_at" value="created_at"
                                @if ($filters['dates_by'] == 'created_at')
                                    checked
                                @endif
                                >&nbsp;&nbsp; Created At</label>
                                <ul id="dates_by_submenu" class="dropdown-menu dropdown-submenu p-2">
                                    <li>Start Date</li>
                                    <li class="mb-2">
                                        <input type="date" class="form-control bg-default" name="start_date" value="{{ $filters['start_date'] }}">
                                    </li>
                                    <li>End Date</li>
                                    <li>
                                        <input type="date" class="form-control bg-default" name="end_date" value="{{ $filters['end_date'] }}">
                                    </li>
                                </ul>
                            </li>
                            <!-- end of dates -->
                            <!-- start of tags -->
                            <li>
                                <label class="dropdown-item"><input type="checkbox" id="tags"
                                @if ($filters['tags'])
                                    checked
                                @endif
                                >&nbsp;&nbsp; Tag</label>
                            </li>
                            <li class="tags pl-3 submenu
                            @if ($filters['tags'])
                                submenu-active
                            @endif
                            ">
                                <p class="dropdown-item">
                                    <button id="tags_all" type="button" class="btn border border-white rounded btn-select-all" style="font-size: x-small;">Select All</button>
                                </p>
                            </li>
                            <li class="tags pl-3 submenu
                            @if ($filters['tags'])
                                submenu-active
                            @endif
                            ">
                                <label class="dropdown-item"><input type="checkbox" name="tags[]" value="PARTNER"
                                @if (in_array('PARTNER', $filters['tags']))
                                    checked
                                @endif
                                >&nbsp;&nbsp; PARTNER</label>
                            </li>
                            <li class="tags pl-3 submenu
                            @if ($filters['tags'])
                                submenu-active
                            @endif
                            ">
                                <label class="dropdown-item"><input type="checkbox" name="tags[]" value="BENEFICIARY"
                                @if (in_array('BENEFICIARY', $filters['tags']))
                                    checked
                                @endif
                                >&nbsp;&nbsp; BENEFICIARY</label>
                            </li>
                            <!-- end of tags -->
                            <!-- start of providers -->
                            <li>
                                <label class="dropdown-item"><input type="checkbox" id="providers"
                                @if ($filters['providers'])
                                    checked
                                @endif
                                >&nbsp;&nbsp; Provider</label>
                            </li>
                            <div id="provider-list">
                                <li class="providers pl-3 submenu
                                @if ($filters['providers'])
                                    submenu-active
                                @endif
                                ">
                                    <p class="dropdown-item">
                                        <button id="providers_all" type="button" class="btn border border-white rounded btn-select-all" style="font-size: x-small;">Select All</button>
                                    </p>
                                </li>
                            </div>                         
                            <!-- end of providers -->
                        </ul>
                    </div>

                    <button class="btn btn-primary form-control" style="max-width: 150px;" type="submit" id="btn-search"><i class="icon-search4"></i> @lang("cms.Search")</button>
                    <button class="btn btn-success form-control" id="btn-export" style="max-width: 150px;" type="submit"><i class="icon-download"></i> {{ __("cms.Export") }}</button>
                </div>
            </div>

            @if ($filterCounter > 0)
            <div class="row mb-2 ml-2">
                <p>Search results based on filters: </p>
                <div class="mx-1">
                    @if ($filters['start_date'] || $filters['end_date'])
                        <span class="badge badge-info">
                            {{ date_format(date_create($filters['start_date']), 'd-m-Y H:i:s') . ' - ' . date_format(date_create($filters['end_date'] . ' 23:59:59'), 'd-m-Y H:i:s') }}
                            <span class="filter-clear" data-filter="dates" style="cursor: pointer;"> X</span>
                        </span>
                    @endif

                    @if ($filters['providers'])
                        <span class="badge badge-info">{{ 'Provider: ' . implode(', ', $filters['providers']) }} <span class="filter-clear" data-filter="providers" style="cursor: pointer;">X</span> </span>
                    @endif

                    @if ($filters['tags'])
                        <span class="badge badge-info">{{ 'Tag: ' . implode(', ', $filters['tags']) }} <span class="filter-clear" data-filter="tags" style="cursor: pointer;">X</span> </span>
                    @endif
                </div>
                <p id="delete_all_filters" style="cursor: pointer;"><u>Delete all filters</u></p>
            </div>
            @endif

            @php
                $totalItem = count($transactions);
                
                $isNeedBulkAction = $filters['status'] == 'holding' 
                            && count($filters['providers']) == 1 
                            && in_array($filters['providers'][0] ?? '', ['BCA', 'CIMB', 'DANA'])
                            && $totalItem > 0;
            @endphp
            
            @hasaccess('MONEY_TRANSFER.TRANSACTIONS_UPDATE')
                @if ($isNeedBulkAction)
                    @if ($filters['providers'][0] == 'BCA')
                        <div class="mb-3">
                            <button
                                type="button"
                                id="btnUpdate"
                                class="btn btn-primary w-100 w-sm-auto btn-action-BCA"
                                disabled
                                data-toggle="modal"
                                data-target="#warningModal"
                                data-action="{{ route('money_transfer.transactions.bulk_update') }}"
                                data-label="Update">
                                <i class="icon-spinner11 mr-2"></i> Bulk Update
                            </button>

                            <button
                                type="button"
                                id="btnRetry"
                                class="btn btn-warning w-100 w-sm-auto btn-action-BCA"
                                disabled
                                data-toggle="modal"
                                data-target="#warningModal"
                                data-action="{{ route('money_transfer.transactions.bulk_update') }}"
                                data-label="Retry">
                                <i class="icon-spinner11 mr-2"></i> Bulk Retry
                            </button>

                            <button
                                type="button"
                                id="btnSuccess"
                                class="btn btn-success w-100 w-sm-auto btn-action-BCA"
                                disabled
                                data-toggle="modal"
                                data-target="#warningModal"
                                data-action="{{ route('money_transfer.transactions.bulk_update') }}"
                                data-label="Mark As Success">
                                <i class="icon-spinner11 mr-2"></i> Bulk Mark As Success
                            </button>
                                                        
                            <button
                                type="button"
                                id="btnFailed"
                                class="btn btn-danger w-100 w-sm-auto btn-action-BCA"
                                disabled
                                data-toggle="modal"
                                data-target="#warningModal"
                                data-action="{{ route('money_transfer.transactions.bulk_update') }}"
                                data-label="Mark As Failed">
                                <i class="icon-spinner11 mr-2"></i> Bulk Mark As Failed
                            </button>
                        </div>
                    @elseif ($filters['providers'][0] == 'CIMB')
                        <div class="mb-3">
                            <button
                                type="button"
                                id="btnRetry"
                                class="btn btn-warning w-100 w-sm-auto btn-action-CIMB"
                                disabled
                                data-toggle="modal"
                                data-target="#warningModal"
                                data-action="{{ route('money_transfer.transactions.bulk_update') }}"
                                data-label="Retry">
                                <i class="icon-spinner11 mr-2"></i> Bulk Retry
                            </button>

                            <button
                                type="button"
                                id="btnUpdate"
                                class="btn btn-primary w-100 w-sm-auto btn-action-CIMB"
                                disabled
                                data-toggle="modal"
                                data-target="#warningModal"
                                data-action="{{ route('money_transfer.transactions.bulk_update') }}"
                                data-label="Update">
                                <i class="icon-spinner11 mr-2"></i> Bulk Update
                            </button>

                            <button
                                type="button"
                                id="btnFailed"
                                class="btn btn-danger w-100 w-sm-auto btn-action-CIMB"
                                disabled
                                data-toggle="modal"
                                data-target="#warningModal"
                                data-action="{{ route('money_transfer.transactions.bulk_update') }}"
                                data-label="Mark As Failed">
                                <i class="icon-spinner11 mr-2"></i> Bulk Mark As Failed
                            </button>
                        </div>
                    @elseif ($filters['providers'][0] == 'DANA')
                        <div class="mb-3">
                            <button
                                type="button"
                                id="btnRetry"
                                class="btn btn-warning w-100 w-sm-auto btn-action-DANA"
                                disabled
                                data-toggle="modal"
                                data-target="#warningModal"
                                data-action="{{ route('money_transfer.transactions.bulk_update') }}"
                                data-label="Retry">
                                <i class="icon-spinner11 mr-2"></i> Bulk Retry
                            </button>

                            <button
                                type="button"
                                id="btnSuccess"
                                class="btn btn-success w-100 w-sm-auto btn-action-DANA"
                                disabled
                                data-toggle="modal"
                                data-target="#warningModal"
                                data-action="{{ route('money_transfer.transactions.bulk_update') }}"
                                data-label="Mark As Success">
                                <i class="icon-spinner11 mr-2"></i> Bulk Mark As Success
                            </button>

                            <button
                                type="button"
                                id="btnFailed"
                                class="btn btn-danger w-100 w-sm-auto btn-action-DANA"
                                disabled
                                data-toggle="modal"
                                data-target="#warningModal"
                                data-action="{{ route('money_transfer.transactions.bulk_update') }}"
                                data-label="Mark As Failed">
                                <i class="icon-spinner11 mr-2"></i> Bulk Mark As Failed
                            </button>
                        </div>
                    @endif
                @endif
            @endhasaccess

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        @if ($isNeedBulkAction)
                            <th>
                                <input type="checkbox" id="checkedAll">
                            </th>
                        @endif
                        <th>@lang("cms.Code")</th>
                        <th>@lang("cms.Provider Transaction ID")</th>
                        <th>@lang("cms.Beneficiary Name")</th>
                        <th>@lang("cms.Tag")</th>
                        <th>@lang("cms.Provider")</th>
                        <th>@lang("cms.Beneficiary Bank Type")</th>
                        <th>@lang("cms.Disbursement Amount")</th>
                        <th>@lang("cms.Disbursement Fee")</th>
                        <th>@lang("cms.Created At")</th>
                        <th>@lang("cms.Status")</th>
                        <th>@lang("cms.Actions")</th>
                    </tr>
                    </thead>

                    <tbody>
                        @if ($totalItem == 0)
                            <tr>
                                <td colspan="10" class="text-center">Data Not Found</td>
                            </tr>
                        @endif
                        @foreach($transactions as $trans)
                            <tr>
                                @if ($isNeedBulkAction)
                                    @php

                                        $lastUpdated = \Carbon\Carbon::parse($trans['updated_at']);            
                                        $now = \Carbon\Carbon::now('Asia/Jakarta');
                                        
                                        $diff = $lastUpdated->diffInSeconds($now, false);

                                        $canUpdate = $diff > $retryTimeLimit;
                                    @endphp

                                <td>
                                    <input type="checkbox" id="cb-{{ $trans['code'] }}" data-code="{{ $trans['code'] }}" class="bulkAction"
                                    @if (! $canUpdate)
                                        disabled
                                    @endif>
                                </td>
                                @endif
                                <td>{{ $trans['code'] }}</td>
                                <td>{{ $trans['provider_transaction_id'] }}</td>
                                <td>{{ $trans['recipient_name'] }}</td>
                                <td>{{ ucfirst($trans['transaction']['type'] ?? "") }}</td>
                                <td>{{ $trans['provider']['name'] ?? "" }}</td>
                                <td>{{ @$trans['bank']['name'] ?? ""}}</td>
                                <td>{{ number_format($trans['amount'],0,',','.') }}</td>
                                <td>{{ number_format($trans['fee'],0,',','.') }}</td>
                                <td>{{ $trans['created_at_formatted'] }}</td>
                                <td><span class="badge @if($trans['status'] == 'PENDING')
                                        badge-warning
                                    @elseif($trans['status'] == 'SUCCESS')
                                        badge-success
                                    @else 
                                        badge-danger
                                    @endif
                                    ">{{ $trans['status'] }}</span></td>
                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="{{ route('money_transfer.transactions.detail', ['id' => $trans['id']] ) }}" class="dropdown-item"><i class="icon-search4"></i> @lang("cms.Detail")</a>
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
                        <span style="margin:auto;">{{ $total }}</span>
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

        .bg-default, .bg-default:focus {
            background-color: #383940;
            border: 1px solid white;
        }

        .btn-select-all:hover {
            background-color: white;
            border: 1px solid black;
            color: #000000;
        }

        .table {
            white-space: nowrap;
        }

        #searchclear {
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
    </style>
    @endpush

    @swal

    @push('scripts')
    <script>
        const exportAction = "{{ route('money_transfer.transactions.export') }}";
        const indexAction = "{{ route('money_transfer.transactions.index') }}";
    </script>
    
    <script src="{{ asset('assets/js/mt/function.js') }}"></script>
    <script src="{{ asset('assets/js/mt/transaction/function.js') }}"></script>
    <script src="{{ asset('assets/js/mt/transaction/core.js') }}"></script>

    @if ($filters['dates_by'] && ($filters['start_date'] || $filters['end_date']) )
        <script>
            $('.dates_by').css('display', 'block');
            $('#dates_by_submenu').css('display', 'block');
        </script>
    @else
        <script>
            $('.dates_by').css('display', 'none');
            $('#dates_by_submenu').css('display', 'none');    
        </script>
    @endif

    <script>
         $.ajax({url: "{{ route('money_transfer.json.providers.index') }}", success: async function(data){
            let providerSelected = "{{ implode(',', $filters['providers']) }}";
            providerSelected = providerSelected.split(',');

            await data.result.forEach((item, key) => {
                let checked = providerSelected.includes(item.code) ? ' checked ' : '';

                $('#provider-list').append('<li class="providers pl-3 submenu '
                    + '@if ($filters["providers"]) submenu-active @endif">'
                    + '<label class="dropdown-item"><input type="checkbox" class="cb-providers" name="providers[]" value="'+item.code+'"'
                    +  checked
                    + 'data-code="'+item.code+'">&nbsp;&nbsp; '+item.name+'</label></li>');
            });

            selectingCodes();

            setDisabledActionButton();
        }});

        // TODO Ajax for status counter
    </script>
    @endpush
</x-app-layout>
