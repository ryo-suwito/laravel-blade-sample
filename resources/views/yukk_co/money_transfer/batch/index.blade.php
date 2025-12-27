<x-app-layout>
    <x-page.header :title="__('cms.Batch Group')">
        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            <x-breadcrumb.active>{{ __("cms.Batch Group") }}</x-breadcrumb.active>
        </x-slot>
    </x-page.header>

    <x-page.content :title="__('cms.Batch Group')">
        <form action="{{ route('money_transfer.transactions.batches.index') }}" id="form">

            <div class="row mb-3">
                <div class="col d-md-flex d-lg-flex" style="gap: 5px;">
                    <div class="input-group" style="max-width: 400px;">
                        <select name="search_by" id="search_by" class="form-control">
                            <option value="code" @if ($filters['search_by'] == 'code') selected @endif>Batch ID</option>
                            <option value="entity_name" @if ($filters['search_by'] == 'entity_name') selected @endif>User Name</option>
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
                            <li class="dates_by pl-3 submenu
                            @if ($filters['dates_by'])
                                submenu-active
                            @endif
                            ">
                                <label class="dropdown-item"><input type="radio" name="dates_by" value="updated_at"
                                @if ($filters['dates_by'] == 'updated_at')
                                    checked
                                @endif
                                >&nbsp;&nbsp; Updated At</label>
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
                        </ul>
                    </div>

                    <button class="btn btn-primary form-control" style="max-width: 150px;" type="submit" id="btn-search"><i class="icon-search4"></i> @lang("cms.Search")</button>
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

                    @if ($filters['status'])
                        <span class="badge badge-info">{{ 'Status: ' . implode(', ', $filters['status']) }} <span class="filter-clear" data-filter="status" style="cursor: pointer;">X</span> </span>
                    @endif

                    @if ($filters['tags'])
                        <span class="badge badge-info">{{ 'Tag: ' . implode(', ', $filters['tags']) }} <span class="filter-clear" data-filter="tags" style="cursor: pointer;">X</span> </span>
                    @endif
                </div>
                <p id="delete_all_filters" style="cursor: pointer;"><u>Delete all filters</u></p>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ __("cms.Batch ID") }}</th>
                            <th>{{ __("cms.Tag") }}</th>
                            <th>{{ __("cms.Amount") }}</th>
                            <th>{{ __("cms.Disbursement Fee") }}</th>
                            <th>{{ __("cms.Total") }}</th>
                            <th>{{ __("cms.Username") }}</th>
                            <th>{{ __("cms.Created At") }}</th>
                            <th>{{ __("cms.Updated At") }}</th>
                            <th>{{ __("cms.Status") }}</th>
                            <th>{{ __("cms.Actions") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($transactions) == 0)
                            <tr class="text-center">
                                <td colspan="10"> Data Not Found</td>
                            </tr>
                        @endif
                        @foreach($transactions as $item)
                        <tr>
                            <td>{{ $item['code'] }}</td>
                            <td>{{ $item['entity_type'] }}</td>
                            <td>{{ number_format($item['amount'], 0, ',', '.') }}</td>
                            <td>{{ number_format($item['fee'], 0, ',', '.') }}</td>
                            <td>{{ number_format($item['total'], 0, ',', '.') }}</td>
                            <td>{{ $item['entity']['name'] ?? '' }}</td>
                            <td>{{ date_format(date_create($item['created_at']), 'd-m-Y H:i:s') }}</td>
                            <td>{{ date_format(date_create($item['updated_at']), 'd-m-Y H:i:s') }}</td>
                            <td>
                                <span class="badge
                                    @if ($item['status'] == 'SUCCESS')
                                        bg-success
                                    @else
                                        bg-secondary
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
                                            <a href="{{ route('money_transfer.transactions.batches.show', ['code' => $item['code']]) }}" class="dropdown-item">
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
        $(document).on('click', '.page-content .dropdown-menu', function (e) {
            e.stopPropagation();
        });

        $('#perPageDropdown').change(function(e) {
            $('#form').submit()
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

        $('#tags').on('change', function() {
            if($('#tags').is(':checked')) {
                $('.tags').css('display', 'block');
            } else {
                $('input[name="tags[]"]').prop('checked', false);
                $('.tags').css('display', 'none');
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

        $('#date_filter').on('change', function() {
            $('#dates_by_submenu').css('display', 'block');
        });

        $('#status_all').on('click', function(e) {
            $('input[name="status[]"]').prop('checked', true);
        });

        $('#tags_all').on('click', function(e) {
            $('input[name="tags[]"]').prop('checked', true);
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

            $('#form').submit()
        });

        $('#delete_all_filters').on('click', function(e) {
            window.location.href = $('.btn-reset').attr('href');
        });

        $('#btn-apply-filter').on('click', function(e) {
            $(this).text("Loading...");
            $(this).prop('disabled', true);

            $('#form').submit()
        })

        $('#btn-search').on('click', function(e) {
            $(this).text("Loading...");
            $(this).prop('disabled', true);

            $('#form').submit()
        })

        $("#searchclear").click(function(){
            $("#searchinput").val('');
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
    @endpush

</x-app-layout>
