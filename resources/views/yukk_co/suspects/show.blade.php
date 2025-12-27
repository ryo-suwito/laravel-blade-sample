<x-app-layout>
    <x-page.header :title="__('cms.Suspect')">
        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            <x-breadcrumb.link :link="route('yukk_co.suspects.index')" :text="__('cms.Suspect')" />
            <x-breadcrumb.active>
                {{ __('cms.Detail') }}
            </x-breadcrumb.active>
        </x-slot>
    </x-page.header>

    <x-page.content>
        <div>
            <form method="POST" id="form_edit" action="{{ route('yukk_co.suspects.update', $suspect['id']) }}"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-sm-12 col-lg-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12 col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <label class="col-lg-4 col-form-label"
                                                                for="name">@lang('cms.Attempt Date')</label>
                                                            <div class="col-lg-8">
                                                                <input type="text" class="form-control"
                                                                    value="{{ $suspect['last_attempted_at'] }}"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <label class="col-lg-4 col-form-label"
                                                                for="name">@lang('cms.Source')</label>
                                                            <div class="col-lg-8">
                                                                <input type="text" class="form-control"
                                                                    value="{{ $suspect['last_source'] }}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <label class="col-lg-4 col-form-label"
                                                                for="name">@lang('cms.Destination')</label>
                                                            <div class="col-lg-8">
                                                                <input type="text" class="form-control"
                                                                    value="{{ $suspect['last_destination'] }}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <label class="col-lg-4 col-form-label"
                                                                for="name">@lang('cms.Attempt Count')</label>
                                                            <div class="col-lg-8">
                                                                <input type="text" class="form-control"
                                                                    value="{{ $suspect['attempt'] }}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-lg-6">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label"
                                                        for="name">@lang('cms.YUKK ID')</label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control"
                                                            value="{{ $suspect['suspectable']['yukk_id'] }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label"
                                                        for="name">@lang('cms.Referral ID')</label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control"
                                                            value="{{ $suspect['suspectable']['referral_id'] }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label"
                                                        for="name">@lang('cms.Name')</label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control"
                                                            value="{{ $suspect['suspectable']['fullname'] }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label"
                                                        for="name">@lang('cms.Email')</label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control"
                                                            value="{{ $suspect['suspectable']['email'] }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label"
                                                        for="name">@lang('cms.YUKK Cash')</label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control"
                                                            value="{{ $suspect['suspectable']['yukk_p'] }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label"
                                                        for="name">@lang('cms.BI Status')</label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control"
                                                            value="{{ $suspect['suspectable']['bi_status'] }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label"
                                                        for="name">@lang('cms.ID Number')</label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control"
                                                            value="{{ $suspect['suspectable']['id_number'] }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label">@lang('cms.Thumbnail')</label>
                                                    <div class="col-lg-8">
                                                        <img src="{{ $suspect['suspectable']['self_photo'] }}"
                                                            alt="" style="max-width: 100%;max-height: 100%;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-lg-6">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label"
                                                        for="name">@lang('cms.Username')</label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control"
                                                            value="{{ $suspect['suspectable']['username'] }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label"
                                                        for="name">@lang('cms.Phone Number')</label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control"
                                                            value="{{ $suspect['suspectable']['phone'] }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label"
                                                        for="name">@lang('cms.Gender')</label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control"
                                                            value="{{ $suspect['suspectable']['gender'] }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label"
                                                        for="name">@lang('cms.YUKK Points')</label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control"
                                                            value="{{ $suspect['suspectable']['yukk_e'] }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label"
                                                        for="name">@lang('cms.Email Verification Status')</label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control"
                                                            value="{{ $suspect['suspectable']['email_verification_status'] }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label"
                                                        for="name">@lang('cms.Status')</label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control"
                                                            value="{{ $suspect['suspectable']['status'] }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-lg-12">
                        <div class="row">
                            <div class="col-sm col-lg">
                                <a href="{{ route('yukk_co.suspects.index') }}" class="btn btn-block btn-secondary">
                                    @lang('cms.Go Back')
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </x-page.content>

    <x-page.content>
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('cms.Attempt Log') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped dataTable">
                                        <thead>
                                            <tr>
                                                <th>{{ __('cms.Attempt Date') }}</th>
                                                <th>{{ __('cms.Amount') }}</th>
                                                <th>{{ __('cms.Source') }}</th>
                                                <th>{{ __('cms.Destination') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($suspect_log['data'])
                                                @foreach ($suspect_log['data'] as $item)
                                                    <tr>
                                                        <td>{{ $item['created_at'] }}</td>
                                                        <td>{{ $item['amount'] }}</td>
                                                        <td>{{ $item['last_activity'] }}</td>
                                                        <td>{{ $item['current_activity'] }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr class="text-center">
                                                    <td colspan="9"> Data Not Found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="form-group ml-2 mt-1">
                                        {{ 'Showing ' . $suspect_log['from'] . ' to ' . $suspect_log['to'] . ' of ' . $suspect_log['total'] . ' entries' }}
                                    </div>
                                    <div class="col-1">
                                        <form action="{{ route('yukk_co.suspects.edit', $suspect['id']) }}"
                                            method="GET">
                                            <select class="select2 form-control" name="per_page_log"
                                                onchange='if(this.value != 0) { this.form.submit(); }'>
                                                <option @if ($suspect_log['per_page'] == 10) selected @endif>10</option>
                                                <option @if ($suspect_log['per_page'] == 25) selected @endif>25</option>
                                                <option @if ($suspect_log['per_page'] == 50) selected @endif>50</option>
                                                <option @if ($suspect_log['per_page'] == 100) selected @endif>100</option>
                                            </select>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <ul class="pagination pagination-flat justify-content-end">
                                            @php($plus_minus_range = 3)
                                            @if ($suspect_log['current_page'] == 1)
                                                <li class="page-item disabled"><a href="#" class="page-link"><i
                                                            class="icon-arrow-left12"></i></a></li>
                                            @else
                                                <li class="page-item">
                                                    <a href="{{ route('yukk_co.suspects.edit', array_merge(['id' => $suspect['id']], request()->all(), ['page' => $suspect_log['current_page'] - 1])) }}"
                                                        class="page-link"><i class="icon-arrow-left12"></i></a>
                                                </li>
                                            @endif
                                            @if ($suspect_log['current_page'] - $plus_minus_range > 1)
                                                <li class="page-item disabled"><a href="#"
                                                        class="page-link">...</a></li>
                                            @endif
                                            @for ($i = max(1, $suspect_log['current_page'] - $plus_minus_range); $i <= min($suspect_log['current_page'] + $plus_minus_range, $suspect_log['last_page']); $i++)
                                                @if ($i == $suspect_log['current_page'])
                                                    <li class="page-item active"><a href="#"
                                                            class="page-link">{{ $i }}</a></li>
                                                @else
                                                    <li class="page-item">
                                                        <a href="{{ route('yukk_co.suspects.edit', array_merge(['id' => $suspect['id']], request()->all(), ['page_log' => $i])) }}"
                                                            class="page-link">{{ $i }}</a>
                                                    </li>
                                                @endif
                                            @endfor
                                            @if ($suspect_log['current_page'] + $plus_minus_range < $suspect_log['last_page'])
                                                <li class="page-item disabled"><a href="#"
                                                        class="page-link">...</a></li>
                                            @endif
                                            @if ($suspect_log['current_page'] == $suspect_log['last_page'])
                                                <li class="page-item disabled"><a href="#" class="page-link"><i
                                                            class="icon-arrow-right13"></i></a></li>
                                            @else
                                                <li class="page-item">
                                                    <a href="{{ route('yukk_co.suspects.edit', array_merge(['id' => $suspect['id']], request()->all(), ['page_log' => $suspect_log['current_page'] + 1])) }}"
                                                        class="page-link">
                                                        <i class="icon-arrow-right13"></i>
                                                    </a>
                                                </li>
                                            @endif

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-page.content>

    <x-page.content>
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('cms.Transaction Log') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped dataTable">
                                        <thead>
                                            <tr>
                                                <th>{{ __('cms.Date') }}</th>
                                                <th>{{ __('cms.Transaction Type') }}</th>
                                                <th>{{ __('cms.Nominal') }}</th>
                                                <th>{{ __('cms.RRN') }}</th>
                                                <th>{{ __('cms.Bank Source') }}</th>
                                                <th>{{ __('cms.Description') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($transaction_log['data'])
                                                @foreach ($transaction_log['data'] as $item)
                                                    <tr>
                                                        <td>{{ $item['transaction_time'] }}</td>
                                                        <td>{{ $item['title'] }}</td>
                                                        <td>{{ $item['yukk_p_amount'] }}</td>
                                                        <td>{{ $item['object_ref_code'] }}</td>
                                                        <td>{{ $item['bank_source'] }}</td>
                                                        <td>{{ $item['description'] }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr class="text-center">
                                                    <td colspan="9"> Data Not Found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="form-group ml-2 mt-1">
                                        {{ 'Showing ' . $transaction_log['from'] . ' to ' . $transaction_log['to'] . ' of ' . $transaction_log['total'] . ' entries' }}
                                    </div>
                                    <div class="col-1">
                                        <form action="{{ route('yukk_co.suspects.edit', $suspect['id']) }}"
                                            method="GET">
                                            <select class="select2 form-control" name="per_page_transaction_log"
                                                onchange='if(this.value != 0) { this.form.submit(); }'>
                                                <option @if ($transaction_log['per_page'] == 10) selected @endif>10</option>
                                                <option @if ($transaction_log['per_page'] == 25) selected @endif>25</option>
                                                <option @if ($transaction_log['per_page'] == 50) selected @endif>50</option>
                                                <option @if ($transaction_log['per_page'] == 100) selected @endif>100</option>
                                            </select>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <ul class="pagination pagination-flat justify-content-end">
                                            @php($plus_minus_range = 3)
                                            @if ($transaction_log['current_page'] == 1)
                                                <li class="page-item disabled"><a href="#" class="page-link"><i
                                                            class="icon-arrow-left12"></i></a></li>
                                            @else
                                                <li class="page-item">
                                                    <a href="{{ route('yukk_co.suspects.edit', array_merge(['id' => $suspect['id']], request()->all(), ['page_transaction_log' => $transaction_log['current_page'] - 1])) }}"
                                                        class="page-link"><i class="icon-arrow-left12"></i></a>
                                                </li>
                                            @endif
                                            @if ($transaction_log['current_page'] - $plus_minus_range > 1)
                                                <li class="page-item disabled"><a href="#"
                                                        class="page-link">...</a></li>
                                            @endif
                                            @for ($i = max(1, $transaction_log['current_page'] - $plus_minus_range); $i <= min($transaction_log['current_page'] + $plus_minus_range, $transaction_log['last_page']); $i++)
                                                @if ($i == $transaction_log['current_page'])
                                                    <li class="page-item active"><a href="#"
                                                            class="page-link">{{ $i }}</a></li>
                                                @else
                                                    <li class="page-item">
                                                        <a href="{{ route('yukk_co.suspects.edit', array_merge(['id' => $suspect['id']], request()->all(), ['page_transaction_log' => $i])) }}"
                                                            class="page-link">{{ $i }}</a>
                                                    </li>
                                                @endif
                                            @endfor
                                            @if ($transaction_log['current_page'] + $plus_minus_range < $transaction_log['last_page'])
                                                <li class="page-item disabled"><a href="#"
                                                        class="page-link">...</a></li>
                                            @endif
                                            @if ($transaction_log['current_page'] == $transaction_log['last_page'])
                                                <li class="page-item disabled"><a href="#" class="page-link"><i
                                                            class="icon-arrow-right13"></i></a></li>
                                            @else
                                                <li class="page-item">
                                                    <a href="{{ route('yukk_co.suspects.edit', array_merge(['id' => $suspect['id']], request()->all(), ['page_transaction_log' => $transaction_log['current_page'] + 1])) }}"
                                                        class="page-link">
                                                        <i class="icon-arrow-right13"></i>
                                                    </a>
                                                </li>
                                            @endif

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-page.content>
    @swal
</x-app-layout>
