<x-app-layout>
    <x-page.header :title="__('cms.Transaction Detail')">
        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            <x-breadcrumb.link :link="route('money_transfer.transactions.index')" :text="__('cms.Transaction')"/>
            <x-breadcrumb.active>{{ __("cms.Detail") }}</x-breadcrumb.active>
        </x-slot>

        <x-slot name="actions">
        @hasaccess('MONEY_TRANSFER.TRANSACTIONS_UPDATE')
            <div class="d-flex flex-column flex-md-row align-items-md-center">
                @if(
                    $transaction['canRetry']
                    && (
                        (in_array($transaction['status'], ['HOLDING.DISBURSEMENT', 'HOLDING.INSUFFICIENT_BALANCE'])
                            && in_array($transaction['provider']['code'], ['FLIP', 'CIMB', 'BCA'])
                        ) || (in_array($transaction['status'], ['HOLDING.DISBURSEMENT'])
                            && in_array($transaction['provider']['code'], ['DANA'])
                        )
                    )
                )
                    <div class="px-1 mb-3 mb-md-0">
                        <button
                            type="button"
                            id="btnRetry"
                            class="btn btn-warning w-100 w-sm-auto"
                            data-toggle="modal"
                            data-target="#warningModal"
                            data-action="{{ route('money_transfer.transactions.update', ['id' => $transaction['id']]) }}"
                            data-label="Retry">
                            <i class="icon-spinner11 mr-2"></i> Retry
                        </button>
                    </div>
                @endif

                @if(
                    (in_array($transaction['status'], ['HOLDING.DISBURSEMENT', 'HOLDING.INSUFFICIENT_BALANCE', 'HOLDING']) && $transaction['provider']['code'] == 'CIMB')
                    || (in_array($transaction['status'], ['HOLDING.DISBURSEMENT']) && $transaction['provider']['code'] == 'DANA')
                )
                    <div class="px-1 mb-3 mb-md-0">
                        <button
                            type="button"
                            id="btnFailed"
                            class="btn btn-danger w-100 w-sm-auto"
                            data-toggle="modal"
                            data-target="#warningModal"
                            data-action="{{ route('money_transfer.transactions.update', ['id' => $transaction['id']]) }}"
                            data-label="Mark As Failed">
                            <i class="icon-spinner11 mr-2"></i> Mark As Failed
                        </button>
                    </div>
                @endif

                @if(in_array($transaction['status'], ['HOLDING.DISBURSEMENT', 'HOLDING.INSUFFICIENT_BALANCE', 'ONGOING.WAITING_PROVIDER_UPDATE'])
                    && ! in_array($transaction['provider']['code'], ['DANA'])
                )
                    <div class="px-1 mb-3 mb-md-0">
                        <button
                            type="button"
                            id="btnUpdate"
                            class="btn btn-primary w-100 w-sm-auto"
                            data-toggle="modal"
                            data-target="#warningModal"
                            data-action="{{ route('money_transfer.transactions.update', ['id' => $transaction['id']]) }}"
                            data-label="Update">
                            <i class="icon-spinner11 mr-2"></i> Update
                        </button>
                    </div>
                @endif

                @if(in_array($transaction['status'], ['HOLDING.DISBURSEMENT'])
                    && in_array($transaction['provider']['code'], ['BCA','DANA']) 
                )
                    <div class="px-1 mb-3 mb-md-0">
                        <button
                            type="button"
                            id="btnSuccess"
                            class="btn btn-success w-100 w-sm-auto"
                            data-toggle="modal"
                            data-target="#warningModal"
                            data-action="{{ route('money_transfer.transactions.update', ['id' => $transaction['id']]) }}"
                            data-label="Mark As Success">
                            <i class="icon-spinner11 mr-2"></i> Mark As Success
                        </button>
                    </div>
                @endif
            </div>
        @endhasaccess
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

        <div class="form-control mb-3">
            Click <a href="{{ route('money_transfer.transactions.items.logs', ['code' => $transaction['code'], 'provider' => $transaction['provider']['code']]) }}">here</a> to show the log
            or <a href="{{ route('money_transfer.transactions.items.logs', ['code' => $transaction['code'], 'provider' => $transaction['provider']['code'], 'type' => 'error']) }}">here</a> to show the <b>error</b> log.
        </div>
        
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("Code")</label>
                    <div class="col-lg-8">
                        <x-form.readonly-input-or-link 
                            item="{{ $transaction['code'] }}" 
                            name="code" 
                            url="{{ route('money_transfer.transactions.items.show', ['code' => $transaction['code']]) . '?from=batch' }}" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Provider Transaction Id</label>
                    <div class="col-lg-8">
                        <input type="text" name="provider_transaction_id" class="form-control" required="" value="{{ $transaction['provider_transaction_id'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("Beneficiary Bank Type")</label>
                    <div class="col-lg-8">
                        <input type="text" name="bank_account_name" class="form-control" required="" value="{{ @$transaction['bank']['name'] ?? ''}}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("Beneficiary Bank Code")</label>
                    <div class="col-lg-8">
                        <input type="text" name="bank_code" class="form-control" required="" value="{{ $transaction['bank_code'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Disbursement Fee")</label>
                    <div class="col-lg-8">
                        <input type="text" name="fee" class="form-control" required="" value="{{ number_format($transaction['fee'], 0, ',', '.') }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Amount")</label>
                    <div class="col-lg-8">
                        <input type="text" name="amount" class="form-control" required="" value="{{ number_format($transaction['amount'], 0, ',', '.') }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">User Name</label>
                    <div class="col-lg-8">
                        <input type="text" name="entity_name" class="form-control" required="" value="{{ @$transaction['transaction']['entity']['name'] ?? '' }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Status")</label>
                    <div class="col-lg-8">
                        <input type="text" name="status" class="form-control" required="" value="{{ $transaction['status'] }}" readonly>
                    </div>
                </div>
                @if ($transaction['status'] == "FAILED")
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">@lang("cms.Reason")</label>
                        <div class="col-lg-8">
                            <input type="text" name="status" class="form-control" required="" value="{{ $transaction['reason'] }}" readonly>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-lg-6">
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Transaction Group Code</label>
                    <div class="col-lg-8">
                        <x-form.readonly-input-or-link 
                            item="{{ $transaction['group']['code'] ?? '' }}" 
                            name="transaction_code" 
                            url="{{ route('money_transfer.transactions.groups.show', ['code' => $transaction['group']['code'] ?? 0]) }}" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("Beneficiary Name")</label>
                    <div class="col-lg-8">
                        <input type="text" name="recipient_name" class="form-control" required="" value="{{ $transaction['recipient_name'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Bank Account Number")</label>
                    <div class="col-lg-8">
                        <input type="text" name="account_number" class="form-control" required="" value="{{ $transaction['account_number'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Provider")</label>
                    <div class="col-lg-8">
                        <input type="text" name="tag" class="form-control" required="" value="{{ $transaction['provider']['name'] ?? ''}}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Provider Fee")</label>
                    <div class="col-lg-8">
                        <input type="text" name="provider_fee" class="form-control" required="" value="{{ number_format($transaction['provider_fee'], 0, ',', '.') }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Total")</label>
                    <div class="col-lg-8">
                        <input type="text" name="total" class="form-control" required="" value="{{ number_format($transaction['total'], 0, ',', '.') }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Created At")</label>
                    <div class="col-lg-8">
                        <input type="text" name="created_at" class="form-control" required="" value="{{ $transaction['created_at_formatted'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Updated At")</label>
                    <div class="col-lg-8">
                        <input type="text" name="updated_at" class="form-control" required="" value="{{ $transaction['updated_at_formatted'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Tag")</label>
                    <div class="col-lg-8">
                        <input type="text" name="tag" class="form-control" required="" value="{{ ucfirst(strtolower($transaction['transaction']['entity_type'] ?? '')) }}" readonly>
                    </div>
                </div>
            </div>
        </div>
        @hasaccess('MONEY_TRANSFER.TRANSFER_PROXIES.VIEW')
            @if ($transaction['status'] == 'FAILED' && ! empty($transaction['bank_transfer']))
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
                        <tr>
                            <td>
                                <a href="{{ route('money_transfer.transfer_proxies.show', ['code' => $transaction['bank_transfer']['code']]) }}" target="_blank">
                                    {{ $transaction['bank_transfer']['code'] }}
                                </a>
                            </td>
                            <td>{{ $transaction['bank_transfer']['provider_trx_code'] }}</td>
                            <td>{{ $transaction['bank_transfer']['source_bank'] }}</td>
                            <td>{{ $transaction['bank_transfer']['destination_bank'] }}</td>
                            <td>{{ number_format($transaction['bank_transfer']['amount'], 0, ',', '.') }}</td>
                            <td>{{ $transaction['bank_transfer']['action'] }}</td>
                            <td>{{ $transaction['bank_transfer']['created_at'] }}</td>
                            <td>
                                <span class="badge
                                    @if ($transaction['bank_transfer']['status'] == 'SUCCESS')
                                        bg-success
                                    @elseif (in_array($transaction['bank_transfer']['status'], ['PENDING', 'ONGOING']))
                                        bg-secondary
                                    @else
                                        bg-danger
                                    @endif
                                ">{{ $transaction['bank_transfer']['status'] }}</span>
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
                                                @if(in_array($transaction['bank_transfer']['status'], ['FAILED']))
                                                    <div class="px-1 mb-3 mb-md-0">
                                                        <button
                                                            type="button"
                                                            id="btnProxyRetry"
                                                            class="btn btn-warning w-100 w-sm-auto"
                                                            data-toggle="modal"
                                                            data-target="#warningModal"
                                                            data-action="{{ route('money_transfer.transfer_proxies.update', ['id' => $transaction['bank_transfer']['id']]) }}"
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
                                                            data-action="{{ route('money_transfer.transfer_proxies.update', ['id' => $transaction['bank_transfer']['id']]) }}"
                                                            data-label="Mark As Success">
                                                            Mark As Success
                                                        </button>
                                                    </div>
                                                @else
                                                    <a href="{{ route('money_transfer.transfer_proxies.show', ['code' => $transaction['bank_transfer']['code']]) }}" class="dropdown-item">
                                                        <i class="icon-search4"></i> {{ __("cms.Detail") }}
                                                    </a>
                                                @endif
                                            </div>
                                        @elseaccess
                                            <a href="{{ route('money_transfer.transfer_proxies.show', ['code' => $transaction['bank_transfer']['code']]) }}" class="dropdown-item">
                                                <i class="icon-search4"></i> {{ __("cms.Detail") }}
                                            </a>
                                        @endhasaccess
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @endif
        @endhasaccess
    </x-page.content>
    
    @swal

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
            $("#inputRetry").val(btnLabel == 'Retry' ? 1 : 0);
            $("#inputSuccess").val(btnLabel == 'Mark As Success' ? 1 : 0);
            $("#inputFailed").val(btnLabel == 'Mark As Failed' ? 1 : 0);
        }

        $(document).ready(function() {
            const btnListIdForModalWarning = ["btnUpdate", "btnRetry", "btnSuccess", "btnFailed", "btnProxyRetry", "btnProxySuccess"];

            $("#" + btnListIdForModalWarning.join(",#")).click(function(){
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
