@extends('layouts.master')

@section("html_head")
    <script type="text/javascript" src="{{asset('assets/js/plugins/ui/fullcalendar/main.min.js')}}"></script>
@endsection

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Skip Process Day")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.Skip Process Day")</span>
                </div>

                {{--<a href="#" class="header-elements-toggle text-body d-sm-none"><i class="icon-more"></i></a>--}}
            </div>

            {{--<div class="header-elements d-none">
                <div class="breadcrumb justify-content-center">
                    <a href="#" class="breadcrumb-elements-item">
                        Link
                    </a>

                    <div class="breadcrumb-elements-item dropdown p-0">
                        <a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
                            Dropdown
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="#" class="dropdown-item">Action</a>
                            <a href="#" class="dropdown-item">Another action</a>
                            <a href="#" class="dropdown-item">One more action</a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">Separate action</a>
                        </div>
                    </div>
                </div>
            </div>--}}
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                @lang("cms.Skip Process Day")

                @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("SKIP_PROCESS_DAY_CREATE", "AND"))
                    <a href="{{ route("cms.yukk_co.skip_process_day.create") }}" type="button" class="btn btn-primary w-100 w-sm-auto float-right">@lang("cms.Add New")</a>
                @endif
            </h5>
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs">
                <li class="nav-item"><a href="#list-view" class="nav-link rounded-top active" data-toggle="tab">@lang("cms.List View")</a></li>
                <li class="nav-item"><a href="#calendar-view" class="nav-link rounded-top" data-toggle="tab">@lang("cms.Calendar View")</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade active show" id="list-view">
                    <table class="table table-bordered table-striped dataTable">
                        <thead>
                        <tr>
                            <th>@lang("cms.Date")</th>
                            <th>@lang("cms.Title")</th>
                            <th>@lang("cms.Description")</th>
                            <th>@lang("cms.Created At")</th>
                            <th>@lang("cms.Actions")</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($skip_process_day_list as $index => $skip_process_day)
                            <tr>
                                <td>{{ @\App\Helpers\H::formatDateTime($skip_process_day->date, "d-M-Y") }}</td>
                                <td>{{ @$skip_process_day->title }}</td>
                                <td>{{ @$skip_process_day->description }}</td>
                                <td>{{ @\App\Helpers\H::formatDateTime($skip_process_day->created_at) }}</td>
                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="{{ route("cms.yukk_co.skip_process_day.item", @$skip_process_day->id) }}" class="dropdown-item"><i class="icon-search4"></i> @lang("cms.Detail")</a>
                                                @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("SKIP_PROCESS_DAY_EDIT", "AND"))
                                                    <a href="{{ route("cms.yukk_co.skip_process_day.edit", @$skip_process_day->id) }}" class="dropdown-item"><i class="icon-pencil7"></i> @lang("cms.Edit")</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <ul class="pagination pagination-flat justify-content-end">
                        @php($plus_minus_range = 3)
                        @if ($current_page == 1)
                            <li class="page-item disabled"><a href="#" class="page-link"><i class="icon-arrow-left12"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route("cms.yukk_co.provider.list", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
                            </li>
                        @endif
                        @if ($current_page - $plus_minus_range > 1)
                            <li class="page-item disabled"><a href="#" class="page-link">...</a></li>
                        @endif
                        @for ($i = max(1, $current_page - $plus_minus_range); $i <= min($current_page + $plus_minus_range, $last_page); $i++)
                            @if ($i == $current_page)
                                <li class="page-item active"><a href="#" class="page-link">{{ $i }}</a></li>
                            @else
                                <li class="page-item">
                                    <a href="{{ route("cms.yukk_co.provider.list", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
                                </li>
                            @endif
                        @endfor
                        @if ($current_page + $plus_minus_range < $last_page)
                            <li class="page-item disabled"><a href="#" class="page-link">...</a></li>
                        @endif
                        @if ($current_page == $last_page)
                            <li class="page-item disabled"><a href="#" class="page-link"><i class="icon-arrow-right13"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route("cms.yukk_co.provider.list", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">
                                    <i class="icon-arrow-right13"></i>
                                </a>
                            </li>
                        @endif

                    </ul>
                </div>

                <div class="tab-pane fade" id="calendar-view">
                    <div class="fullcalendar-basic"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".dataTable").DataTable({
                "paging": false,
                "ordering": true,
                "info": false,
                "searching": true,
            });

            $(".pagination .page-item.active").click(function(e) {
                e.preventDefault();
            });

            const events = [
                @foreach($skip_process_day_list as $index => $skip_process_day)
                {
                    title: '{{ @$skip_process_day->title }}',
                    start: '{{ @$skip_process_day->date }}',
                    url: '{{ @route("cms.yukk_co.skip_process_day.item", $skip_process_day->id) }}',
                },
                @endforeach
            ];

            // Define element
            const calendarBasicViewElement = document.querySelector('.fullcalendar-basic');

            // Initialize
            if(calendarBasicViewElement) {
                const calendarBasicViewInit = new FullCalendar.Calendar(calendarBasicViewElement, {
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    navLinks: true, // can click day/week names to navigate views
                    nowIndicator: true,
                    weekNumberCalculation: 'ISO',
                    editable: true,
                    selectable: true,
                    direction: document.dir == 'rtl' ? 'rtl' : 'ltr',
                    dayMaxEvents: true, // allow "more" link when too many events
                    events: events
                });

                // Init
                calendarBasicViewInit.render();

                // Resize calendar when sidebar toggler is clicked
                $('.sidebar-control').on('click', function() {
                    calendarBasicViewInit.updateSize();
                });
            }

        });
    </script>
@endsection
