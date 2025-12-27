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
                <div class="row">
                    <div class="mx-2">
                        <select name="type" id="type" class="form-control">
                            <option value="">-- Pilih Kolom --</option>
                            <option value="error">Error Message</option>
                            <option value="status">Status PTEN</option>
                            <option value="name">Branch Name</option>
                            <option value="merchant">Merchant Name</option>
                            <option value="mid">MID</option>
                            <option value="mpan">MPAN</option>
                            <option value="nmid">NMID</option>
                        </select>
                    </div>
                    <div>
                        <input type="text" name="search" id="search" class="form-control" placeholder="@lang("cms.Search")">
                    </div>
                    <div class="mx-2">
                        <select name="status" id="status" class="form-control">
                            <option value="">-- Pilih Status --</option>
                            <option value="all">ALL</option>
                            <option value="ok">OK</option>
                            <option value="not">NOT OK</option>
                        </select>
                    </div>
                    <div id="button_csv"></div>

                    <div class="my-sm-auto ml-sm-auto mb-3">
                        <button class="btn btn-primary form-control" type="button" name="import_id" data-toggle="modal" data-target="#modal-import">
                            <iclass="icon-file-upload"></i> @lang('cms.Import By ID')
                        </button>
                    </div>
                    <div class="ml-2">
                        <button class="dropdown-item form-control" type="button" name="export_excel" id="export_excel"><i class="icon-file-download"></i> @lang('cms.Export to Excel')</button>
                    </div>
                </div>
                <div class="mt-2 ml-1"><span><b id="text_count"></b> data will be saved, from total <b>{{ count($merchant_branch_list) }}</b> data</span></div>
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
                            <th>@lang('cms.Status')</th>
                            <th>@lang('cms.Error Message')</th>
                            <th>@lang('cms.Branch ID')</th>
                            <th>@lang('cms.Branch Name')</th>
                            <th>@lang('cms.Merchant Name')</th>
                            <th>@lang('cms.MID')</th>
                            <th>@lang('cms.MPAN')</th>
                            <th>@lang('cms.NMID')</th>
                            <th>@lang('cms.Status PTEN')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($merchant_branch_list as $index => $merchant_branch)
                        <tr>
                            <td class="td-checkbox">
                                @if($merchant_branch['status_ok'] == 1)
                                    <input class="checkbox" type="checkbox" name="branch_id[{{ $index }}]" value="{{ $merchant_branch['id'] }}">
                                    
                                    @php
                                        $count_ok = $count_ok + 1;
                                    @endphp
                                @endif
                            </td>
                            <td>{{ ($merchant_branch['status_ok'] == 1) ? 'OK' : 'NOT OK'}}</td>
                            <td>{{ $merchant_branch['message'] }}</td>
                            <td>{{ $merchant_branch['id'] }}</td>
                            <td>{{ ($merchant_branch['merchant_id'] != '' && $merchant_branch['mid'] != '') ? $merchant_branch['merchant_branch_name_pten_50'] : $merchant_branch['name']}}</td>
                            <td>{{ ($merchant_branch['merchant_id'] != '' && $merchant_branch['mid'] != '') ? $merchant_branch['merchant']['name'] : ''}}</td>
                            <td>{{ ($merchant_branch['merchant_id'] != '' && $merchant_branch['mid'] != '') ? $merchant_branch['mid'] : ''}}</td>
                            <td>{{ ($merchant_branch['merchant_id'] != '' && $merchant_branch['mid'] != '') ? $merchant_branch['mpan'] : ''}}</td>
                            <td>{{ ($merchant_branch['merchant_id'] != '' && $merchant_branch['mid'] != '') ? $merchant_branch['nmid_pten'] : ''}}</td>
                            <td>
                                @php
                                    if($merchant_branch['merchant_id'] != '' && $merchant_branch['mid'] != ''){
                                        if($merchant_branch['status_pten'] == 'APPROVED'){
                                            $class_badge = 'badge-success';
                                        }else if($merchant_branch['status_pten'] == 'REJECTED_DELETE_PTEN'){
                                            $class_badge = 'badge-danger';
                                        }else{
                                            $class_badge = 'badge-info';
                                        }
                                    }else{
                                        $class_badge = '';
                                    }
                                @endphp
                                <span class="badge {{ $class_badge }} badge-pill ml-auto">
                                    {{ ($merchant_branch['merchant_id'] != '' && $merchant_branch['mid'] != '') ? $merchant_branch['status_pten'] : ''}}
                                </span>
                            </td>
                            <input type="hidden" name="qris[{{ $index }}]" value="{{ ($merchant_branch['merchant_id'] != '' && $merchant_branch['mid'] != '') ? $merchant_branch['qr_static_path'] : ''}}">
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <input type="hidden" id="count_ok" value="{{ $count_ok }}">
            </form>
        </div>

        <div class="mx-3 my-3">
            <button type="button" id="modal_delete" class="btn btn-block btn-danger mx-auto" {{ (count($merchant_branch_list) == 0) ? 'disabled' : ''}}>@lang('cms.Delete Data PTEN')</button>
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
            var count_ok = $("#count_ok").val();
            $("#text_count").text(count_ok);

            var table = $(".dataTable").DataTable({
                "paging": true,
                "ordering": false,
                "info": false,
                "searching": true,
                "pageLength": 25
            });

            $('#select-all').click(function(event) {
                var myTable = $('#dataTable').dataTable();
                
                if(this.checked) {
                    myTable.$(".checkbox", {"page": "all"}).each(function() {
                        this.checked = true;
                    });
                } else {
                    myTable.$(".checkbox", {"page": "all"}).each(function() {
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

            $('#dataTable_filter').hide();
            $('#search').on('keydown', function () {
                var type = $("#type").val();

                if(type == ''){
                    alert('Please select at column');
                    document.getElementById("type").focus();
                    document.getElementById("search").reset();
                }
            });
            $('#search').on('keyup', function () {
                var type = $("#type").val();
                var column = 0;

                if(type == 'error'){
                    column = 2;
                }else if(type == 'name'){
                    column = 4;
                }else if(type == 'merchant'){
                    column = 5;
                }else if(type == 'mid'){
                    column = 6;
                }else if(type == 'mpan'){
                    column = 7;
                }else if(type == 'nmid'){
                    column = 8;
                }else{
                    column = 9;
                }
                
                table.columns(column).search(this.value).draw();
            });

            $('#status').on('change', function () {
                var value_status = '';
                var myTable = $('#dataTable').dataTable();

                if(this.value == 'ok'){
                    value_status = 'OK';
                }else if(this.value == 'not'){
                    value_status = 'NOT OK';

                    $("#select-all").prop('checked',false);
                    myTable.$(".checkbox", {"page": "all"}).each(function() {
                        this.checked = false;
                    });
                }else{
                    value_status = '';
                }
                
                table.columns(1).search("^" + value_status , true, false, true).draw();
            });

            $("#export_excel").click(function(e) {
                var get_status = $('#status').val();
                var value_status = '';

                if(get_status == 'ok'){
                    value_status = 'OK';
                }else if(get_status == 'not'){
                    value_status = 'NOT OK';
                }else{
                    value_status = '';
                }
                
                var test = table.rows(function ( idx, data, node ) {

                            if(value_status != ''){
                                return data[1] == value_status ? true : false;
                            }else{
                                return true;
                            }
                            
                            }).data();
                var data = [];
                
                var header = 'Status; Status Message; Branch ID; Branch Name; Merchant Name; MID; MPAN; NMID; Status \n';
                data.push(header);

                test.map(function (item, index) {
                    var rowData = item[1]+';'+item[2]+';'+item[3]+';'+item[4]+';'+item[5]+';'+item[6]+';'+ '`'+item[7] +';'+item[8]+';'+removeHTMLTags(item[9])+'\n';
                    data.push(rowData);
                });

                const blob = new Blob([data.join("")], { type: "text/csv; charset=utf-8" });
                const url = URL.createObjectURL(blob);
                const link = document.createElement("a");
                link.setAttribute("href", url);
                link.setAttribute("download", "export.csv");
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });
        });

        function removeHTMLTags(htmlString) {
            const parser = new DOMParser();
            const doc = parser.parseFromString(htmlString, 'text/html');
            const textContent = doc.body.textContent || "";
            return textContent.trim();
        }
    </script>
@endsection
