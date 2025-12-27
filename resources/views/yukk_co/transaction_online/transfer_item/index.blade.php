<x-app-layout>
    <x-page.header :title="__('cms.ONLINE (YUKK) TRANSFER TO YUKKINGS/CUSTOMER')">
        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            <x-breadcrumb.active>@lang("cms.ONLINE (YUKK) TRANSFER TO YUKKINGS/CUSTOMER")</x-breadcrumb.active>
        </x-slot>
    </x-page.header>

    <x-page.content :title="__('cms.ONLINE (YUKK) TRANSFER TO YUKKINGS/CUSTOMER')">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Source Acount Number</th>
                        <th>Beneficiary Name</th>
                        <th>Beneficiary Number</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Updated at</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item['code'] }}</td>
                            <td>{{ $item['source_account_number'] }}</td>
                            <td>{{ $item['beneficiary_name'] }}</td>
                            <td>{{ $item['destination_account_number'] }}</td>
                            <td>{{ number_format($item['amount'], 2) }}</td>
                            <td>{{ $item['status']}}</td>
                            <td>{{ date("Y-m-d", strtotime($item['updated_at'])) }}</td>
                            <td class="text-center">
                                @if ($item['status'] == 'SUCCESS' && $item['action'] != 'TO_BENEFICIARY')
                                @elseif ($item['action'] == 'TO_BENEFICIARY' || $item['retry_count'] < 3)
                                    <div class="dropdown position-static">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false">
                                            <i class="icon-menu9"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            @if (preg_match('/^FAILED/', $item['status']) && $item['retry_count'] < 3)
                                                <a class="dropdown-item" data-toggle="modal" data-target="#retry-{{ $item['id'] }}">
                                                    Retry
                                                </a>
                                            @endif

                                            @if ($item['action'] == 'TO_BENEFICIARY')
                                                <a href="{{
                                                    url('yukk-co/transaction-online/transfer-items'). '/' .$item['id'] . '/transactions' }}" target="_blank" class="dropdown-item">
                                                    Details
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <x-slot name="footer">
            <div style="overflow-x: auto;">
                <div class="d-md-flex justify-content-md-end">
                    {!! $items->links() !!}
                </div>
            </div>
        </x-slot>

        @foreach($items as $item)
            @if (preg_match('/^FAILED/', $item['status']) && $item['retry_count'] < 3)
            <div class="modal fade" id="retry-{{ $item['id'] }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <form action="{{ url('yukk-co/transaction-online/transfer-items/'. $item['id'] . '/retry') }}" method="post">
                    @csrf
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Confirm Retry</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Before you click retry, please check the mutation and data in advanced
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Confirm</button>
                        </div>
                        </div>
                    </div>
                </form>
            </div>
            @endif
        @endforeach

    </x-page.content>

    @swal
</x-app-layout>
