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
                        <th>No</th>
                        <th>Customer Name</th>
                        <th>Customer Brach Name</th>
                        <th>Customer Email</th>
                        <th>Account Number</th>
                        <th>Yukk Portion</th>
                        <th>Amount</th>
                        <th>Payment Methode</th>
                        <th>Transaction Time</th>
                        <th>Fullfill Time</th>
                        <th>Transaction Code</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $transaction['customer']['name'] }}</td>
                        <td>{{ $transaction['customer']['branch_name'] ?? $transaction['customer']['account_branch_name'] }}</td>
                        <td>{{ $transaction['customer']['email'] ?? $transaction['customer']['email_list'] }}</td>
                        <td>{{ $transaction['customer']['account_number'] }}</td>
                        <td>
                            {{ 'yukk_co_portion = ' . number_format($transaction['yukk_co_portion'], 2) }} <p>
                            {{ 'yukk_co_portion_type = ' . $transaction['yukk_co_portion_type'] }} <p>
                            {{ 'yukk_co_portion_total = '. number_format($transaction['margin'], 2) }}
                        </td>
                        <td>{{ $transaction['payment_method'] == 'YUKK' ? number_format($transaction['yukk_p_amount'] + $transaction['yukk_e_amount'], 2) : number_format($transaction['other_money'], 2) }}</td>
                        <td>{{ $transaction['payment_method'] }}</td>
                        <td>{{ $transaction['order_date'] }}</td>
                        <td>{{ $transaction['transaction_time'] }}</td>
                        <td>
                            {{ 'Journal Ref_code: '. ($transaction['aggregator_id'] == 3607 ? $transaction['order_id'] : $transaction['bill_code'] ) }}
                            <br>
                            {{ 'transaction code: '. $transaction['transaction_code'] }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <x-slot name="footer">
            <div style="overflow-x: auto;">
                <div class="d-md-flex justify-content-md-end">
                {!! $transactions->links() !!}
                </div>
            </div>
        </x-slot>
    </x-page.content>

    @swal
</x-app-layout>
