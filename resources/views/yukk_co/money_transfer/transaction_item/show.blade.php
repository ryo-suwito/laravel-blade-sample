<x-app-layout>
    <x-page.header :title="__('cms.Transaction List Detail')">
        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            @if ($clickFrom == 'batch')
                <x-breadcrumb.link :link="route('money_transfer.transactions.batches.index')" :text="__('cms.Batch Group')"/>
                <x-breadcrumb.link :link="route('money_transfer.transactions.batches.show', ['code' => $item['transaction']['code']])" :text="__('cms.Batch Group Detail')"/>
            @else
                <x-breadcrumb.link :link="route('money_transfer.transactions.groups.index')" :text="__('cms.Provider Group')"/>
                <x-breadcrumb.link :link="route('money_transfer.transactions.groups.show', ['code' => $item['group']['code']])" :text="__('cms.Provider Group Detail')"/>
            @endif
            <x-breadcrumb.active>{{ __("cms.Transaction List Detail") }}</x-breadcrumb.active>
        </x-slot>
    </x-page.header>

    <x-page.content>
        <div class="form-control mb-3">
            Click <a href="{{ route('money_transfer.transactions.items.logs', ['code' => $item['code'], 'provider' => $item['provider']['code']]) }}">here</a> to show the log
            or <a href="{{ route('money_transfer.transactions.items.logs', ['code' => $item['code'], 'provider' => $item['provider']['code'], 'type' => 'error']) }}">here</a> to show the <b>error</b> log.
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Code")</label>
                    <div class="col-lg-8">
                        <input type="text" name="code" class="form-control" required="" value="{{ $item['code'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Beneficiary Bank Type")</label>
                    <div class="col-lg-8">
                        <input type="text" name="bank_type" class="form-control" required="" value="{{ $item['bank']['name'] ?? '' }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Beneficiary Name")</label>
                    <div class="col-lg-8">
                        <input type="text" name="beneficiary_name" class="form-control" required="" value="{{ $item['recipient_name'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Bank Account Number")</label>
                    <div class="col-lg-8">
                        <input type="text" name="account_number" class="form-control" required="" value="{{ $item['account_number'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Amount")</label>
                    <div class="col-lg-8">
                        <input type="text" name="amount" class="form-control" required="" value="{{ number_format($item['amount'], 0, ',', '.') }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Disbursement Fee")</label>
                    <div class="col-lg-8">
                        <input type="text" name="fee" class="form-control" required="" value="{{ number_format($item['fee'], 0, ',', '.') }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Total")</label>
                    <div class="col-lg-8">
                        <input type="text" name="total" class="form-control" required="" value="{{ number_format($item['total'], 0, ',', '.') }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Provider Transaction ID")</label>
                    <div class="col-lg-8">
                        <input type="text" name="provider_transaction_id" class="form-control" required="" value="{{ $item['provider_transaction_id'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Provider Name")</label>
                    <div class="col-lg-8">
                        <input type="text" name="provider_name" class="form-control" required="" value="{{ $item['provider']['name'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Provider Fee")</label>
                    <div class="col-lg-8">
                        <input type="text" name="provider_fee" class="form-control" required="" value="{{ number_format($item['provider_fee'], 0, ',', '.') }}" readonly>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Idempotency Key")</label>
                    <div class="col-lg-8">
                        <input type="text" name="idempotency_key" class="form-control" required="" value="{{ $item['idempotency_key'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Order ID")</label>
                    <div class="col-lg-8">
                        <input type="text" name="order_id" class="form-control" required="" value="{{ $item['order_id'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Tag")</label>
                    <div class="col-lg-8">
                        <input type="text" name="tag" class="form-control" required="" value="{{ $item['transaction']['entity_type'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Username")</label>
                    <div class="col-lg-8">
                        <input type="text" name="username" class="form-control" required="" value="{{ $item['transaction']['entity']['name'] ?? '' }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Status")</label>
                    <div class="col-lg-8">
                        <input type="text" name="status" class="form-control" required="" value="{{ $item['status'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Reason")</label>
                    <div class="col-lg-8">
                        <input type="text" name="reason" class="form-control" required="" value="{{ $item['reason'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Remark")</label>
                    <div class="col-lg-8">
                        <input type="text" name="remark" class="form-control" required="" value="{{ $item['remark'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Email")</label>
                    <div class="col-lg-8">
                        <input type="text" name="email" class="form-control" required="" value="{{ $item['beneficiary_email'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Created By")</label>
                    <div class="col-lg-8">
                        <input type="text" name="created_by" class="form-control" required="" value="{{ $item['transaction']['created_by'] }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Created At")</label>
                    <div class="col-lg-8">
                        <input type="text" name="created_at" class="form-control" required="" value="{{ date_format(date_create($item['created_at']), 'd-m-Y H:i:s') }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">@lang("cms.Updated At")</label>
                    <div class="col-lg-8">
                        <input type="text" name="updated_at" class="form-control" required="" value="{{ date_format(date_create($item['updated_at']), 'd-m-Y H:i:s') }}" readonly>
                    </div>
                </div>
            </div>
        </div>

        @hasaccess('MONEY_TRANSFER.TRANSFER_PROXIES.VIEW')
            @if ($item['status'] == 'FAILED' && ! empty($item['bank_transfer']))
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
                                <a href="{{ route('money_transfer.transfer_proxies.show', ['code' => $item['bank_transfer']['code']]) }}" target="_blank">
                                    {{ $item['bank_transfer']['code'] }}
                                </a>
                            </td>
                            <td>{{ $item['bank_transfer']['provider_trx_code'] }}</td>
                            <td>{{ $item['bank_transfer']['source_bank'] }}</td>
                            <td>{{ $item['bank_transfer']['destination_bank'] }}</td>
                            <td>{{ number_format($item['bank_transfer']['amount'], 0, ',', '.') }}</td>
                            <td>{{ $item['bank_transfer']['action'] }}</td>
                            <td>{{ $item['bank_transfer']['created_at'] }}</td>
                            <td>
                                <span class="badge
                                    @if ($item['bank_transfer']['status'] == 'SUCCESS')
                                        bg-success
                                    @elseif (in_array($item['bank_transfer']['status'], ['PENDING', 'ONGOING']))
                                        bg-secondary
                                    @else
                                        bg-danger
                                    @endif
                                ">{{ $item['bank_transfer']['status'] }}</span>
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
                                                @if(in_array($item['bank_transfer']['status'], ['FAILED']))
                                                    <div class="px-1 mb-3 mb-md-0">
                                                        <button
                                                            type="button"
                                                            id="btnProxyRetry"
                                                            class="btn btn-warning w-100 w-sm-auto"
                                                            data-toggle="modal"
                                                            data-target="#warningModal"
                                                            data-action="{{ route('money_transfer.transfer_proxies.update', ['id' => $item['bank_transfer']['id']]) }}"
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
                                                            data-action="{{ route('money_transfer.transfer_proxies.update', ['id' => $item['bank_transfer']['id']]) }}"
                                                            data-label="Mark As Success">
                                                            Mark As Success
                                                        </button>
                                                    </div>
                                                @else
                                                    <a href="{{ route('money_transfer.transfer_proxies.show', ['code' => $item['bank_transfer']['code']]) }}" class="dropdown-item">
                                                        <i class="icon-search4"></i> {{ __("cms.Detail") }}
                                                    </a>
                                                @endif
                                            </div>
                                        @elseaccess
                                            <a href="{{ route('money_transfer.transfer_proxies.show', ['code' => $item['bank_transfer']['code']]) }}" class="dropdown-item">
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

        <hr>

        <h4 class="mb-4">{{ __('cms.Transaction') }}</h4>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __("cms.Reference ID") }}</th>
                        <th>{{ __("cms.Type") }}</th>
                        <th>{{ __("cms.Status") }}</th>
                        <th>{{ __("cms.Tag") }}</th>
                        <th>{{ __("cms.Created At") }}</th>
                        <th>{{ __("cms.Updated At") }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <a target="_blank" href="{{ route('money_transfer.transactions.batches.show', ['code' => $item['transaction']['code']]) }}">
                                {{ $item['transaction']['code'] }}
                            </a>
                        </td>
                        <td>{{ 'Batch Code' }}</td>
                        <td>
                            <span class="badge
                                @if ($item['transaction']['status'] == 'SUCCESS')
                                    bg-success
                                @else
                                    bg-secondary
                                @endif
                            ">{{ $item['transaction']['status'] }}</span>
                        </td>
                        <td>{{ $item['transaction']['entity_type'] }}</td>
                        <td>{{ date_format(date_create($item['transaction']['created_at']), 'd-m-Y H:i:s') }}</td>
                        <td>{{ date_format(date_create($item['transaction']['updated_at']), 'd-m-Y H:i:s') }}</td>
                    </tr>
                    @if (! empty($item['group']))
                    <tr>
                        <td>
                            <a target="_blank" href="{{ route('money_transfer.transactions.groups.show', ['code' => $item['group']['code']] ) }}">
                                {{ $item['group']['code'] }}
                            </a>
                        </td>
                        <td>{{ 'Group Code' }}</td>
                        <td>
                            <span class="badge
                                @if ($item['group']['status'] == 'SUCCESS')
                                    bg-success
                                @else
                                    bg-secondary
                                @endif
                            ">{{ $item['group']['status'] }}</span>
                        </td>
                        <td>{{ $item['transaction']['entity_type'] }}</td>
                        <td>{{ date_format(date_create($item['group']['created_at']), 'd-m-Y H:i:s') }}</td>
                        <td>{{ date_format(date_create($item['group']['updated_at']), 'd-m-Y H:i:s') }}</td>
                    </tr>
                    @endif
                    @if (! empty($item['group']['deposit']))
                        <tr>
                            <td>
                                <a target="_blank" href="{{ route('money_transfer.provider_deposits.detail', ['id' => $item['group']['deposit']['id']] ) }}">
                                    {{ $item['group']['deposit']['code'] }}
                                </a>
                            </td>
                            <td>{{ 'Top Up Code' }}</td>
                            <td>
                                <span class="badge
                                    @if ($item['group']['deposit']['status'] == 'SUCCESS')
                                        bg-success
                                    @else
                                        bg-secondary
                                    @endif
                                ">{{ $item['group']['deposit']['status'] }}</span>
                            </td>
                            <td>{{ $item['transaction']['entity_type'] }}</td>
                            <td>{{ date_format(date_create($item['group']['deposit']['created_at']), 'd-m-Y H:i:s') }}</td>
                            <td>{{ date_format(date_create($item['group']['deposit']['updated_at']), 'd-m-Y H:i:s') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <hr>

        <div class="d-flex mb-4">
            <h4 class="my-sm-auto">{{ __('cms.Webhook') }}</h4>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                @if (in_array($item['status'], ['SUCCESS', 'FAILED']) && count($item['event_logs']) < 15 && $item['transaction']['created_by'] == 'API')
                    <a href="{{ route('money_transfer.transactions.items.webhooks.send', ['code' => $item['code']]) }}">
                        <button class="btn btn-success" id="btn-resend">
                            Resend Callback
                        </button>
                    </a>
                @endif
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __("cms.No") }}</th>
                        <th>{{ __("cms.URL") }}</th>
                        <th>{{ __("cms.Status Code") }}</th>
                        <th>{{ __("cms.Timestamp") }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($item['event_logs']) == 0)
                        <tr>
                            <td colspan="4">Data Not Found</td>
                        </tr>
                    @endif
                    @foreach ($item['event_logs'] as $key => $log)
                        @if (empty($log['target']))
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td colspan="3"> In Progress...</td>
                        </tr>
                        @else
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $log['target'] }}</td>
                            <td>{{ $log['status'] }}</td>
                            <td>{{ date_format(date_create($log['sent_at']), 'd-m-Y H:i:s') }}</td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
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
        $('#btn-resend').on('click', function(e) {
            $(this).text('Loading..');
            $(this).prop('disabled', true);
        })
    </script>

    @endpush

</x-app-layout>
