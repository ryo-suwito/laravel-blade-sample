<x-app-layout>
    <x-page.header :title="__('cms.Top Up Detail')">
        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            <x-breadcrumb.link :link="route('money_transfer.provider_deposits.index')" :text="__('cms.Top Up Balance')"/>
            <x-breadcrumb.active>{{ __("cms.Detail") }}</x-breadcrumb.active>
        </x-slot>

        <x-slot name="actions">
        @hasaccess('MONEY_TRANSFER.TOP_UP_BALANCE_UPDATE')
            @if(in_array($deposit['status'], ['FAILED.TRANSFER_BCA', 'SUCCESS.TRANSFER_BCA', 'FAILED.TOP_UP_PROVIDER']))
                <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                    @if(in_array($deposit['status'], ['FAILED.TRANSFER_BCA', 'SUCCESS.TRANSFER_BCA', 'FAILED.TOP_UP_PROVIDER']))
                        <button type="button" id="{{ $deposit['status'] == 'SUCCESS.TRANSFER_BCA' ? 'btnUpdate' : 'btnRetry' }}" class="btn btn-warning w-100 w-sm-auto" data-toggle="modal" data-target="#warningModal" data-action="{{ route('money_transfer.provider_deposits.retry', ['id' => $deposit['id']]) }}"
                        data-label="{{ $deposit['status'] == 'SUCCESS.TRANSFER_BCA' ? 'Update' : 'Retry' }}">
                            <i class="icon-spinner11 mr-2"></i> {{ $deposit['status'] == 'SUCCESS.TRANSFER_BCA' ? 'Update' : 'Retry' }}
                        </button>
                    @endif
                    @if(in_array($deposit['status'], ['FAILED.TRANSFER_BCA', 'FAILED.TOP_UP_PROVIDER']))
                        <button type="button" id="btnMarkAsSuccess" class="btn btn-success w-100 w-sm-auto" data-toggle="modal" data-target="#warningModal" data-action="{{ route('money_transfer.provider_deposits.mark_success', ['id' => $deposit['id']]) }}"
                        data-label="Mark As Success">
                            <i class="icon-checkmark"></i> Mark As Success
                        </button>
                    @endif
                </div>
            @endif
        @endhasaccess
        </x-slot>
    </x-page.header>

    <x-page.content>
        <div class="modal" id="warningModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <form id="formWarningModal" action="" method="post">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Attention!</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure to <span id="actionBody"></span>?</p>
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
            <div class="col-lg-6">
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Ref Id</label>
                    <div class="col-lg-8">
                        <x-form.readonly-input-or-link 
                                item="{{ $deposit['transaction_item_group']['code'] ?? '' }}" 
                                name="code" 
                                url="{{ route('money_transfer.transactions.groups.show', ['code' => $deposit['transaction_item_group']['code']]) }}" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Provider Transaction Id</label>
                    <div class="col-lg-8">
                        <input type="text" name="provider_transaction_id" class="form-control" required="" value="{{ $deposit['provider_transaction_id'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Bank Account Number")</label>
                    <div class="col-lg-8">
                        <input type="text" name="bank_account_name" class="form-control" required="" value="{{ $deposit['provider_account_number'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Unique Code")</label>
                    <div class="col-lg-8">
                        <input type="text" name="unique_code" class="form-control" required="" value="{{ number_format($deposit['unique_code'], 0, ',', '.') }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Updated At")</label>
                    <div class="col-lg-8">
                        <input type="text" name="updated_at" class="form-control" required="" value="{{ $deposit['updated_at_format'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Status")</label>
                    <div class="col-lg-8">
                        <input type="text" name="status" class="form-control" required="" value="{{ $deposit['status'] }}" readonly>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Top Up Id</label>
                    <div class="col-lg-8">
                        <input type="text" name="top_up_id" class="form-control" required="" value="{{ $deposit['code'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Bank Transfer</label>
                    <div class="col-lg-8">
                        <input type="text" name="bank_transfer" class="form-control" required="" value="{{ $deposit['bank']['name'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Amount")</label>
                    <div class="col-lg-8">
                        <input type="text" name="amount" class="form-control" required="" value="{{ number_format($deposit['amount'], 0, ',', '.') }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Created At")</label>
                    <div class="col-lg-8">
                        <input type="text" name="created_at" class="form-control" required="" value="{{ $deposit['created_at_format'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">User Name</label>
                    <div class="col-lg-8">
                        <input type="text" name="user_name" class="form-control" required="" value="{{ $deposit['entity']['name'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Tag</label>
                    <div class="col-lg-8">
                        <input type="text" name="tag" class="form-control" required="" value="{{ ucfirst(strtolower($deposit['entity_type'])) }}" readonly>
                    </div>
                </div>
            </div>
        </div>
        @hasaccess('MONEY_TRANSFER.TRANSFER_PROXIES.VIEW')
            @if (! empty($deposit['bank_transfers']))
            <hr>
            <h4>@lang('cms.Proxy List')</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ __("cms.Code") }}</th>
                            <th>{{ __("cms.Provider Transaction Code") }}</th>
                            <th>{{ __("cms.Source Bank") }}</th>
                            <th>{{ __("cms.Destination Bank") }}</th>
                            <th>{{ __("cms.Amount") }}</th>
                            <th>{{ __("cms.Category") }}</th>
                            <th>{{ __("cms.Created At") }}</th>
                            <th>{{ __("cms.Status") }}</th>
                            <th>{{ __("cms.Actions") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deposit['bank_transfers'] as $trx)
                        <tr>
                            <td>
                                <a href="{{ route('money_transfer.transfer_proxies.show', ['code' => $trx['code']]) }}" target="_blank">
                                    {{ $trx['code'] }}
                                </a>
                            </td>
                            <td>{{ $trx['provider_trx_code'] }}</td>
                            <td>{{ $trx['source_bank'] }}</td>
                            <td>{{ $trx['destination_bank'] }}</td>
                            <td>{{ number_format($trx['amount'], 0, ',', '.') }}</td>
                            <td>{{ $trx['action'] }}</td>
                            <td>{{ $trx['created_at'] }}</td>
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
                                        @hasaccess('MONEY_TRANSFER.TRANSFER_PROXIES.UPDATE')
                                            <div class="d-flex flex-column flex-md-row align-items-md-center">
                                                @if(in_array($trx['status'], ['FAILED']))
                                                    <div class="px-1 mb-3 mb-md-0">
                                                        <button
                                                            type="button"
                                                            id="btnProxyRetry"
                                                            class="btn btn-warning w-100 w-sm-auto"
                                                            data-toggle="modal"
                                                            data-target="#warningModal"
                                                            data-action="{{ route('money_transfer.transfer_proxies.update', ['id' => $trx['id']]) }}"
                                                            data-label="Retry">
                                                            Retry
                                                        </button>
                                                    </div>
                                                    <div class="px-1 mb-3 mb-md-0">
                                                        <button
                                                            type="button"
                                                            id="btnProxySuccess"
                                                            class="btn btn-success w-100 w-sm-auto"
                                                            data-toggle="modal"
                                                            data-target="#warningModal"
                                                            data-action="{{ route('money_transfer.transfer_proxies.update', ['id' => $trx['id']]) }}"
                                                            data-label="Mark As Success">
                                                            Mark As Success
                                                        </button>
                                                    </div>
                                                @else
                                                    <a href="{{ route('money_transfer.transfer_proxies.show', ['code' => $trx['code']]) }}" class="dropdown-item">
                                                        <i class="icon-search4"></i> {{ __("cms.Detail") }}
                                                    </a>
                                                @endif
                                            </div>
                                        @elseaccess
                                            <a href="{{ route('money_transfer.transfer_proxies.show', ['code' => $trx['code']]) }}" class="dropdown-item">
                                                <i class="icon-search4"></i> {{ __("cms.Detail") }}
                                            </a>
                                        @endhasaccess
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        @endhasaccess
    </x-page.content>

    @push('styles')
    <style>
        .table {
            white-space: nowrap;
        }
    </style>
    @endpush


    @push('scripts')
    <script>
        function setDataModal(btnLabel, route) {
            $("#actionBody").html(btnLabel);
            $("#actionBtn").html(btnLabel);
            $("#formWarningModal").attr('action', route);
        }

        $(document).ready(function() {
            const btnListIdForModalWarning = ["btnRetry", "btnMarkAsSuccess", "btnUpdate"];

            $('#' + btnListIdForModalWarning.join(',#')).click(function(){
                setDataModal($(this).data('label'), $(this).data('action'));
            });

            $('#formWarningModal').submit(function() {
                $(this).find(':button[type=submit]').prop('disabled', true);
                $(this).find(':button[type=submit]').html('Loading..');
            })
        });
    </script>
    @endpush
</x-app-layout>
