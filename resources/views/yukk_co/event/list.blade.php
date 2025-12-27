@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang("cms.Event List")</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.Event List")</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card mt-4">
        <div class="ml-sm-auto mb-3 mb-sm-0 mt-3 mr-3">
            @if(in_array('MASTER_DATA.EVENT.UPDATE', $access_control))
                <div class="dropdown p-0">
                    <a class="dropdown-item btn-primary" href="{{ route('yukk_co.event.create') }}">
                        <i class="icon-add"></i>@lang("cms.Create Event")
                    </a>
                </div>
            @endif
        </div>
        <div class="card-header form-group">
            <form method="get" enctype="multipart/form-data" action="{{ route('yukk_co.event.list') }}">
                    @csrf
                    <div class="row">
                        <div class="col-sm-4">
                            <label class="control-label" for="name">@lang("cms.Name")</label>
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label" for="code">@lang("cms.Code")</label>
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label">&nbsp;</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <input type="text" name="name" id="name" value="{{ isset($name) ? $name : "" }}"  class="form-control">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="code" id="code" value="{{ isset($code) ? $code : "" }}"  class="form-control">
                        </div>
                        <div class="col-sm-4">
                            <button type="submit" class="btn btn-primary btn-block">@lang("cms.Search")</button>
                        </div>
                    </div>
                </form>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped dataTable">
                <thead>
                    <tr>
                        <th>@lang("cms.Name")</th>
                        <th>@lang("cms.Code")</th>
                        <th>@lang("cms.Short Description")</th>
                        <th>@lang("cms.Location")</th>
                        <th>@lang("cms.Event Date")</th>
                        <th>@lang("cms.Event Organizer")</th>
                        <th>@lang("cms.Display Status")</th>
                        <th>@lang("cms.Created At")</th>
                        <th>@lang("cms.Actions")</th>
                    </tr>
                </thead>

                <tbody>
                @foreach ($event_list as $event)
                    <tr>
                        <td>{{ @$event->name }}</td>
                        <td>{{ @$event->code }}</td>
                        <td>{{ substr(@$event->short_description, 0, 50) }}</td>
                        <td>{{ @$event->location }}</td>
                        <td>{{ @$event->start_date }} - {{ @$event->end_date }}</td>
                        <td>{{ @$event->event_organizer_name }} ({{ @$event->event_organizer_code }})</td>
                        <td>{{ @$event->display_status }}</td>
                        <td>{{ @$event->created_at }}</td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        @if(in_array('MASTER_DATA.EVENT.UPDATE', $access_control))
                                            <a href="{{ route('yukk_co.event.edit', $event->id) }}" class="dropdown-item"><i class="icon-pencil7"></i>
                                                @lang("cms.Edit")
                                            </a>
                                        @endif
                                        @if(in_array('MASTER_DATA.EVENT.VIEW', $access_control))
                                            <a href="{{ route('yukk_co.event.detail', $event->id) }}" class="dropdown-item"><i class="icon-zoomin3"></i>
                                                @lang("cms.Detail")
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".dataTable").DataTable({
                "paging": true,
                "ordering": true,
                "info": true,
                "searching": false,
            });

            $(".date_range").daterangepicker({
                parentEl: '.content-inner',
                locale: {
                    format: 'DD-MMM-YYYY',
                    firstDay: 1,
                },
                timePicker: false,
                timePicker24Hour: false,
            });

            $(".pagination .page-item.active").click(function(e) {
                e.preventDefault();
            });
        });
    </script>
@endsection
