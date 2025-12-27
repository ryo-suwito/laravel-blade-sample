<x-app-layout>
    <x-page.header :title="__('cms.Proxy')">
        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            <x-breadcrumb.active>{{ __("cms.Proxy") }}</x-breadcrumb.active>
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

        <div class="row ml-1">
            <a href="{{ route('money_transfer.transfer_proxies.index') }}" class="btn @if(in_array(null, $filters['status'])) btn-secondary @else btn-outline-secondary @endif mr-2">
                All <span class="badge badge-success ml-2 status-all">0</span>
            </a>
            <a href="{{ route('money_transfer.transfer_proxies.index').'?status=SUCCESS' }}" class="btn @if(in_array('SUCCESS', $filters['status'])) btn-secondary @else btn-outline-secondary @endif mr-2">
                Success <span class="badge badge-success ml-2 status-success">0</span>
            </a>
            <a href="{{ route('money_transfer.transfer_proxies.index').'?status=PENDING' }}" class="btn @if(in_array('PENDING', $filters['status'])) btn-secondary @else btn-outline-secondary @endif mr-2">
                Pending <span class="badge badge-success ml-2 status-pending">0</span>
            </a>
            <a href="{{ route('money_transfer.transfer_proxies.index').'?status=FAILED' }}" class="btn @if(in_array('FAILED', $filters['status'])) btn-secondary @else btn-outline-secondary @endif mr-2">
                Failed <span class="badge badge-success ml-2 status-failed">0</span>
            </a>
        </div>
        <hr>

        <form action="{{ route('money_transfer.transfer_proxies.index') }}" id="form">
            <input type="hidden" name="status" value="{{ implode(',', $filters['status']) }}">
            <input type="hidden" name="export" value="{{ $filters['export'] }}" id="export">
            
            <div class="row mb-3">
                <div class="col d-md-flex d-lg-flex" style="gap: 5px;">
                    <div class="input-group" style="max-width: 400px;">
                        <select name="search_by" id="search_by" class="form-control">
                            <option value="code" @if (request()->get('search_by') == 'code') selected @endif>Code</option>
                            <option value="group_code" @if (request()->get('search_by') == 'group_code') selected @endif>Group</option>
                            <option value="ref_code" @if (request()->get('search_by') == 'ref_code') selected @endif>Ref Code</option>
                            <option value="provider_trx_code" @if (request()->get('search_by') == 'provider_trx_code') selected @endif>Provider Transaction Code</option>
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
                                        <a href="{{ request()->url() . '?search_by=' . $filters['search_by'] . '&search=' . $filters['search'] . '&status=' . implode(',', $filters['status']) }}" class="btn btn-danger btn-reset">Reset</a>
                                    </div>
                                    <div class="col">
                                        <button type="button" class="btn btn-primary" id="btn-apply-filter">Apply</button>
                                    </div>
                                </div>
                            </li>
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
                                <label class="dropdown-item">
                                    <input type="radio" name="dates_by" id="dates_by_created_at" value="created_at" checked>
                                    &nbsp;&nbsp; Created At
                                </label>
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
                            <li>
                                <label class="dropdown-item"><input type="checkbox" id="categories"
                                @if ($filters['categories'])
                                    checked
                                @endif
                                >&nbsp;&nbsp; Category</label>
                            </li>
                            <li class="categories pl-3 submenu
                            @if ($filters['categories'])
                                submenu-active
                            @endif
                            ">
                                <p class="dropdown-item">
                                    <button id="categories_all" type="button" class="btn border border-white rounded btn-select-all" style="font-size: x-small;">Select All</button>
                                </p>
                            </li>
                            <li class="categories pl-3 submenu
                            @if ($filters['categories'])
                                submenu-active
                            @endif
                            ">
                                <label class="dropdown-item"><input type="checkbox" name="categories[]" value="POOL_TO_PARKING"
                                @if (in_array('POOL_TO_PARKING', $filters['categories']))
                                    checked
                                @endif
                                >&nbsp;&nbsp; POOL_TO_PARKING</label>
                            </li>
                            <li class="categories pl-3 submenu
                            @if ($filters['categories'])
                                submenu-active
                            @endif
                            ">
                                <label class="dropdown-item"><input type="checkbox" name="categories[]" value="PARKING_TO_PROVIDER"
                                @if (in_array('PARKING_TO_PROVIDER', $filters['categories']))
                                    checked
                                @endif
                                >&nbsp;&nbsp; PARKING_TO_PROVIDER</label>
                            </li>
                            <li class="categories pl-3 submenu
                            @if ($filters['categories'])
                                submenu-active
                            @endif
                            ">
                                <label class="dropdown-item"><input type="checkbox" name="categories[]" value="PARKING_TO_POOL"
                                @if (in_array('PARKING_TO_POOL', $filters['categories']))
                                    checked
                                @endif
                                >&nbsp;&nbsp; PARKING_TO_POOL</label>
                            </li>
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
                            {{ 'Dates: ' .date_format(date_create($filters['start_date']), 'd-m-Y H:i:s') . ' - ' . date_format(date_create($filters['end_date'] . ' 23:59:59'), 'd-m-Y H:i:s') }}
                            <span class="filter-clear" data-filter="dates" style="cursor: pointer;"> X</span>
                        </span>
                    @endif

                    @if ($filters['categories'])
                        <span class="badge badge-info">{{ 'Categories: ' . implode(', ', $filters['categories']) }} <span class="filter-clear" data-filter="categories" style="cursor: pointer;">X</span> </span>
                    @endif

                    @if ($filters['providers'])
                        <span class="badge badge-info">{{ 'Provider: ' . implode(', ', $filters['providers']) }} <span class="filter-clear" data-filter="providers" style="cursor: pointer;">X</span> </span>
                    @endif

                </div>
                <p id="delete_all_filters" style="cursor: pointer;"><u>Delete all filters</u></p>
            </div>
            @endif

            @php
                $totalItem = count($trxs);

                $isNeedBulkAction = ($filters['status'][0] ?? '') == 'FAILED'
                    && count($filters['providers']) == 1
                    && in_array($filters['providers'][0] ?? '', ['BCA', 'CIMB', 'DANA']) 
                    && $totalItem > 0;
            @endphp

            @hasaccess('MONEY_TRANSFER.TRANSFER_PROXIES.UPDATE')
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
                                data-action="{{ route('money_transfer.transfer_proxies.bulk_update') }}"
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
                                data-action="{{ route('money_transfer.transfer_proxies.bulk_update') }}"
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
                                data-action="{{ route('money_transfer.transfer_proxies.bulk_update') }}"
                                data-label="Mark As Success">
                                <i class="icon-spinner11 mr-2"></i> Bulk Mark As Success
                            </button>
                        </div>
                    @elseif ($filters['providers'][0] == 'CIMB')
                        <!-- TODO -->
                    @elseif ($filters['providers'][0] == 'DANA')
                        <!-- TODO -->
                    @endif
                @endif
            @endhasaccess

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="deposit_group_table">
                    <thead>
                        <tr>
                            @if ($isNeedBulkAction)
                            <th>
                                <input type="checkbox" id="checkedAll">
                            </th>
                            @endif
                            <th>{{ __("cms.Code") }}</th>
                            <th>{{ __("cms.Group Transaction") }}</th>
                            <th>{{ __("cms.Ref Code") }}</th>
                            <th>{{ __("cms.Provider Transaction Code") }}</th>
                            <th>{{ __("cms.Amount") }}</th>
                            <th>{{ __("cms.Category") }}</th>
                            <th>{{ __("cms.Provider") }}</th>
                            <th>{{ __("cms.Created At") }}</th>
                            <th>{{ __("cms.Status") }}</th>
                            <th>{{ __("cms.Actions") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($totalItem == 0)
                            <tr class="text-center">
                                <td colspan="10"> Data Not Found</td>
                            </tr>
                        @endif
                        @foreach($trxs as $trx)
                        <tr>
                            @if ($isNeedBulkAction)
                                <td>
                                    <input type="checkbox" id="cb-{{ $trx['code'] }}" data-code="{{ $trx['code'] }}" class="bulkAction">
                                </td>
                            @endif
                            <td>{{ $trx['code'] }}</td>
                            <td>{{ $trx['group']['code'] ?? ''}}</td>
                            <td>{{ $trx['ref']['code'] ?? ''}}</td>
                            <td>{{ $trx['provider_trx_code'] }}</td>
                            <td>{{ number_format($trx['amount'], 0, ',', '.') }}</td>
                            <td>{{ $trx['action'] }}</td>
                            <td>{{ $trx['group']['provider']['name'] ?? ''}}</td>
                            <td>{{ $trx['created_at_formatted'] }}</td>
                            <td>
                                <span class="badge
                                    @if ($trx['status'] == 'SUCCESS')
                                        bg-success
                                    @elseif (in_array($trx['status'], ['PENDING', 'ONGOING']))
                                        bg-secondary
                                    @else
                                        bg-danger
                                    @endif
                                ">{{ $trx['status'] }}</span>
                            </td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('money_transfer.transfer_proxies.show', ['code' => $trx['code']]) }}" class="dropdown-item">
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

        .bg-default, .bg-default:focus {
            background-color: #383940;
            border: 1px solid white;
        }

        .btn-select-all:hover {
            background-color: white;
            border: 1px solid black;
            color: #000000;
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

        .table {
            white-space: nowrap;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        const exportAction = "{{ route('money_transfer.transfer_proxies.export') }}";
        const indexAction = "{{ route('money_transfer.transfer_proxies.index') }}";
    </script>

    <script src="{{ asset('assets/js/mt/function.js') }}"></script>
    <script src="{{ asset('assets/js/mt/transfer-proxy/function.js') }}"></script>
    <script src="{{ asset('assets/js/mt/transfer-proxy/core.js') }}"></script>


    <script>
        $(document).on('click', '.page-content .dropdown-menu', function (e) {
            e.stopPropagation();
        });

        $('#perPageDropdown').change(function(e) {
            resetUrl();
            $('#form').submit();
        })

        $('#date_filter').on('change', function() {
            if($('#date_filter').is(':checked')) {
                $('.dates_by').css('display', 'block');
            } else {
                $('input[type="date"]').val('');
                $('input[name="dates_by"]').prop('checked', false);
                $('#dates_by_created_at').prop('checked', true);
                $('.dates_by').css('display', 'none');
            }
        });

        $('#date_filter').on('change', function() {
            $('#dates_by_submenu').css('display', 'block');
        });

        $('#categories').on('change', function() {
            if($('#categories').is(':checked')) {
                $('.categories').css('display', 'block');
            } else {
                $('input[name="categories[]"]').prop('checked', false);
                $('.categories').css('display', 'none');
            }
        });

        $('#categories_all').on('click', function(e) {
            $('input[name="categories[]"]').prop('checked', true);
        });

        
        $('#providers').on('change', function() {
            if($('#providers').is(':checked')) {
                $('.providers').css('display', 'block');
            } else {
                $('input[name="providers[]"]').prop('checked', false);
                $('.providers').css('display', 'none');
            }
        });

        $('#providers_all').on('click', function(e) {
            $('input[name="providers[]"]').prop('checked', true);
        });

        $('.filter-clear').on('click', function(e) {
            var filter = $(this).data('filter');

            if(filter == 'dates') {
                $('input[type="date"]').val('');
                $('input[name="dates_by"]').prop('checked', false);
                $('#dates_by_created_at').prop('checked', true);
                $('.dates_by').css('display', 'none');
            } else {
                $('input[name="' + filter + '[]"]').prop('checked', false);
                $('.' + filter).css('display', 'none');
            } 

            resetUrl();
            $('#form').submit();
        });

        $('#delete_all_filters').on('click', function(e) {
            window.location.href = $('.btn-reset').attr('href');
        });

        $('#btn-apply-filter').on('click', function(e) {
            if ($('#date_filter').is(':checked')
                && ($('input[name="start_date"]').val() == '' || $('input[name="end_date"]').val() == '')) {
                swal('error', 'Date columns not complete');

                return; 
            }

            if ($('#categories').is(':checked') && ! $('input[name="categories[]"]').is(':checked')) {
                swal('error', 'Choose at least one category');

                return; 
            }

            if ($('#providers').is(':checked') && ! $('input[name="providers[]"]').is(':checked')) {
                swal('error', 'Choose at least one provider');

                return; 
            }

            $(this).text("Loading...");
            $(this).prop('disabled', true);

            resetUrl();
            $('#form').submit()
        })

        $('#btn-search').on('click', function(e) {
            $(this).text("Loading...");
            $(this).prop('disabled', true);

            resetUrl();
            $('#form').submit()
        })

        $("#searchclear").click(function(){
            $("#searchinput").val('');
        });

        $('input[name="end_date"]').on('change', function(e) {
            const startDateEl = $('input[name="start_date"]');

            if (startDateEl.val() == '') {
                swal('warning', 'Choose start date first');

                $(this).val('');
            }

            const startDate = new Date(startDateEl.val());
            const endDate = new Date($(this).val());

            if (startDate > endDate) {
                swal('error', 'End date must be greater than start date');
                $(this).val('');
            }
        });

        $('input[name="start_date"]').on('change', function(e) {
            $('input[name="end_date"]').val('')
        });

        $('#btn-export').on('click', function(e) {
            $('#export').val(1);

            $('#form').prop('action', "{{ route('money_transfer.transfer_proxies.export') }}")

            $('#form').submit()

            swal('success', 'The export request is being processed please wait');
        });

    </script>

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
                const whitelistProviders = ['BCA', 'YUKK'];
                
                if (whitelistProviders.includes(item.code)) {
                    let checked = providerSelected.includes(item.code) ? ' checked ' : '';

                    $('#provider-list').append('<li class="providers pl-3 submenu '
                        + '@if ($filters["providers"]) submenu-active @endif">'
                        + '<label class="dropdown-item"><input type="checkbox" class="cb-providers" name="providers[]" value="'+item.code+'"'
                        +  checked
                        + 'data-code="'+item.code+'">&nbsp;&nbsp; '+item.name+'</label></li>');
                }
            });

            selectingCodes();

            setDisabledActionButton();
        }});

        $.ajax({url: "{{ route('money_transfer.json.transfer_proxies.status_counter') }}", success: function(data){
            let counterAll = 0;
            let status = data.result;
            
            Object.keys(status).forEach(key => {
                const value = status[key];
                $('.status-' + key.toLowerCase()).html(value);

                counterAll += value;
            });

            $('.status-all').html(counterAll);
        }});
    </script>
    @endpush

</x-app-layout>
