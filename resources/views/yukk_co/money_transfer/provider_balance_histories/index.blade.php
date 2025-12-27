<x-app-layout>
    <x-page.header :title="__('cms.Rekon Provider Balance')">
        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            <x-breadcrumb.active>{{ __("cms.Rekon Provider Balance") }}</x-breadcrumb.active>
        </x-slot>
    </x-page.header>

    <x-page.content>
    <div class="modal" id="createModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header mx-auto">
                    <!-- Tabs navs -->
                    <ul class="nav nav-tabs" id="ex1" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a
                            class="nav-link active"
                            id="cashout_tab"
                            data-toggle="tab"
                            href="#cashout_content"
                            role="tab"
                            aria-controls="cashout_content"
                            aria-selected="true"
                            >Cashout</a
                            >
                        </li>
                        <li class="nav-item" role="presentation">
                            <a
                            class="nav-link"
                            id="adjustment_tab"
                            data-toggle="tab"
                            href="#adjustment_content"
                            role="tab"
                            aria-controls="adjustment_content"
                            aria-selected="false"
                            >Adjustment</a
                            >
                        </li>
                    </ul>
                    <!-- Tabs navs -->
                </div>
                <div class="modal-body">
                    <!-- Tabs content -->
                    <div class="tab-content" id="ex1-content">
                        <div
                            class="tab-pane fade show active"
                            id="cashout_content"
                            role="tabpanel"
                            aria-labelledby="cashout_content"
                        >
                            <form action="{{ route('money_transfer.provider_balances.cashout') }}" class="form-adjustment" method="post">
                                @csrf
                                <div class="text-center">
                                    <h4>Cashout</h4>
                                </div>

                                <input type="hidden" name="provider_code" value="{{ $filters['provider'] }}">
                                <input type="hidden" id="cashout_unique_code_non_format" name="cashout_unique_code_non_format" value="{{ $summary['UNIQUE_CODE']['total'] ?? 0}}">

                                <div class="form-group">
                                    <label>@lang("cms.Description")</label>
                                    <input type="text" id="cashout_description" name="cashout_description" class="form-control" placeholder="@lang("cms.Description")" value="" required>
                                </div>

                                <div class="form-group">
                                    <label>@lang("cms.Unique Code")</label>
                                    <input type="text" id="cashout_unique_code" name="cashout_unique_code" class="form-control" value="" required>
                                    <span id="cashout_unique_code_info" class="text-danger" style="font-size: smaller;"></span>
                                </div>

                                <div class="form-group">
                                    <label>@lang("cms.Final Balance")</label>
                                    <input type="text" id="cashout_final_balance" name="cashout_final_balance" class="form-control" value="" readonly>
                                </div>

                                <div class="form-group">
                                    <div class="input-group input-group-append position-static">
                                        <button class="btn-submit-cashout btn btn-primary form-control" type="submit">@lang("cms.Confirm")</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="adjustment_content" role="tabpanel" aria-labelledby="adjustment_content">
                            <form action="{{ route('money_transfer.provider_balances.adjustment') }}" class="form-adjustment" method="post">
                                @csrf
                                <div class="text-center">
                                    <h4>Adjustment</h4>
                                </div>

                                <input type="hidden" name="provider_code" value="{{ $filters['provider'] }}">
                                <input type="hidden" id="adjustment_unique_code_non_format" name="adjustment_unique_code_non_format" value="{{ $summary['UNIQUE_CODE']['total'] ?? 0}}">
                                <input type="hidden" id="adjustment_balance_non_format" name="adjustment_balance_non_format" value="{{ $summary['PRIMARY']['total'] ?? 0}}">
                                <input type="hidden" name="adjustment_last_balance" value="{{ $summary['PRIMARY']['total'] ?? 0}}">
                                <input type="hidden" name="adjustment_last_unique_code" value="{{ $summary['UNIQUE_CODE']['total'] ?? 0}}">

                                <div class="form-group">
                                    <label>@lang("cms.Description")</label>
                                    <input type="text" id="adjustment_description" name="adjustment_description" class="form-control" placeholder="@lang("cms.Description")" value="" required>
                                </div>

                                <div class="form-group">
                                    <label>@lang("cms.Unique Code")</label>
                                    <input type="text" id="adjustment_unique_code" name="adjustment_unique_code" class="form-control" value="" required>
                                </div>

                                <div class="form-group">
                                    <label>@lang("cms.Balance")</label>
                                    <input type="text" id="adjustment_balance" name="adjustment_balance" class="form-control" value="" required>
                                </div>

                                <div class="form-group">
                                    <div class="input-group input-group-append position-static">
                                        <button class="btn-submit-adjustment btn btn-primary form-control" type="submit">@lang("cms.Confirm")</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Tabs content -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btn-cancel-adjustment" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 border rounded my-auto mr-2">
            <div class="m-1">
                <h5>@lang("cms.Current Total Deposit")</h5>
                <label id="current_total_deposit">Rp {{ number_format((@$summary['PRIMARY']['total']+@$summary['UNIQUE_CODE']['total']),0,',','.') }}</label>
            </div>
        </div>
        <div class="col-lg-3 border rounded my-auto mr-2">
            <div class="m-1">
                <h5>@lang("cms.Current Deposit")</h5>
                <label id="current_deposit">Rp {{ number_format(@$summary['PRIMARY']['total'],0,',','.') }}</label>
            </div>
        </div>
        <div class="col-lg-3 border rounded my-auto mr-2">
            <div class="m-1">
                <h5>@lang("cms.Current Unique Code")</h5>
                <label id="current_unique_code">Rp {{ number_format(@$summary['UNIQUE_CODE']['total'],0,',','.') }}</label>
            </div>
        </div>
        @hasaccess('MONEY_TRANSFER.PROVIDER_BALANCE_HISTORIES_UPDATE')
            @if(in_array($filters['provider'], ['YUKK', 'FLIP']))
                <div class="col-lg-2 my-auto">
                    <div class="">
                        <button type="button" class="form-control btn btn-primary" data-toggle="modal" data-target="#createModal">@lang("cms.Create")</button>
                    </div>
                </div>
            @endif
        @endhasaccess
    </div>

    <div class="mt-3">
        <div class="col">
            <div class="mb-3">
                <a href="{{ $filters['filter']  == 'all' ? '#' : route('money_transfer.provider_balance_histories.index')}}"
                    class="btn @if($filters['filter']  == 'all') btn-secondary @else btn-outline-secondary @endif mr-2">
                    All 
                </a>
                <a href="{{ $filters['filter']  == 'credit' ? '#' : route('money_transfer.provider_balance_histories.index').'?filter=credit' }}"
                    class="btn @if($filters['filter']  == 'credit') btn-secondary @else btn-outline-secondary @endif mr-2">
                    Credit 
                </a>
                <a href="{{ $filters['filter']  == 'debit' ? '#' : route('money_transfer.provider_balance_histories.index').'?filter=debit' }}"
                    class="btn @if($filters['filter']  == 'debit') btn-secondary @else btn-outline-secondary @endif mr-2">
                    Debit 
                </a>
            </div>
            
            <form action="{{ route('money_transfer.provider_balance_histories.index') }}" method="get">
                <div class="row">
                    <input type="hidden" name="filter" value="{{ $filters['filter']  }}">

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>@lang("cms.Date Range")</label>
                            <input type="text" id="date_range" name="date_range" class="form-control" placeholder="@lang("cms.Search Date Range")"
                            value="{{ $filters['start_date']->format('d-M-Y') }} - {{ $filters['end_date']->format('d-M-Y') }}">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>@lang("cms.Select Provider")</label>
                            <select id="provider" name="provider" class="form-control">
                                <option value="all"
                                @if($filters['provider'] == "all")
                                    selected 
                                @endif
                                >@lang("All")</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>@lang("cms.Search")</label>
                            <input type="text" id="search" name="search" class="form-control" placeholder="@lang("cms.Search Notes")"
                            value="{{ $search }}">
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="col">
                            <label for="">Tag</label>
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

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="input-group input-group-append position-static">
                                <button class="btn btn-primary form-control" type="submit"><i class="icon-search4"></i> @lang("cms.Filter")</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            
            <hr>

            <div class="d-flex mb-3 overflow-auto">
                <div class="col border rounded my-auto mr-2">
                    <div class="m-1">
                        <p>@lang("cms.Remaining Deposit")</p>
                        <label id="remaining_deposit" class="text-primary">-</label>
                    </div>
                </div>
                <div class="col border rounded my-auto mr-2">
                    <div class="m-1">
                        <p>@lang("cms.Remaining Unique Code")</p>
                        <label id="remaining_unique_code" class="text-success">-</label>
                    </div>
                </div>
                <div class="col border rounded my-auto mr-2">
                    <div class="m-1">
                        <p>@lang("cms.Total Credit")</p>
                        <label id="current_credit" class="text-success">-</label>
                    </div>
                </div>
                <div class="col border rounded my-auto mr-2">
                    <div class="m-1">
                        <p>@lang("cms.Total Debit")</p>
                        <label id="current_debit" class="text-danger">-</label>
                    </div>
                </div>
                <div class="col border rounded my-auto mr-2">
                    <div class="m-1">
                        <p>@lang("cms.Total Disburse")</p>
                        <label id="total_lifetime" class="text-success">-</label>
                    </div>
                </div>
                <div class="col border rounded my-auto">
                    <div class="m-1">
                        <p>@lang("cms.Total Disburse Fee")</p>
                        <label id="total_fee_lifetime" class="text-success">-</label>
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-striped table-responsive dataTable mb-3">
                <thead>
                    <tr>
                        <th class="w-auto">No.</th>
                        <th class="w-100">NOTES</th>
                        <th class="w-auto">TAG</th>
                        <th class="w-auto">DEBIT</th>
                        <th class="w-auto">CREDIT</th>
                        <th class="w-auto">LATEST AMOUNT</th>
                        <th class="w-auto">LATEST TRX BALANCE</th>
                        <th class="w-auto">LATEST UNIQUE CODE BALANCE</th>
                        <th class="w-auto">TRANSACTION TIME</th>
                    </tr>
                </thead>

                <tbody>
                    @if (count($histories) == 0)
                    <tr>
                        <td colspan="9" class="text-center">Data Not Found</td>
                    </tr>
                    @endif
                    @php
                        $counter = ($current_page * 10) - 10;
                    @endphp

                    @foreach($histories as $his)
                        <tr>
                            <td>{{ ++$counter }}</td>
                            <td>{{ $his['notes'] }}</td>
                            <td>{{ array_key_exists('entity_type', $his['transaction'])
                                ? $his['transaction']['entity_type'] 
                                : $his['transaction']['transaction']['entity_type'] ?? '-' }}
                            </td>
                            <td>{{ number_format($his['debit'], 0, ",", ".") }}</td>
                            <td>{{ number_format($his['credit'], 0, ",", ".") }}</td>
                            <td>{{ number_format($his['latest_amount'], 0, ",", ".") }}</td>
                            <td>{{ number_format($his['latest_trx_balance'], 0, ",", ".") }}</td>
                            <td>{{ number_format($his['latest_unique_code_balance'], 0, ",", ".") }}</td>
                            <td>{{ $his['created_at_format'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

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
                                <a href="{{ route("money_transfer.provider_balance_histories.index", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
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
                                    <a href="{{ route("money_transfer.provider_balance_histories.index", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
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
                                <a href="{{ route("money_transfer.provider_balance_histories.index", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">
                                    <i class="icon-arrow-right13"></i>
                                </a>
                            </li>
                        @endif

                    </ul>
                </div>
            </div>
        </div>
    </div>
    </x-page.content>

@push('styles')
<style>
    .table {
        white-space: nowrap;
    }

    p {
        white-space: nowrap;
    }
</style>
@endpush

@push('scripts')
    <script type="text/javascript" src="{{asset('assets/js/plugins/ui/moment/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/pickers/daterangepicker.js')}}"></script>
    
    <script>
        function formatInteger(rupiah) {
            var number_string = rupiah.replace('Rp ', ''),
                angka = number_string.split('.');
            
            return angka.join('');
        }

        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split    = number_string.split(','),
                sisa     = split[0].length % 3,
                rupiah     = split[0].substr(0, sisa),
                ribuan     = split[0].substr(sisa).match(/\d{3}/gi);
            
            if(parseInt(number_string) == 0) {
                return 0;
            }

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            
            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
        }

        $(document).ready(function() {
            let currentPrimary = {{ $summary['PRIMARY']['total'] ?? 0}};
            let currentUniqueCode = {{ $summary['UNIQUE_CODE']['total'] ?? 0 }};

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

            $('#cashout_unique_code').keyup(function(e) {
                $(this).val(formatRupiah($(this).val(), 'Rp '));

                $('#cashout_unique_code_non_format').val(formatInteger($(this).val()));

                if((currentUniqueCode-formatInteger($(this).val())) < 0) {
                    $('.btn-submit-cashout').hide();
                    $('#cashout_unique_code_info').html('The unique code entered is greater than balance');
                    $('#cashout_final_balance').val(formatRupiah(currentUniqueCode.toString(), 'Rp '));
                } else {
                    $('#cashout_unique_code_info').html('');
                    $('.btn-submit-cashout').show();
                    $('#cashout_final_balance').val(formatRupiah((currentUniqueCode-formatInteger($(this).val())).toString(), 'Rp '));
                }
                                
            })

            $('.form-adjustment').submit(function() {
                $(this).find(':button[type=submit]').prop('disabled', true);
                $(this).find(':button[type=submit]').html('Loading..');
            })

            $('#cashout_final_balance').val(formatRupiah(currentUniqueCode.toString(), 'Rp '));
            $('#adjustment_unique_code').val(formatRupiah(currentUniqueCode.toString(), 'Rp '));
            $('#adjustment_balance').val(formatRupiah(currentPrimary.toString(), 'Rp '));

            $('#adjustment_unique_code').keyup(function(e) {
                $(this).val(formatRupiah($(this).val(), 'Rp '));
                
                $('#adjustment_unique_code_non_format').val(formatInteger($(this).val()));
            })

            $('#adjustment_balance').keyup(function(e) {
                $(this).val(formatRupiah($(this).val(), 'Rp '));

                $('#adjustment_balance_non_format').val(formatInteger($(this).val()));

            });

            $('#btn-cancel-adjustment').click(function(e) {
                $('#cashout_description').val('');
                $('#cashout_final_balance').val(formatRupiah(currentUniqueCode.toString(), 'Rp '));
                $('#cashout_unique_code').val('');
                $('#cashout_unique_code_non_format').val(currentUniqueCode.toString());

                $('#adjustment_description').val('');
                $('#adjustment_unique_code_non_format').val(currentUniqueCode.toString());
                $('#adjustment_unique_code').val(formatRupiah(currentUniqueCode.toString(), 'Rp '));
                $('#adjustment_balance_non_format').val(currentPrimary.toString());
                $('#adjustment_balance').val(formatRupiah(currentPrimary.toString(), 'Rp '));
            })

        });
    </script>

    <script>
        $.ajax({url: "{{ route('money_transfer.json.providers.index') }}", success: function(data){
            let providerSelected = "{{ $filters['provider'] }}";
            let selected = '';

            data.result.forEach((item, key) => {
                selected = providerSelected == item.code ? 'selected' : '';

                $('#provider').append('<option ' +selected+ ' value="'+ item.code +'">'+ item.name +'</option>');
            });
        }});

        $.ajax({url: "{{ route('money_transfer.json.transactions.summary') }}", success: function(data){
            $('#total_lifetime').html(formatRupiah(data.result.total.toString(), undefined));
            $('#total_fee_lifetime').html(formatRupiah(data.result.fee.toString(), 'Rp '));
        }});

        $.ajax({
            url: "{{ route('money_transfer.json.provider-balance-histories.summary') }}",
            data: {
                filter: '{{ $filters["filter"] }}',
                provider: '{{ $filters["provider"] }}',
                tag: '{{ $filters["tag"] }}',
                start_date: '{{ $filters["start_date"]->format("Y-m-d") }}',
                end_date: '{{ $filters["end_date"]->format("Y-m-d") }}',
            },
            success: function(data){
                let result = data.result;
                let remaining = result.debit - result.credit;

                $('#remaining_deposit').html(formatRupiah(remaining.toString(), 'Rp '));
                $('#remaining_unique_code').html(formatRupiah(result.unique_code.toString(), 'Rp '));
                $('#current_credit').html(formatRupiah(result.credit.toString(), 'Rp '));
                $('#current_debit').html(formatRupiah(result.debit.toString(), 'Rp '));
            }
        });
    </script>
@endpush

</x-app-layout>