@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang('cms.List Delete Merchant Branch')</h4>
            </div>
            @if(session()->has('message'))
            <div style="display: flex; justify-content: flex-end; width: 80%; z-index:9999; border-radius:0px; margin-bottom: 5vh;">
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session()->get('message') }}
                </div>
            </div>
            @endif
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        @lang('cms.Home')</a>
                    <a href="{{ route('yukk_co.merchant.pten.list') }}" class="breadcrumb-item">@lang('cms.QRIS (PTEN) Menu')</a>
                    <span class="breadcrumb-item active">@lang('cms.List Delete Merchant Branch')</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card mt-4">
        <div class="card-header">
            <form action="{{ route('yukk_co.merchant.pten.delete.list') }}" method="GET">
                <div class="form-group row">
                    <div class="form-group mx-2">
                        <select name="type" class="form-control">
                            <option value="">-- Pilih Kolom --</option>
                            <option value="name" {{ ($filter == 'name') ? 'selected' : ''}}>Branch Name</option>
                            <option value="merchant" {{ ($filter == 'merchant') ? 'selected' : ''}}>Merchant Name</option>
                            <option value="mid" {{ ($filter == 'mid') ? 'selected' : ''}}>MID</option>
                            <option value="mpan" {{ ($filter == 'mpan') ? 'selected' : ''}}>MPAN</option>
                            <option value="nmid" {{ ($filter == 'nmid') ? 'selected' : ''}}>NMID</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="@lang("cms.Search")">
                    </div>
                    <div class="form-group mx-2">
                        <button class="btn btn-primary form-control" type="submit"><i class="icon-search4"></i></button>
                    </div>

                    <div class="my-sm-auto ml-sm-auto mb-3">
                        <button class="btn btn-primary form-control" type="button" name="import_id" data-toggle="modal" data-target="#modal-import">
                            <iclass="icon-file-upload"></i> @lang('cms.Import By ID')
                        </button>
                    </div>

                    <div class="my-sm-auto ml-2 mb-3">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <label>
                            @lang("cms.Per page")&nbsp;
                            <select name="per_page" onchange='if(this.value != 0) { this.form.submit(); }'>
                                <option @if($per_page == 10) selected @endif>10</option>
                                <option @if($per_page == 25) selected @endif>25</option>
                                <option @if($per_page == 50) selected @endif>50</option>
                                <option @if($per_page == 100) selected @endif>100</option>
                            </select>
                        </label>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body">
            <form method="post" id="form_table" action="{{ route("yukk_co.merchant.pten.delete.pten") }}">
                @csrf
                <input type="hidden" name="keterangan" id="value_keterangan">
                <input type="hidden" name="ids" id="ids">
                <table class="table table-bordered table-striped dataTable" id="dataTable">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" name="select-all" id="select-all" />
                            </th>
                            <th>@lang('cms.Branch Name')</th>
                            <th>@lang('cms.Merchant Name')</th>
                            <th>@lang('cms.MID')</th>
                            <th>@lang('cms.MPAN')</th>
                            <th>@lang('cms.NMID')</th>
                            <th>@lang('cms.Status')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($merchant_branch_list as $index => $merchant_branch)
                        <tr>
                            <td class="td-checkbox">
                                @if(in_array($merchant_branch['id'], $activity_delete) == false)
                                    <input class="checkbox" type="checkbox" name="branch_id[{{ $index }}]" value="{{ $merchant_branch['id'] }}">
                                @endif
                            </td>
                            <td>{{ $merchant_branch['merchant_branch_name_pten_50'] }}</td>
                            <td>{{ $merchant_branch['merchant']['name'] }}</td>
                            <td>{{ $merchant_branch['mid'] }}</td>
                            <td>{{ $merchant_branch['mpan'] }}</td>
                            <td>{{ $merchant_branch['nmid_pten'] }}</td>
                            <td>
                                @php
                                    if($merchant_branch['status_pten'] == 'APPROVED'){
                                        $class_badge = 'badge-success';
                                    }else if($merchant_branch['status_pten'] == 'REJECTED_DELETE_PTEN'){
                                        $class_badge = 'badge-danger';
                                    }else{
                                        $class_badge = 'badge-info';
                                    }
                                @endphp
                                <span class="badge {{ $class_badge }} badge-pill ml-auto">
                                    {{ $merchant_branch['status_pten']}}
                                </span>
                            </td>
                            <input type="hidden" name="qris[{{ $index }}]" value="{{ $merchant_branch['qr_static_path'] }}">
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </form>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="pagination pagination-flat justify-content-end">
                        @php($plus_minus_range = 3)
                        @if ($current_page == 1)
                            <li class="page-item disabled"><a href="#" class="page-link"><i class="icon-arrow-left12"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route("yukk_co.merchant.pten.delete.list", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
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
                                    <a href="{{ route("yukk_co.merchant.pten.delete.list", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
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
                                <a href="{{ route("yukk_co.merchant.pten.delete.list", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">
                                    <i class="icon-arrow-right13"></i>
                                </a>
                            </li>
                        @endif

                    </ul>
                </div>
            </div>
        </div>

        <div class="mx-3 my-3">
            <button type="button" id="modal_delete" class="btn btn-block btn-danger mx-auto">@lang('cms.Delete Data PTEN')</button>
        </div>

        <div class="modal fade" id="modal-delete" tabindex="-1" role="dialog"
             aria-labelledby="demoModalLabel" aria-hidden="true">
            <form id="modal-confirm-form" data-attribute-action="" class="modal-dialog" role="document" method="post" action="">
                <input id="modal-confirm-input" name="ids" type="hidden" value="">
                <input id="modal-confirm-approveOrReject" name="approveOrReject" type="hidden" value="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="demoModalLabel">Request Delete QRIS</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mx-auto">
                            <label class="col-md-3">Deletion Reason</label>
                            <textarea name="" id="keterangan_delete" class="form-control col-md-8" rows="4"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="btnConfirm">Delete</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="modal fade" id="modal-import" tabindex="-1" role="dialog"
             aria-labelledby="demoModalLabel" aria-hidden="true">
            <form id="" data-attribute-action="" class="modal-dialog" role="document" method="post" action="{{ route('yukk_co.merchant.pten.delete.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="demoModalLabel">Add Merchant Branch</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mb-3">
                        <div class="row mx-auto">
                            <label class="col-md-4">Import Merchant Branch</label>
                            <input type="file" name="import" id="import_merchant_branch" class="form-control col-md-8" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
                        </div>
                        <div class="row mx-auto mt-2">
                            <a href="{{ url('template/template_bulk_delete_merchant.xlsx') }}" class="col-md-8 offset-4">Download Template</a>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn btn-primary" >Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".dataTable").DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "searching": false,
            });

            $('#select-all').click(function(event) {
                if(this.checked) {
                    $(':checkbox').each(function() {
                        this.checked = true;
                    });
                } else {
                    $(':checkbox').each(function() {
                        this.checked = false;
                    });
                }
            });

            $("#btnConfirm").click( function(){
                var value_keterangan = $('#keterangan_delete').val();
                var textbox = document.getElementById("keterangan_delete");
                
                if(value_keterangan != ''){
                    if(textbox.value.length < 5){
                        alert('deletion reason minimum 5 characters!');
                        textbox.focus();
                    }else if(textbox.value.length > 50){
                        alert('deletion reason must not exceed 50 characters!');
                        textbox.focus();
                    }else{
                        $('#value_keterangan').val(value_keterangan);
                        $('form#form_table').submit();
                    }
                }else{
                    alert('deletion reason must be filled!');
                    textbox.focus();
                }
                
            });

            $('#modal_delete').click(function(event) {
                var checkbox_id = $(".checkbox").is(":checked");
                var myTable = $('#dataTable').dataTable();
                var array_value = [];

                myTable.$(".checkbox:checked", {"page": "all"}).each(function() {
                    array_value.push(this.value);
                });
                
                if(array_value.length > 0){
                    $('#modal-delete').modal('show');
                    $('#ids').val(array_value);
                }else{
                    alert('Please select at least one row');
                }
            });
        });

    </script>
@endsection
