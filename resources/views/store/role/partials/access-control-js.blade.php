@php
    $targetType = isset($role) ? $role['target_type'] : null;
    $defaultAccessControls = isset($role) ? $role['access_control'] : [];
@endphp

@push('scripts')
    <script src="{{ asset('/vendor/vue/vue.global.js') }}"></script>
    <script src="{{ asset('/vendor/lodash/lodash.min.js') }}"></script>
    <script>
        const app = Vue.createApp({
            data() {
                return {
                    targetType: '{{ $targetType }}',
                    accessControls: JSON.parse('@json($accessControls)'),
                    search: '',
                    inputAccessControls: JSON.parse('@json(old("access_control") ?? $defaultAccessControls)')
                };
            },

            computed: {
                groups() {
                    const groups = [];

                    this.accessControls.forEach((group) => {
                        const accessControls = group.access_controls.filter((access) => {
                            const actions = access.actions.filter((action) => {
                                return action.value.toLowerCase().indexOf(this.search) > -1;
                            });

                            return actions.length > 0;
                        });

                        groups.push({
                            name: group.name,
                            access_controls: accessControls,
                        });
                    })

                    return groups.filter(({ access_controls }) => access_controls.length > 0);
                },
            },

            methods: {
                handleChangeTargetType () {
                    this.search = '';
                    this.accessControls = [];
                    this.inputAccessControls = [];

                    fetch('{{ route("json.cms-api.grouping-access-controls") }}?target_type='+this.targetType)
                        .then((res) => res.json())
                        .then((data) => this.accessControls = data.result)
                        .catch((err) => console.error(err))
                },
                handleSearch: _.debounce(function (e) {
                    this.search = e.target.value;
                }, 300),
            },
        });

        app.mount('#role-form');
    </script>
@endpush
