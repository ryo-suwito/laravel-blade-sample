@extends('layouts.master')

@section('header')
<!-- Page header -->
<style>
    .bootstrap-select .no-results {
        background: #2c2d33 !important;
    }

    .bootstrap-select .dropdown-menu.inner {
        max-height: 300px;
    }

    div.dropdown-menu.show {
        max-width: 240px !important;
        max-height: 364px !important;
    }

    a.dropdown-item.selected {
        color: #65bbf9 !important;
    }

    li > a > span.text {
        white-space: break-spaces;
        margin-right: 10px !important;
    }

    .bootstrap-select.show-tick .dropdown-menu .selected span.check-mark {
        right: 15px !important;
        top: 25% !important;
    }
</style>
<div class="page-header page-header-light">
    <div class="page-header-content d-sm-flex">
        <div class="page-title">
            <h4>Users</h4>
        </div>

        <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">

        </div>
    </div>

    <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                <span class="breadcrumb-item active">user</span>
            </div>

        </div>

    </div>
</div>
<!-- /page header -->
<div class="modal" id="addPartnerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="max-width: 900px">
        <div class="modal-content">
            <form action="{{ route('money_transfer.partners.add') }}" class="form" method="post" id="createUserForm">
                @csrf
                <div class="modal-header mx-auto">
                    <h4>Add Users</h4>
                </div>
                <div id="selectedPartners">

                </div>
                <div id="selectedBeneficiaries">

                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="pl-2 mr-2">
                            <button id="btnAll" type="button" class="btn btn-outline-secondary filter active">
                                All
                            </button>
                        </div>
                        <div class="mr-2">
                            <button id="btnPartner" type="button" data-tag="PARTNER" class="btn btn-outline-secondary filter">
                                Partner
                            </button>
                        </div>
                        <div class="mr-2">
                            <button id="btnBenef" type="button" data-tag="BENEFICIARY" class="btn btn-outline-secondary filter">
                                Beneficiary
                            </button>
                        </div>
                    </div>

                    <div class="row my-2">
                        <div class="col-1 d-flex align-content-end flex-wrap">
                            <label for="">Search</label>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Name" name="search" id="entitySearch">
                        </div>
                    </div>

                    <!-- Tabs content -->
                    <div class="table-responsive" id="entityTable" style="max-height: 350px">
                        <table class="table table-bordered table-striped dataTableNonAssigned">
                            <thead>
                                <tr>
                                    <th class="w-10">@lang("cms.Select")</th>
                                    <th>User Name</th>
                                    <th>Tag</th>
                                    <th>@lang("cms.Short Description")</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($non_assigned_entities as $key => $nae)
                                    <tr>
                                        <td style="width:  8.33%">
                                            <input type="checkbox" class="entity" 
                                                data-id="{{ $nae['id'] }}" data-tag="{{ $nae['tag'] }}" value="{{ $nae['id'] }}">
                                        </td>
                                        <td>{{ $nae["name"] }}</td>
                                        <td>{{ ucwords(strtolower($nae["tag"])) }}</td>
                                        <td>{{ $nae["name"] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Tabs content -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><li class="fas fa-times-circle"></li> Cancel</button>
                    <button type="submit" class="btn btn-success"><li class="icon-floppy-disk"></li> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-sm-flex" >
            <div>
                <h4>Users</h4>
            </div>
            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                @hasaccess('MONEY_TRANSFER.PARTNER_SETTINGS_CREATE')
                    <button type="button" class="btn btn-primary w-100 w-sm-auto" data-toggle="modal" data-target="#addPartnerModal"><i class="fas fa-plus"></i> Add</button>
                @endhasaccess
            </div>
        </div>

        <div class="card-body">
            <div>
                <form action="" method="GET">
                    <div class="row">
                        <div class="col-5">
                            <div class="row">
                                <div class="col-2 d-flex align-content-end flex-wrap">
                                    <label for="">Search</label>
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control" placeholder="User Name" name="search" value="{{ request()->get('search') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="row">
                                <div class="col-2 d-flex align-content-end flex-wrap">
                                    <label for="">Tag</label>
                                </div>
                                <div class="col">
                                    <select class="form-control" name="tag" id="tag">
                                        <option value="">All</option>
                                        <option value="PARTNER" 
                                        @if (request()->get('tag') == 'PARTNER')
                                            selected
                                        @endif >Partner</option>
                                        <option value="BENEFICIARY"
                                        @if (request()->get('tag') == 'BENEFICIARY')
                                            selected
                                        @endif 
                                        >Beneficiary</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <button type="submit" class="btn btn-primary form-control">Search</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped dataTableAssigned mb-4">
                    <thead>
                    <tr>
                        <th>@lang("cms.Name")</th>
                        <th>Tag</th>
                        <th>@lang("cms.Short Description")</th>
                        <th>@lang("cms.Active")</th>
                        <th>@lang("cms.Actions")</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($settings as $s)
                            <tr>
                                <td>{{ $s["name"] }}</td>
                                <td>{{ ucwords(strtolower($s["tag"])) }}</td>
                                <td>{{ $s["name"] ?? "-" }}</td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="switch-{{ $s["name"] }}" 
                                        @if($s["active"] ?? 0)
                                            checked 
                                        @endif
                                        disabled>
                                        <label class="custom-control-label" for="switch-{{ $s["name"] }}"></label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="{{ route('money_transfer.partners.edit', ['id' => $s["id"]]) . "?tag=" . strtoupper($s["tag"]) }}" class="dropdown-item"><i class="icon-cog"></i> @lang("cms.Setting")</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-lg-12">
                    <div class="float-left">
                        <div class="d-flex">
                            <span class="mr-2" style="margin:auto;">Total </span>
                            <span style="margin:auto;">{{ $total }}</span>
                        </div>
                    </div>
                    <ul class="pagination pagination-flat justify-content-end">
                        @php($plus_minus_range = 3)
                        @if ($current_page == 1)
                            <li class="page-item disabled"><a href="#" class="page-link"><i class="icon-arrow-left12"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route("money_transfer.partners.index", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
                            </li>
                        @endif
                        @if ($current_page - $plus_minus_range > 1)
                            <li class="page-item disabled"><a href="#" class="page-link">...</a></li>
                        @endif
                        @for ($i = max(1, $current_page - $plus_minus_range); $i <= min($current_page + $plus_minus_range, $last_page); $i++)
                            @if ($i == $current_page)
                                <li class="page-item active"><a href="#" class="page-link">{{ $i }}</a></li>
                            @else
                                <li class="page-item">
                                    <a href="{{ route("money_transfer.partners.index", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
                                </li>
                            @endif
                        @endfor
                        @if ($current_page + $plus_minus_range < $last_page)
                            <li class="page-item disabled"><a href="#" class="page-link">...</a></li>
                        @endif
                        @if ($current_page == $last_page)
                            <li class="page-item disabled"><a href="#" class="page-link"><i class="icon-arrow-right13"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route("money_transfer.partners.index", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">
                                    <i class="icon-arrow-right13"></i>
                                </a>
                            </li>
                        @endif

                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>

        function isChecked(tag, dataId)
        {
            return $('#' + (tag == "Partner" ? 'partner-' : 'benef-') + dataId).length ? "checked" : "" ;
        }

        function checkingEvent()
        {
            $('.entity').on("change", function() {
                if($(this).is(':checked')) {
                    if ($(this).data('tag') == 'BENEFICIARY') {
                        $('#selectedBeneficiaries').append('<input type="hidden" id="benef-'+$(this).data('id')+'" name="selectedBeneficiaries[]" value="'+$(this).data('id')+'">')
                    } else {
                        $('#selectedPartners').append('<input type="hidden" id="partner-'+$(this).data('id')+'" name="selectedPartners[]" value="'+$(this).data('id')+'">')
                    }
                } else {
                    if ($(this).data('tag') == 'BENEFICIARY') {
                        $('#benef-'+$(this).data('id')).remove();
                    } else {
                        $('#partner-'+$(this).data('id')).remove();
                    }
                }
            })
        }

        function entityFilter(tagId) {
            $('#entityTable tbody').empty()
            $('#entityTable tbody').append("<tr><td colspan=\"4\">Loading...</td></tr>")

            $.ajax({
                url: "{{ route('money_transfer.json.entities.index') }}",
                method: "GET",
                data: {
                    "_token": '{{ csrf_token() }}',
                    "tag": $("#" + tagId).data("tag"),
                    "search": $('#entitySearch').val(),
                },
                success: function(data) {
                    $('#entityTable tbody').empty()
                    $.each(data, function (index, value) {
                        $('#entityTable tbody').append("<tr>"
                            + "<td style=\"width:  8.33%\"><input type=\"checkbox\" class=\"entity\" "
                            + isChecked(value.tag, value.id)
                            + " data-id=\""+ value.id +"\" data-tag=\""+ value.tag +"\" value=\""+ value.id +"\"></td>"
                            + "<td>"+ value.name +"</td>"
                            + "<td>"+ value.tag +"</td>"
                            + "<td>"+ value.name +"</td>"
                            + "</tr>")
                    });

                    if(data.length == 0) {
                        $('#entityTable tbody').append("<tr>"
                            + "<td colspan=\"4\">Data not found</td>"
                            + "</tr>")
                    }

                    checkingEvent()
                },
            });
        }

        function debounce(func, wait, immediate) {
            var timeout;
            return function() {
                var context = this, args = arguments;
                var later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                var callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        };

        $(document).ready(function() {

            $(".dataTableAssigned").DataTable({
                "paging": false,
                "ordering": true,
                "info": false,
                "searching": false,
                "columnDefs": [
                    { "orderable": false, "targets": 3 },
                    { "orderable": false, "targets": 4 },
                ]
            });

            $(".dataTableNonAssigned").DataTable({
                "paging": false,
                "ordering": true,
                "info": false,
                "searching": false,
                "columnDefs": [
                    { "orderable": false, "targets": 0 },
                ]
            });

            checkingEvent()

            $("#date_range").daterangepicker({
                parentEl: '.content-inner',
                locale: {
                    format: 'DD-MMM-YYYY',
                    firstDay: 1,
                },
            });

            $(".pagination .page-item.active").click(function(e) {
                e.preventDefault();
            });

            $('.form').submit(function() {
                $(this).find(':button[type=submit]').prop('disabled', true);
                $(this).find(':button[type=submit]').html('Loading..');
            })

            $('.filter').on("click", function() {
                $('.filter').removeClass('active');
                $(this).addClass('active');

                entityFilter(this.id);
            })

            $('#entitySearch').on("keyup", (debounce(function(){
                entityFilter($('.filter.active').attr('id'));
            },500)));

            $('#createUserForm').on('keyup keypress', function(e) {
                var keyCode = e.keyCode || e.which;
                if (keyCode === 13) { 
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
@endsection
