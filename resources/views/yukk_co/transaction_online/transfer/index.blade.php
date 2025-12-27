<x-app-layout>
    <x-page.header :title="__('cms.ONLINE (YUKK) TRANSFER TO YUKKINGS/CUSTOMER')">
        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            <x-breadcrumb.active>@lang("cms.ONLINE (YUKK) TRANSFER TO YUKKINGS/CUSTOMER")</x-breadcrumb.active>
        </x-slot>
    </x-page.header>

    <x-page.content :title="__('cms.ONLINE (YUKK) TRANSFER TO YUKKINGS/CUSTOMER')">
        <div class="ml-2">
            <form action="{{ url('yukk-co/transaction-online/transfers') }}" method="get">
                <div class="form-group row">
                    <label class="col-form-label" for="date">Date</label>
                    <div class="col-sm-2">
                        <input type="date" value="{{ request('date') }}" name="date" class="form-control bg-white text-dark">
                    </div>
                    <button type="submit" class="btn btn-primary px-3">submit</button>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Created at</th>
                        <th>Updated at</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($transfers as $transfer)
                        <tr>
                            <td>{{ $transfer['code'] }}</td>
                            <td>{{ $transfer['date'] }}</td>
                            <td>{{ $transfer['amount'] }}</td>
                            <td>{{ $transfer['status'] }}</td>
                            <td>{{ $transfer['created_at'] }}</td>
                            <td>{{ $transfer['updated_at'] }}</td>
                            <td>
                                <a href="{{ route('yukk-co.transaction-online.transfer-items.index', ['transfer_id' => $transfer['id']]) }}" class="btn btn-primary">Details</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <x-slot name="footer">
            <div style="overflow-x: auto;">
                <div class="d-md-flex justify-content-md-end">
                    {!! $transfers->links() !!}
                </div>
            </div>
        </x-slot>
    </x-page.content>

    @swal
</x-app-layout>
