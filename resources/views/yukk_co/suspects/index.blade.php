<x-app-layout>
    <x-page.header :title="__('cms.Suspect')">

        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            <x-breadcrumb.active>{{ __('cms.Suspect') }}</x-breadcrumb.active>
        </x-slot>
    </x-page.header>

    <x-page.content>
        <form action="{{ route('yukk_co.suspects.index') }}" method="get">
            <div class="row mb-3">
                <div class="col-lg-2 col-sm-6 mb-3">
                    <select class="form-control select2" id="type" name="type">
                        <option value="">{{ __('cms.Select Type') }}</option>
                        <option value="yukk_id" @if ($type == 'yukk_id') selected @endif>{{ __('cms.YUKK ID') }}
                        </option>
                        <option value="fullname" @if ($type == 'fullname') selected @endif>{{ __('cms.Name') }}
                        </option>
                    </select>
                </div>
                <div class="col-lg-4 col-sm-6 mb-3">
                    <input class="form-control" id="keyword" name="keyword" value="{{ $keyword }}"
                        placeholder="{{ __('cms.Search Parameter By Type') }}">
                </div>
                <div class="col-lg-3 col-sm-6 mb-3">
                    <input type="text" id="date_range" name="date_range" class="form-control"
                        placeholder="{{ __('cms.Search Date Range') }}" autocomplete="off" value="{{ $date_range }}">
                </div>
                <div class="col-lg-3 col-sm-6 mb-3">
                    <div class="row">
                        <div class="col-sm-6 col-xs-12 mb-3">
                            <button class="btn btn-primary form-control" id="search" type="submit"><i
                                    class="icon-search4"></i>
                                {{ __('cms.Search') }}</button>
                        </div>
                        <div class="col-sm-6 col-xs-12 mb-3">
                            <button class="btn btn-danger form-control" id="reset"><i class="icon-sync"></i>
                                {{ __('cms.Reset') }}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-end mr-3">
                <div class="form-group justify-content-end">
                    <select class="select2 form-control" name="per_page"
                        onchange='if(this.value != 0) { this.form.submit(); }'>
                        <option @if ($per_page == 10) selected @endif>10</option>
                        <option @if ($per_page == 25) selected @endif>25</option>
                        <option @if ($per_page == 50) selected @endif>50</option>
                        <option @if ($per_page == 100) selected @endif>100</option>
                    </select>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered table-striped dataTable">
                <thead>
                    <tr>
                        <th>{{ __('cms.Attempt Date') }}</th>
                        <th>{{ __('cms.YUKK ID') }}</th>
                        <th>{{ __('cms.Name') }}</th>
                        <th>{{ __('cms.Phone Number') }}</th>
                        <th>{{ __('cms.Email') }}</th>
                        <th>{{ __('cms.Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($suspects)
                        @foreach ($suspects as $item)
                            <tr>
                                <td>{{ $item['last_attempted_at'] }}</td>
                                <td>{{ $item['yukk_id'] }}</td>
                                <td>{{ $item['fullname'] }}</td>
                                <td>{{ $item['phone'] }}</td>
                                <td>{{ $item['email'] }}</td>
                                <td>
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                @if (in_array('LAST_TOPUP_VALIDATION.VIEW', $access_control))
                                                    <a href="{{ route('yukk_co.suspects.show', $item['id']) }}"
                                                        class="dropdown-item"><i class="icon-zoomin3"></i>
                                                        Detail
                                                    </a>
                                                @endif
                                                @if (in_array('LAST_TOPUP_VALIDATION.UPDATE', $access_control))
                                                    <a href="{{ route('yukk_co.suspects.edit', $item['id']) }}"
                                                        class="dropdown-item"><i class="icon-pencil7"></i>
                                                        Edit
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
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

        <div class="card-footer">
            <div class="row">
                <div class="form-group ml-2 mt-1">
                    {{ 'Showing ' . $from . ' to ' . $to . ' of ' . $total . ' entries' }}
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <ul class="pagination pagination-flat justify-content-end">
                        @php($plus_minus_range = 3)
                        @if ($current_page == 1)
                            <li class="page-item disabled"><a href="#" class="page-link"><i
                                        class="icon-arrow-left12"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route('yukk_co.suspects.index', array_merge(request()->all(), ['page' => $current_page - 1])) }}"
                                    class="page-link"><i class="icon-arrow-left12"></i></a>
                            </li>
                        @endif
                        @if ($current_page - $plus_minus_range > 1)
                            <li class="page-item disabled"><a href="#" class="page-link">...</a></li>
                        @endif
                        @for ($i = max(1, $current_page - $plus_minus_range); $i <= min($current_page + $plus_minus_range, $last_page); $i++)
                            @if ($i == $current_page)
                                <li class="page-item active"><a href="#"
                                        class="page-link">{{ $i }}</a></li>
                            @else
                                <li class="page-item">
                                    <a href="{{ route('yukk_co.suspects.index', array_merge(request()->all(), ['page' => $i])) }}"
                                        class="page-link">{{ $i }}</a>
                                </li>
                            @endif
                        @endfor
                        @if ($current_page + $plus_minus_range < $last_page)
                            <li class="page-item disabled"><a href="#" class="page-link">...</a></li>
                        @endif
                        @if ($current_page == $last_page)
                            <li class="page-item disabled"><a href="#" class="page-link"><i
                                        class="icon-arrow-right13"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route('yukk_co.suspects.index', array_merge(request()->all(), ['page' => $current_page + 1])) }}"
                                    class="page-link">
                                    <i class="icon-arrow-right13"></i>
                                </a>
                            </li>
                        @endif

                    </ul>
                </div>
            </div>
        </div>
    </x-page.content>

    @swal

    @push('scripts')
        <script type="text/javascript" src="{{ asset('assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/plugins/ui/moment/moment.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/daterangepicker.js') }}"></script>

        <script>
            $(document).ready(function() {

                $(".pagination .page-item.active").click(function(e) {
                    e.preventDefault();
                });

                $('#reset').click(function() {
                    // Kosongkan semua input
                    $('#type').val('');
                    $('#keyword').val('');
                    $('#date_range').val('');
                });


                $("#date_range").daterangepicker({
                    parentEl: '.content-inner',
                    locale: {
                        format: 'DD-MMM-YYYY',
                        firstDay: 1,
                    },
                    autoUpdateInput: false,
                    timePicker: false,
                    timePicker24Hour: false,
                });
                $('#date_range').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('DD-MMM-YYYY') + ' - ' + picker.endDate.format(
                        'DD-MMM-YYYY'));
                });
                $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                });
            });
        </script>
    @endpush
</x-app-layout>
