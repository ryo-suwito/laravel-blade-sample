<x-app-layout>
    <x-page.header :title="__('cms.Setting')">
        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            <x-breadcrumb.active>{{ __("cms.Setting") }}</x-breadcrumb.active>
        </x-slot>
    </x-page.header>

    <x-page.content>
        <div class="table-responsive">
            <table class="table table-bordered table-striped dataTable">
                <thead>
                    <tr>
                        <th>{{ __("cms.Name") }}</th>
                        <th>{{ __("cms.Label") }}</th>
                        <th>{{ __("cms.Value") }}</th>
                        <th>{{ __("cms.Actions") }}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </x-page.content>

    @swal

    @push('scripts')
        <script type="text/javascript" src="{{asset('assets/js/plugins/tables/datatables/datatables.min.js')}}"></script>
        <script>
        $(document).ready(function() {
            $(".dataTable").DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                ordering: true,
                // "info": false,
                searching: true,
                ajax: {
                    url: '{{ route('yukk_co.general.settings.data') }}',
                },
                columns: [
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'label',
                        name: 'label'
                    },
                    {
                        data: 'value',
                        name: 'value'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searcable: false,
                        width: '15%'
                    },
                ]
            });
        });
    </script>
    @endpush
</x-app-layout>
