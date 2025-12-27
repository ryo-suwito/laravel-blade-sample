<x-app-layout>
    <x-page.header :title="__('cms.Roles')">
        @hasaccess('STORE.ROLES_CREATE')
            <x-slot name="actions">
                <a type="button" class="btn btn-primary w-100 w-sm-auto" href="{{ route('store.roles.create') }}">
                    @lang('cms.Create Role')
                </a>
            </x-slot>
        @endhasaccess

        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            <x-breadcrumb.active>@lang('cms.Roles')</x-breadcrumb.active>
        </x-slot>
    </x-page.header>

    <x-page.content :title="__('cms.Roles')">
        <div class="table-responsive">
            <table class="table table-bordered table-striped dataTable">
                <thead>
                    <tr>
                        <th>@lang('cms.Name')</th>
                        <th>@lang('cms.Description')</th>
                        <th>@lang('cms.Target Type')</th>
                        <th class="text-center">@lang('cms.Status')</th>
                        <th class="text-center">@lang('cms.Actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role['name'] }}</td>
                            <td>{{ $role['description'] }}</td>
                            <td>{{ $role['target_type'] }}</td>
                            <td class="text-center">
                                <x-table.active-status :active="$role['active']" />
                            </td>
                            <td class="text-center">
                                <x-table.action-dropdown>
                                    <a class="dropdown-item" href="{{ route('store.roles.show', ['id' => $role['id']]) }}">
                                        <i class="icon-info22"></i> @lang('cms.Details')
                                    </a>
                                    @hasaccess('STORE.ROLES_EDIT')
                                    <a class="dropdown-item" href="{{ route('store.roles.edit', ['id' => $role['id']]) }}">
                                        <i class="icon-pencil7"></i> @lang('cms.Edit')
                                    </a>
                                    @endhasaccess
                                </x-table.action-dropdown>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-page.content>

    @swal

    @push('scripts')
        <script type="text/javascript" src="{{ asset('assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
        <script type="text/javascript" src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>

        <script>
            $(document).ready(function() {
                $('.dataTable').DataTable({
                    "paging": true,
                    "ordering": true,
                    "searching": true,
                    "language": {
                        "paginate": {
                            "previous": "prev",
                            "next": "next"
                        }
                    }
                });

                function updatePaginationIcons() {
                    $('.dataTables_paginate .previous').html('<i class="icon-arrow-left12"></i>');
                    $('.dataTables_paginate .next').html('<i class="icon-arrow-right13"></i>');
                }

                updatePaginationIcons();

                $('.dataTable').on('draw.dt', function() {
                    updatePaginationIcons();
                });

                $("<style>")
                .prop("type", "text/css")
                .html(`
                    .dataTables_wrapper .table-bordered {
                        margin-bottom: 2rem;
                    }
                `)
                .appendTo("head");
            });
        </script>
    @endpush
</x-app-layout>
