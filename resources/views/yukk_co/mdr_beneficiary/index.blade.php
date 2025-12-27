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

    li>a>span.text {
        white-space: break-spaces;
        margin-right: 10px !important;
    }

    .bootstrap-select.show-tick .dropdown-menu .selected span.check-mark {
        right: 15px !important;
        top: 25% !important;
    }
    .bootstrap-select .dropdown-menu {
        min-width: 280px !important;
    }
    div .inner .show {
        position: relative !important;
    }
    
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(44, 44, 44, 0.14);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .loading-overlay .spinner {
        border: 4px solid rgba(0,0,0,.1);
        border-radius: 50%;
        border-top-color: #3498db;
        width: 30px;
        height: 30px;
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>
<div class="page-header page-header-light">
    <div class="page-header-content d-sm-flex">
        <div class="page-title">
            {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
            <h4>Beneficiary MDR</h4>
        </div>

        <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
            {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
        </div>
    </div>

    <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                <span class="breadcrumb-item active">Beneficiary MDR</span>
            </div>

            {{--<a href="#" class="header-elements-toggle text-body d-sm-none"><i class="icon-more"></i></a>--}}
        </div>

        {{--<div class="header-elements d-none">
                <div class="breadcrumb justify-content-center">
                    <a href="#" class="breadcrumb-elements-item">
                        Link
                    </a>

                    <div class="breadcrumb-elements-item dropdown p-0">
                        <a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
                            Dropdown
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="#" class="dropdown-item">Action</a>
                            <a href="#" class="dropdown-item">Another action</a>
                            <a href="#" class="dropdown-item">One more action</a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">Separate action</a>
                        </div>
                    </div>
                </div>
            </div>--}}
    </div>
</div>
<!-- /page header -->
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route("cms.yukk_co.mdr_beneficiary.detail") }}" method="get">
            <div class="row">
                <div class="col-lg-12">            
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label style="float:right">Choose Type</label>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="selectionType" id="selectBeneficiary" value="beneficiary" @if($selection_type && $selection_type=='beneficiary') checked @endif>
                                    <label class="form-check-label" for="selectBeneficiary">Beneficiary</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="selectionType" id="selectPartner" value="partner"  @if($selection_type && $selection_type=='partner') checked @endif>
                                    <label class="form-check-label" for="selectPartner">Partner</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="beneficiarySearch">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label style="float:right">Select Beneficiary</label>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group" id="beneficiarySearch">
                                <select id="beneficiary" data-live-search="true" name="beneficiary[]" data-live-search-style="contains" class="selectpicker" class="form-control">
                                    <!-- default "" selected -->
                                    <!-- if customer id have other value than "" remove selected -->
                                    @if($customer_id && $customer_id[0] != "")
                                    <option value="">Nothing Selected</option>
                                    @else
                                    <option value="" selected>Nothing Selected</option>
                                    @endif
                                    @foreach($result as $item)
                                    @if($customer_id && in_array(@$item->id, $customer_id))
                                    <option value="{{ @$item->id }}" selected>{{@$item->name}}</option>
                                    @endif
                                    @endforeach
                                    @foreach($result as $item)
                                    @if(!($customer_id && in_array(@$item->id, $customer_id)))
                                    <option value="{{ @$item->id }}">{{@$item->name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="partnerSearch" style="display: none;">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label style="float:right">Select Partner</label>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group" id="partnerSearch">
                                <select id="partner" data-live-search="true" name="partner[]" data-live-search-style="contains" class="selectpicker" class="form-control">
                                    @if($partner_id && $partner_id[0] != "")
                                    <option value="">Nothing Selected</option>
                                    @else
                                    <option value="" selected>Nothing Selected</option>
                                    @endif
                                    @if($partner_result)
                                        @foreach($partner_result as $item)
                                            @if($partner_id && in_array(@$item->id, $partner_id))
                                            <option value="{{ @$item->id }}" selected>{{@$item->name}}</option>
                                            @endif
                                        @endforeach
                                        @foreach($partner_result as $item)
                                            @if(!($partner_id && in_array(@$item->id, $partner_id)))
                                            <option value="{{ @$item->id }}">{{@$item->name}}</option>
                                            @endif
                                        @endforeach
                                    @endif

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label style="float:right">@lang("cms.Date Range")</label>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <input required type="text" id="date_range" name="date_range" class="form-control" placeholder="@lang(" cms.Search Date Range")" value="{{ $start_time->format("d-M-Y H:i:s") }} - {{ $end_time->format("d-M-Y H:i:s") }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8">
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="input-group input-group-append position-static">
                                    <button id="btnCalculate" class="btn btn-primary form-control" style="float:right" type="submit"><i class="icon-search4"></i> Calculate</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($detail)
                    <hr>
                    <div class="row">
                        <div class="col-lg-4">
                            <h3>QRIS</h3>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <p>Number of Transaction</p>
                                        <p>Total Transaction Amount</p>
                                        <p>Total MDR</p>
                                        <p>Total Yukk Portion (include PPN)</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <p>{{ @\App\Helpers\H::formatNumber($detail->summary_qris->count, 0) }}</p>
                                        <p>{{ @\App\Helpers\H::formatNumber($detail->summary_qris->total_of_grand_total, 2) }}</p>
                                        <p>{{ @\App\Helpers\H::formatNumber($detail->summary_qris->total_of_mdr, 2) }}</p>
                                        <p style="color:#afa">{{ @\App\Helpers\H::formatNumber($detail->summary_qris->total_of_yukk_portion, 2) }}
                                            @if($detail->summary_qris->total_of_yukk_portion != 0)
                                            <span onclick="copy({{$detail->summary_qris->total_of_yukk_portion}})" class="badge badge-success" style="margin-left:10px; background:transparent; color:#fff; cursor:pointer; border:1px solid #afa">copy</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <h3>PG (BCA)</h3>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <p>Number of Transaction</p>
                                        <p>Total Transaction Amount</p>
                                        <p>Total Yukk Portion (exclude PPN)</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <p>{{ @\App\Helpers\H::formatNumber($detail->summary_pg_bca->count, 0) }}</p>
                                        <p>{{ @\App\Helpers\H::formatNumber($detail->summary_pg_bca->total_of_grand_total, 2) }}</p>
                                        <p style="color:#afa">{{ @\App\Helpers\H::formatNumber($detail->summary_pg_bca->total_of_yukk_portion, 2) }}
                                            @if($detail->summary_pg_bca->total_of_yukk_portion != 0)
                                            <span onclick="copy({{$detail->summary_pg_bca->total_of_yukk_portion}})" class="badge badge-success" style="margin-left:10px; background:transparent; color:#fff; cursor:pointer; border:1px solid #afa">copy</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <h3>PG (Non BCA)</h3>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <p>Number of Transaction</p>
                                        <p>Total Transaction Amount</p>
                                        <p>Total Yukk Portion (exclude PPN)</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <p>{{ @\App\Helpers\H::formatNumber($detail->summary_pg->count, 0) }}</p>
                                        <p>{{ @\App\Helpers\H::formatNumber($detail->summary_pg->total_of_grand_total, 2) }}</p>
                                        <p style="color:#afa">{{ @\App\Helpers\H::formatNumber($detail->summary_pg->total_of_yukk_portion, 2) }}
                                            @if($detail->summary_pg->total_of_yukk_portion != 0)
                                            <span onclick="copy({{$detail->summary_pg->total_of_yukk_portion}})" class="badge badge-success" style="margin-left:10px; background:transparent; color:#fff; cursor:pointer; border:1px solid #afa">copy</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @if($detail)
            <div class="row">
                <div class="col-lg-3">
                </div>
                <div class="col-lg-6">
                    <h3>Total</h3>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <p>Number of Transaction</p>
                                <p>Total Transaction Amount</p>
                                <p>Total MDR</p>
                                <p>Total Yukk Portion</p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <p>{{ @\App\Helpers\H::formatNumber($detail->summary_total->count, 0) }}</p>
                                <p>{{ @\App\Helpers\H::formatNumber($detail->summary_total->total_of_grand_total, 2) }}</p>
                                <p>{{ @\App\Helpers\H::formatNumber($detail->summary_total->total_of_mdr, 2) }}</p>
                                <p style="color:#afa">{{ @\App\Helpers\H::formatNumber($detail->summary_total->total_of_yukk_portion, 2) }}
                                    @if($detail->summary_total->total_of_yukk_portion != 0)
                                    <span onclick="copy({{$detail->summary_total->total_of_yukk_portion}})" class="badge badge-success" style="margin-left:10px; background:transparent; color:#fff; cursor:pointer; border:1px solid #afa">copy</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            @if($detail->summary_total->total_of_yukk_portion != 0)
                            <p>
                                <span onclick="export2excel()" class="badge badge-success" style="margin:10px; width:100%; background:transparent; color:#fff; cursor:pointer; border:1px solid #afa">Export to Excel</span>
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </form>
    </div>
</div>
<div class="card-footer">
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.1/xlsx.full.min.js"></script>
<script>
    var page = 1;
    function addOverlayToDivInnerShow(){
        var divInnerShow = $("div.inner.show");
        var overlay = $("<div class='loading-overlay'><div class='spinner'></div></div>");
        divInnerShow.append(overlay);
    }
    function removeOverlayFromDivInnerShow(){
        var divInnerShow = $("div.inner.show");
        divInnerShow.find(".loading-overlay").remove();
    }
    function changeBenef(e, clickedIndex, newValue, oldValue){
        // if value is other than "", deselect option ""
        if (clickedIndex != 0) {
            $('#beneficiary option[value=""]').prop('selected', false);
            $('#beneficiary').selectpicker('refresh');
        }
        // if value is "", deselect all other options
        if (clickedIndex == 0) {
            $('#beneficiary').val("").selectpicker('refresh');
        }
    }
    function toggleSelection() {
        let beneficiarySearch = document.getElementById('beneficiarySearch');
        let partnerSearch = document.getElementById('partnerSearch');
        let selectBeneficiary = document.getElementById('selectBeneficiary');
        let selectPartner = document.getElementById('selectPartner');
        if (selectBeneficiary.checked) {
            beneficiarySearch.style.display = 'flex';
            partnerSearch.style.display = 'none';
            fetchCustomers();
            $('#partner').val("").selectpicker('refresh');
        } else if (selectPartner.checked) {
            beneficiarySearch.style.display = 'none';
            partnerSearch.style.display = 'flex';
            fetchPartner();
            $('#beneficiary').val("").selectpicker('refresh');
        }
    }

    function fetchPartner(keyword = null){
        var selectedIds = $('#partner').val() == "" ? null : $('#partner').val();
        var params = {
            keyword: keyword ? keyword : null,
            selected: selectedIds ? selectedIds : null,
            page: page
        };
        var url = "{{ route('cms.yukk_co.mdr_beneficiary.get_partners') }}";
        addOverlayToDivInnerShow();
        $.ajax({
            url: url,
            type: 'GET',
            data: params,
            success: function (response) {
                // if data is not empty put it to select beneficiary option
                // clear all option if page is 1 except those selected must keep the selected option
                if(response.data && response.data.length > 0) {
                    if(keyword) $('#partner').empty();
                    $.each(response.data, function (key, value) {
                        $('#partner').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                    $('#partner').selectpicker('refresh');
                }
                // remove overlay from div.inner.show
                removeOverlayFromDivInnerShow();
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    }
    // fetch customers from server
    function fetchCustomers(keyword = null){
        var selectedIds = $('#beneficiary').val() == "" ? null : $('#beneficiary').val();
        var params = {
            keyword: keyword ? keyword : null,
            selected: selectedIds ? selectedIds : null,
            page: page
        };
        var url = "{{ route('cms.yukk_co.mdr_beneficiary.get_customers') }}";
        // add overlay to div.inner.show
        addOverlayToDivInnerShow();
        $.ajax({
            url: url,
            type: 'GET',
            data: params,
            success: function (response) {
                if(response.data && response.data.length > 0) {
                    if(keyword) $('#beneficiary').empty();
                    $.each(response.data, function (key, value) {
                        $('#beneficiary').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                    $('#beneficiary').selectpicker('refresh');
                }
                // remove overlay from div.inner.show
                removeOverlayFromDivInnerShow();
            },
            error: function (xhr, status, error) {
                // remove overlay from div.inner.show
                removeOverlayFromDivInnerShow();
            }
        });
    }
    $(document).ready(function() {
        let beneficiarySearch = document.getElementById('beneficiarySearch');
        let partnerSearch = document.getElementById('partnerSearch');
        let selectBeneficiary = document.getElementById('selectBeneficiary');
        let selectPartner = document.getElementById('selectPartner');
        toggleSelection();
        selectBeneficiary.addEventListener('change', toggleSelection);
        selectPartner.addEventListener('change', toggleSelection);
        $('#beneficiary').on("changed.bs.select", function(e, clickedIndex, newValue, oldValue) {
            console.log(this.value, clickedIndex, newValue, oldValue)
            changeBenef(e, clickedIndex, newValue, oldValue)
        });
        // add listener to beneficiary selectpicker when user scroll to the bottom of the dropdown call cms.yukk_co.mdr_beneficiary.get_customers
        $('#beneficiary').on('shown.bs.select', function() {
            var menu = $("div.inner.show");
            menu.on('scroll', function() {
                if (menu[0].scrollHeight - menu.scrollTop() <= menu.outerHeight()) {
                    let benefSearchInput = $('#beneficiarySearch .bs-searchbox input').val();
                    console.log('fetch customers', benefSearchInput);
                    page++;
                    fetchCustomers(benefSearchInput);
                }
            });
        });        
        $('#partner').on('shown.bs.select', function() {
            var menu = $("div.inner.show");
            menu.on('scroll', function() {
                if (menu[0].scrollHeight - menu.scrollTop() <= menu.outerHeight()) {
                    let partnerSearchInput = $('#partnerSearch .bs-searchbox input').val();
                    console.log('fetch customers', partnerSearchInput);
                    page++;
                    fetchPartner(partnerSearchInput);
                }
            });
        });

        $('#partner').on("changed.bs.select", function(e, clickedIndex, newValue, oldValue) {
            if (e.target.value == "" || clickedIndex == 0) {
                $('#clearPartner').hide();
            } else {
                $('#clearPartner').show();
            }
            // if value is other than "", deselect option ""
            if (clickedIndex != 0) {
                $('#partner option[value=""]').prop('selected', false);
                $('#partner').selectpicker('refresh');
            }
            // if value is "", deselect all other options
            if (clickedIndex == 0) {
                $('#partner').val("").selectpicker('refresh');
            }
        });
        var typingTimer; 
        var doneTypingInterval = 1500; 
        function onSearchInput(keyword) {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(function(){
                fetchPartner(keyword);
            }, doneTypingInterval);
        }

        function onBenefSearchInput(keyword) {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(function(){
                page = 1;
                fetchCustomers(keyword);
            }, doneTypingInterval);
        }

        $(document).on('keyup', '#partnerSearch .bs-searchbox input', function (e) {
            var searchData = e.target.value;
            console.log(searchData);
            onSearchInput(searchData)
        });

        $(document).on('keyup', '#beneficiarySearch .bs-searchbox input', function (e) {
            var searchData = e.target.value;
            console.log(searchData);
            onBenefSearchInput(searchData)
        });


        $("#date_range").daterangepicker({
            parentEl: '.content-inner',
            locale: {
                format: 'DD-MMM-YYYY HH:mm:ss',
                firstDay: 1,
            },
            timePicker: true,
            maxDate: new Date(),
            timePicker24Hour: true,
        });
        $('.selectpicker').selectpicker();
    });

    function copy(num) {
        try {
            navigator.clipboard.writeText(num).then(
                function() {
                    /* clipboard successfully set */
                    window.alert('Success! The text was copied to your clipboard')
                },
                function() {
                    /* clipboard write failed */
                    window.prompt("Copy to clipboard: Ctrl+C, Enter", num);
                }
            )

        } catch (error) {
            /* clipboard write failed */
            window.prompt("Copy to clipboard: Ctrl+C, Enter", num);
        }
    }

    $("#beneficiary").change(function() {
        let selected = $(this).children(":selected");
        let val = $("#beneficiary").val();
    });
    $("#partner").change(function() {
        let selected = $(this).children(":selected");
        let val = $("#partner").val();
    });

    function export2excel() {
        const locale = 'id-ID';
        @if($details_qris)
        var details_qris = JSON.parse('{!!@json_encode($details_qris)!!}');
        @endif

        @if($details_pg)
        var details_pg = JSON.parse('{!!@json_encode($details_pg)!!}');
        @endif

        @if($details_pg_bca)
        var details_pg_bca = JSON.parse('{!!@json_encode($details_pg_bca)!!}');
        @endif

        @if($details_pg || $details_pg_bca || $details_qris)
        filename = 'reports.xlsx';
        var ws = null
        var wb = XLSX.utils.book_new();
        @if($details_pg)
        ws = XLSX.utils.json_to_sheet(details_pg);
        formatSheet(ws);
        XLSX.utils.book_append_sheet(wb, ws, "transaction_pg");
        @endif
        @if($details_pg_bca)
        ws = XLSX.utils.json_to_sheet(details_pg_bca);
        formatSheet(ws);
        XLSX.utils.book_append_sheet(wb, ws, "transaction_pg_bca");
        @endif
        @if($details_qris)
        ws = XLSX.utils.json_to_sheet(details_qris);
        formatSheet(ws);
        XLSX.utils.book_append_sheet(wb, ws, "transaction_qris");
        @endif
        XLSX.writeFile(wb, filename);
        @endif
    }

    function formatSheet(ws) {
        // Define the column headers that you want to format as numbers
        const numberColumns = ['Trx_Amount', 'MDR_Internal', 'MDR_External', 'Partner_Fee', 'MDR_QRIS', 'Yukk_Portion',
            'Fee_Switching', 'Issuer_Portion', 'Acquirer_Portion'
        ];

        // Get all the cells that match the pattern XX1
        const headerCells = Object.keys(ws).filter(cell => /^[A-Z]+1$/.test(cell));
        // Create a map of column header names to cell names
        const headerMap = headerCells.reduce((map, cell) => {
            const value = ws[cell].v;
            map[cell.charAt(0)] = value; // assumes column names are 1 character; change this line if they are longer
            return map;
        }, {});
        // Loop over the cells in the sheet
        for (let cell in ws) {
            // Check if the key is not a cell (like '!ref')
            if (cell[0] === '!') continue;

            // Get the column name from the cell name
            const columnName = cell.replace(/[0-9]/g, '');

            // If this cell is in a column that should be formatted as a number, and its value is numeric, then format it
            if (numberColumns.includes(headerMap[columnName]) && !isNaN(ws[cell].v)) {
                // Set the cell type to 'n' (numeric)
                ws[cell].t = 'n';

                // Set the cell format to thousands separator with 2 decimal places
                ws[cell].z = XLSX.SSF.get_table()[4]; // '4' is the code for '#,##0.00'
            }
        }
    }
</script>
@endsection