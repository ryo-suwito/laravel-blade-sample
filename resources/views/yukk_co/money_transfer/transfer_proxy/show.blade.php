<x-app-layout>
    <x-page.header :title="__('cms.Proxy Detail')">
        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            <x-breadcrumb.link :link="route('money_transfer.transfer_proxies.index')" :text="__('cms.Proxy')"/>
            <x-breadcrumb.active>{{ __("cms.Detail") }}</x-breadcrumb.active>
        </x-slot>

        <x-slot name="actions">
        @hasaccess('MONEY_TRANSFER.TRANSFER_PROXIES.UPDATE')
            <div class="d-flex flex-column flex-md-row align-items-md-center">
                @if(in_array($trx['status'], ['FAILED']))
                    <div class="px-1 mb-3 mb-md-0">
                        <button
                            type="button"
                            id="btnUpdate"
                            class="btn btn-primary w-100 w-sm-auto"
                            data-toggle="modal"
                            data-target="#warningModal"
                            data-action="{{ route('money_transfer.transfer_proxies.update', ['id' => $trx['id']]) }}"
                            data-label="Update">
                            <i class="icon-spinner11 mr-2"></i> Update
                        </button>
                    </div>
                    <div class="px-1 mb-3 mb-md-0">
                        <button
                            type="button"
                            id="btnRetry"
                            class="btn btn-warning w-100 w-sm-auto"
                            data-toggle="modal"
                            data-target="#warningModal"
                            data-action="{{ route('money_transfer.transfer_proxies.update', ['id' => $trx['id']]) }}"
                            data-label="Retry">
                            <i class="icon-spinner11 mr-2"></i> Retry
                        </button>
                    </div>
                    <div class="px-1 mb-3 mb-md-0">
                        <button
                            type="button"
                            id="btnSuccess"
                            class="btn btn-success w-100 w-sm-auto"
                            data-toggle="modal"
                            data-target="#warningModal"
                            data-action="{{ route('money_transfer.transfer_proxies.update', ['id' => $trx['id']]) }}"
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
            Click <a href="{{ route('money_transfer.transfer_proxies.logs', ['code' => $trx['code'], 'provider' => $trx['group']['provider']['code']]) }}">here</a> to show the log
            or <a href="{{ route('money_transfer.transfer_proxies.logs', ['code' => $trx['code'], 'provider' => $trx['group']['provider']['code'], 'type' => 'error']) }}">here</a> to show the <b>error</b> log.
        </div>

        <div class="row">
            <div class="col-lg-5">
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("Code")</label>
                    <div class="col-lg-8">
                        <input type="text" name="code" class="form-control" required="" value="{{ $trx['code'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Group Transaction")</label>
                    <div class="col-lg-8">
                        <x-form.readonly-input-or-link 
                            item="{{ $trx['group']['code'] ?? '' }}" 
                            name="group_code" 
                            url="{{ route('money_transfer.transactions.groups.show', ['code' => $trx['group']['code'] ?? 0]) }}" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Ref Code")</label>
                    <div class="col-lg-8">
                            @if (str_contains($trx['ref']['code'], 'DBS'))
                            <x-form.readonly-input-or-link 
                                item="{{ $trx['ref']['code'] ?? '' }}" 
                                name="ref_code" 
                                url="{{ route('money_transfer.transactions.items.show', ['code' => $trx['ref']['code'] ?? 0]) }}"/>
                            @else
                            <x-form.readonly-input-or-link 
                                item="{{ $trx['ref']['code'] ?? '' }}" 
                                name="ref_code" 
                                url="{{ route('money_transfer.provider_deposits.detail', ['id' => $trx['ref']['id'] ?? 0]) }}"/>
                            @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Provider Transaction Code")</label>
                    <div class="col-lg-8">
                        <input type="text" name="provider_transaction_code" class="form-control" required="" value="{{ $trx['provider_trx_code'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Amount")</label>
                    <div class="col-lg-8">
                        <input type="text" name="amount" class="form-control" required="" value="{{ number_format($trx['amount'], 0, ',', '.') }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Provider")</label>
                    <div class="col-lg-8">
                        <input type="text" name="provider" class="form-control" required="" value="{{ $trx['group']['provider']['name'] }}"" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Provider Fee")</label>
                    <div class="col-lg-8">
                        <input type="text" name="provider_fee" class="form-control" required="" value="{{ number_format($trx['fee'], 0, ',', '.') }}"" readonly>
                    </div>
                </div>
                @if ($trx['status'] == "FAILED")
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">@lang("cms.Reason")</label>
                        <div class="col-lg-8">
                            <input type="text" name="status" class="form-control" required="" value="{{ $trx['reason'] }}" readonly>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-lg-7">
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Source Bank")</label>
                    <div class="col-lg-8">
                        <input type="text" name="source_bank" class="form-control" required="" value="{{ $trx['source_bank'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Source Account Number")</label>
                    <div class="col-lg-8">
                        <input type="text" name="source_account_number" class="form-control" required="" value="{{ $trx['source_account_number'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Destination Bank")</label>
                    <div class="col-lg-8">
                        <input type="text" name="destionation_bank" class="form-control" required="" value="{{ $trx['destination_bank'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Destination Account Number")</label>
                    <div class="col-lg-8">
                        <input type="text" name="destination_account_number" class="form-control" required="" value="{{ $trx['destination_account_number'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Category")</label>
                    <div class="col-lg-8">
                        <input type="text" name="action" class="form-control" required="" value="{{ $trx['action'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Created At")</label>
                    <div class="col-lg-8">
                        <input type="text" name="created_at" class="form-control" required="" value="{{ $trx['created_at_formatted'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Updated At")</label>
                    <div class="col-lg-8">
                        <input type="text" name="updated_at" class="form-control" required="" value="{{ $trx['updated_at_formatted'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Status")</label>
                    <div class="col-lg-8">
                        <input type="text" name="status" class="form-control" required="" value="{{ $trx['status'] }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </x-page.content>

    @swal

    @push('scripts')
    <script>
        function setDataModal(btnLabel, route) {
            $("#actionBody").html(btnLabel);
            $("#actionBtn").html(btnLabel);
            $("#formWarningModal").attr('action', route);
            $("#inputRetry").val(btnLabel == 'Retry' ? 1 : 0);
            $("#inputSuccess").val(btnLabel == 'Mark As Success' ? 1 : 0);
        }

        $(document).ready(function() {
            const btnListIdForModalWarning = ["btnRetry", "btnSuccess", "btnUpdate",];

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
