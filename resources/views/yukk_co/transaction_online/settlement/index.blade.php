<x-app-layout>
    <x-page.header :title="__('cms.ONLINE (YUKK) TRANSFER TO YUKKINGS/CUSTOMER')">
        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            <x-breadcrumb.active>@lang("cms.ONLINE (YUKK) TRANSFER TO YUKKINGS/CUSTOMER")</x-breadcrumb.active>
        </x-slot>
    </x-page.header>

    <x-page.content :title="__('cms.ONLINE (YUKK) TRANSFER TO YUKKINGS/CUSTOMER')">
        <div class="ml-2">
            <form action="{{ url('yukk-co/transaction-online/settlements') }}" method="get">
                <div class="form-group row">
                    <label class="col-form-label" for="date">Date</label>
                    <div class="col-sm-2">
                        <input type="date" name="date" value="{{ $date }}" class="form-control bg-white text-dark">
                    </div>
                    <button type="submit" class="btn btn-primary px-3">Submit</button>
                    <a href="{{ url('yukk-co/transaction-online/transfers?date=') . $date }}" class="btn btn-success ml-3" target="_blank">Transfer list</a>
                </div>
            </form>
        </div>
        <div class="font-size-sm">*Minimum Settlement Rp10.000 per Beneficiary</div>
        <div class="table-responsive">
            <form action="{{ url('yukk-co/transaction-online/transfers') }}" method="post">
            @csrf
            <input type="text" name="date" value="{{ $date }}" hidden>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th><!-- <input type="checkbox" id="checkAll"> --></th>
                        <th>Customer Name</th>
                        <th>Customer Email</th>
                        <th>Customer Type</th>
                        <th>Bank Name</th>
                        <th>Account Number</th>
                        <th>Transaction Time</th>
                        <th>Nominal</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Ref code</th>
                        <th class="text-center">Option</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($settlements as $settlement)
                        <?php
                            $customerType = data_get($settlement, 'customer_type', 'PLATFORM');
                        ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="transfer[{{ $loop->index }}][status]" class="checkbox" @if ($settlement['status']) hidden @endif />
                                <input type="text" name="transfer[{{ $loop->index }}][customer_id]" value="{{ $settlement['customer_id'] }}" hidden />
                                <input type="text" name="transfer[{{ $loop->index }}][date]" value="{{ $settlement['date'] }}" hidden />
                                <input type="text" name="transfer[{{ $loop->index }}][type]" value="{{ $settlement['type'] }}" hidden />
                            </td>
                            <td>{{ $settlement['customer']['name'] ?? '' }}</td>
                            <td>{{ $settlement['customer']['email'] ?? '' }}</td>
                            <td>{{ strtoupper($customerType) }}</td>
                            <td>{{ $settlement['bank']['name']?? '' }}</td>
                            <td>{{ $settlement['customer']['account_number']?? '' }}</td>
                            <td>{{ $settlement['date'] }}</td>
                            <td>{{ number_format($settlement['amount'], 2) }}</td>
                            <td>{{ $settlement['type'] }}</td>
                            <td>{{ $settlement['status'] }}</td>
                            <td>{{ $settlement['code'] }}</td>
                            <?php
                                $status = $settlement['status'];
                                if ($status == null || $status == '') {
                                    $status = 'ready';
                                }

                                $itemId = data_get($settlement, 'item_id', 0);
                            ?>
                            <td class="text-center">
                                <div class="dropdown position-static">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false">
                                        <i class="icon-menu9"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                    <a href="{{
                                            url('yukk-co/transaction-online/transactions') . '?' . http_build_query([
                                                'customer_id' => data_get($settlement, 'customer_id', ''),
                                                'customer_type' => $customerType,
                                                'type' => data_get($settlement, 'type', ''),
                                                'date' => data_get($settlement, 'date', ''),
                                                'item_id' => $itemId,
                                                'status' => $status,
                                                'fulfillment_status' => 1
                                            ])
                                        }}" target="_blank" class="dropdown-item">
                                        Details
                                    </a>
                                        <a class="dropdown-item" data-toggle="modal" data-target="#confirmation_modal_{{$loop->index }}" @if ($settlement['status']) hidden @endif>Manual Transfer</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary px-5 mt-4">Transfer</button>
        </form>
        @foreach($settlements as $settlement)
            <div class="modal fade" id="confirmation_modal_{{$loop->index}}" tabindex="-1" role="dialog" aria-labelledby="confimationModal" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <form action="{{ url('yukk-co/transaction-online/manual-transfers') }}" method="POST" id="confirmationModal" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title text-center" id="demoModalLabel">Peringatan</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="text-center">
                                    Apakah anda yakin "{{ $settlement['customer']['name'] ?? '' }}" type : "{{ $settlement['type'] }}" sudah di transfer?
                                </div>
                                <input name="date" type="text" value="{{ request('date') ?? date('Y-m-d') }}" hidden>
                                <input name="entity_id" type="text" value="{{ $settlement['customer_id'] }}" hidden>
                                <input name="customer_type" type="text" value="{{ data_get($settlement, 'customer_type', 'platform') === 'partner' ? 'partner' : 'platform' }}" hidden>
                                <input name="type" type="text" value="{{ $settlement['type'] }}" hidden>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang("cms.No")</button>
                                <button type="submit" class="btn btn-primary">@lang("cms.Yes")</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
        </div>

        <x-slot name="footer">
            <div style="overflow-x: auto;">
                <div class="d-md-flex justify-content-md-end">

                </div>
            </div>
        </x-slot>
    </x-page.content>
    @swal

    @push('scripts')
    <script>
        $("#checkAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    </script>
    @endpush
</x-app-layout>
