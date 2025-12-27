<x-app-layout>
    <x-page.header :title="__('cms.Batch Group Detail')">
        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            <x-breadcrumb.link :link="route('money_transfer.transactions.batches.index')" :text="__('cms.Batch Group')"/>
            <x-breadcrumb.active>{{ __("cms.Batch Group Detail") }}</x-breadcrumb.active>
        </x-slot>
    </x-page.header>

    <x-page.content :title="__('cms.Transaction Batch')">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Batch ID")</label>
                    <div class="col-lg-8">
                        <input type="text" name="code" class="form-control" required="" value="{{ $transaction['code'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Tag")</label>
                    <div class="col-lg-8">
                        <input type="text" name="tag" class="form-control" required="" value="{{ $transaction['entity_type'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Username")</label>
                    <div class="col-lg-8">
                        <input type="text" name="username" class="form-control" required="" value="{{ @$transaction['entity']['name'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Amount")</label>
                    <div class="col-lg-8">
                        <input type="text" name="amount" class="form-control" required="" value="{{ number_format($transaction['amount'], 0, ',', '.') }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Disbursement Fee")</label>
                    <div class="col-lg-8">
                        <input type="text" name="fee" class="form-control" required="" value="{{ number_format($transaction['fee'], 0, ',', '.') }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Total")</label>
                    <div class="col-lg-8">
                        <input type="text" name="total" class="form-control" required="" value="{{ number_format($transaction['total'], 0, ',', '.') }}" readonly>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Transaction Total")</label>
                    <div class="col-lg-8">
                        <input type="text" name="transaction_total" class="form-control" required="" value="{{ $transactionTotal }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Status")</label>
                    <div class="col-lg-8">
                        <input type="text" name="status" class="form-control" required="" value="{{ $transaction['status'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Created By")</label>
                    <div class="col-lg-8">
                        <input type="text" name="created_by" class="form-control" required="" value="{{ $transaction['created_by'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Created At")</label>
                    <div class="col-lg-8">
                        <input type="text" name="created_at" class="form-control" required="" value="{{ date_format(date_create($transaction['created_at']), 'd-m-Y H:i:s') }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Updated At")</label>
                    <div class="col-lg-8">
                        <input type="text" name="updated_at" class="form-control" required="" value="{{ date_format(date_create($transaction['updated_at']), 'd-m-Y H:i:s') }}" readonly>
                    </div>
                </div>
            </div>
        </div>   

        <hr>

        @foreach ($transaction['item_groups'] as $group)
            <h4 class="mb-4">{{ __('cms.Group Transaction') }}</h4>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">@lang("cms.Group ID")</label>
                        <div class="col-lg-8">
                            <x-form.readonly-input-or-link 
                                item="{{ $group['code'] ?? '' }}" 
                                name="code" 
                                url="{{ route('money_transfer.transactions.groups.show', ['code' => $group['code']]) }}" /> 
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">@lang("cms.Provider")</label>
                        <div class="col-lg-8">
                            <input type="text" name="provider_name" class="form-control" required="" value="{{ $group['provider']['name'] }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">@lang("cms.Amount")</label>
                        <div class="col-lg-8">
                            <input type="text" name="amount" class="form-control" required="" value="{{ number_format($group['amount'], 0, ',', '.') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">@lang("cms.Top Up ID")</label>
                        <div class="col-lg-8">
                            <x-form.readonly-input-or-link 
                                item="{{ $group['deposit']['code'] ?? '' }}" 
                                name="top_up_id" 
                                url="{{ route('money_transfer.provider_deposits.detail', ['id' => $group['deposit']['id'] ?? 0]) }}" /> 
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">@lang("cms.Transaction Total")</label>
                        <div class="col-lg-8">
                            <input type="text" name="transaction_total" class="form-control" required="" value="{{ $group['item_count'] }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">@lang("cms.Status")</label>
                        <div class="col-lg-8">
                            <input type="text" name="status" class="form-control" required="" value="{{ $transaction['status'] }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">@lang("cms.Created At")</label>
                        <div class="col-lg-8">
                            <input type="text" name="created_at" class="form-control" required="" value="{{ date_format(date_create($transaction['created_at']), 'd-m-Y H:i:s') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">@lang("cms.Updated At")</label>
                        <div class="col-lg-8">
                            <input type="text" name="updated_at" class="form-control" required="" value="{{ date_format(date_create($transaction['updated_at']), 'd-m-Y H:i:s') }}" readonly>
                        </div>
                    </div>
                </div>
            </div> 
            <hr>
        @endforeach

        <h4 class="mb-4">{{ __('cms.Transaction List') }}</h4>

        <form action="{{ route('money_transfer.transactions.batches.show', ['code' => $transaction['code']]) }}" id="form">
            <input type="hidden" name="export" value="{{ $filters['export'] }}" id="export">

            <div class="row mb-3">
                
                <div class="col d-md-flex d-lg-flex" style="gap: 5px;">
                    <div class="input-group" style="max-width: 400px;">
                        <select name="search_by" id="search_by" class="form-control">
                            <option value="code" @if ($filters['search_by'] == 'code') selected @endif>Code</option>
                            <option value="provider_transaction_id" @if ($filters['search_by'] == 'provider_transaction_id') selected @endif>Provide Transaction Id</option>
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
                                        <button type="submit" class="btn btn-primary" id="btn-apply-filter">Apply</button>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <label class="dropdown-item"><input type="checkbox" id="providers"
                                @if ($filters['providers'])
                                    checked
                                @endif
                                >&nbsp;&nbsp; Provider</label>
                            </li>
                            <li class="providers pl-3 submenu
                            @if ($filters['providers'])
                                submenu-active
                            @endif
                            ">
                                <p class="dropdown-item">
                                    <button id="providers_all" type="button" class="btn border border-white rounded btn-select-all" style="font-size: x-small;">Select All</button>
                                </p>
                            </li>
                            @foreach ($providers as $provider)
                                <li class="providers pl-3 submenu
                                @if ($filters['providers'])
                                    submenu-active
                                @endif
                                ">
                                    <label class="dropdown-item"><input type="checkbox" name="providers[]" value="{{ $provider['code'] }}"
                                    @if (in_array($provider['code'], $filters['providers']))
                                        checked
                                    @endif
                                    >&nbsp;&nbsp; {{ $provider['name'] }}</label>
                                </li>
                            @endforeach
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

                    @if ($filters['providers'])
                        <span class="badge badge-info">{{ 'Provider: ' . implode(', ', $filters['providers']) }} <span class="filter-clear" data-filter="providers" style="cursor: pointer;">X</span> </span>
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
                            <td>{{ $item['transaction']['entity_type'] ?? ''}}</td>
                            <td>{{ $item['bank']['name'] ?? ''}}</td>
                            <td>{{ number_format($item['amount'], 0, ',', '.') }}</td>
                            <td>{{ $item['transaction']['created_by'] ?? ''}}</td>
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
                                            <a href="{{ route('money_transfer.transactions.items.show', ['code' => $item['code']]) . '?from=batch' }}" class="dropdown-item">
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
                        <span style="margin:auto;">{{ $transactionTotal }}</span>
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

        #searchinput {
            width: 200px;
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

        .table th {
            white-space: nowrap;
        }
    </style>
    @endpush

    @push('scripts')

    <script>
        function resetUrl()
        {
            if($('#form').prop('action') == "{{ route('money_transfer.transactions.batches.export', ['code' => $transaction['code']]) }}") {
                $('#export').val(0);
                $('#form').prop('action', "{{ route('money_transfer.transactions.batches.show', ['code' => $transaction['code']]) }}")
            }
        }

        $(document).on('click', '.page-content .dropdown-menu', function (e) {
            e.stopPropagation();
        });

        $('#perPageDropdown').change(function(e) {
            resetUrl()
            $('#form').submit()
        })

        $('#providers').on('change', function() {
            if($('#providers').is(':checked')) {
                $('.providers').css('display', 'block');
            } else {
                $('input[name="providers[]"]').prop('checked', false);
                $('.providers').css('display', 'none');
            }
        });

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

        $('#providers_all').on('click', function(e) {
            $('input[name="providers[]"]').prop('checked', true);
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
            
            $('#form').prop('action', "{{ route('money_transfer.transactions.batches.export', ['code' => $transaction['code']]) }}")

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

        $("#searchclear").click(function(){
            $("#searchinput").val('');
        });
    </script>

    @endpush

</x-app-layout>
