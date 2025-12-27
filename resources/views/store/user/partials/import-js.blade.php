@push('scripts')
    <script src="https://unpkg.com/vue@3.2.36/dist/vue.global.prod.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="{{ asset('assets/js/plugins/notifications/sweet_alert.min.js') }}"></script>
    <script>
        const app = Vue.createApp({
            data() {
                return {
                    users: JSON.parse('@json($users)'),
                    errors: JSON.parse('@json($errors)'),
                };
            },

            methods: {
                setupRolesSelect() {
                    $('.role').select2({
                        placeholder: '-- Select Role --',
                        ajax: {
                            url: '{{ route("json.store.roles.index") }}',
                            dataType: 'json',
                            processResults: (paginator) => {
                                const results = paginator.data.map((role) => {
                                    return {
                                        id: role.id,
                                        text: role.name,
                                        targetType: role.target_type,
                                    };
                                });

                                return {
                                    results,
                                    pagination: {
                                        more: paginator.current_page < paginator.last_page,
                                    },
                                };
                            },
                        },
                    });

                    const vm = this

                    $('.role').on('select2:select', (e) => {
                        const userIndex = $(e.target).data('user-index');
                        const roleIndex = $(e.target).data('role-index');

                        if (vm.errors[`users.${userIndex}.roles.${roleIndex}.id`]) {
                            delete vm.errors[`users.${userIndex}.roles.${roleIndex}.id`];
                        }

                        const { id, text, targetType } = e.params.data;

                        let user = vm.users[userIndex];

                        let role = {
                            id: id,
                            name: text,
                            target_type: targetType,
                            targets: [],
                        }

                        if (targetType == 'PARTNER') {
                            role.partners = [];

                            $(e.target).closest('.row').find('.target.select2').select2('destroy');

                            setTimeout(() => {
                                vm.setupPartnersSelect();
                            }, 100);
                        } else if (targetType == 'MERCHANT_BRANCH') {
                            role.merchant_branches = [];

                            $(e.target).closest('.row').find('.target.select2').select2('destroy');

                            setTimeout(() => {
                                vm.setupBranchesSelect();
                            }, 100);
                        } else if (targetType == 'CUSTOMER') {
                            role.beneficiaries = [];

                            $(e.target).closest('.row').find('.target.select2').select2('destroy');

                            setTimeout(() => {
                                vm.setupBeneficiariesSelect();
                            }, 100);
                        } else {
                            delete role.partners;
                            delete role.merchant_branches;
                            delete role.beneficiaries;

                            $(e.target).closest('.row').find('.target.select2').select2('destroy');
                        }

                        user.roles[roleIndex] = role;

                        vm.users.splice(userIndex, 1, user);
                    });
                },
                setupPartnersSelect() {
                    const options = {
                        placeholder: '-- Select Partner --',
                        ajax: {
                            url: '{{ route("json.core.partners.index") }}',
                            dataType: 'json',
                            processResults: (paginator) => {
                                const results = paginator.data.map((partner) => {
                                    return {
                                        id: partner.id,
                                        text: partner.name,
                                    };
                                });

                                return {
                                    results,
                                    pagination: {
                                        more: paginator.current_page < paginator.last_page,
                                    },
                                };
                            },
                        },
                    };

                    const vm = this;

                    $('.partner').each(function () {
                        $(this).select2(options).val($(this).data('default')).trigger('change');

                        $(this).on('select2:select', (e) => {
                            const userIndex = $(e.target).data('user-index');
                            const roleIndex = $(e.target).data('role-index');

                            if (vm.errors[`users.${userIndex}.roles.${roleIndex}.targets`]) {
                                delete vm.errors[`users.${userIndex}.roles.${roleIndex}.targets`];
                            }

                            const { id, text } = e.params.data;

                            let user = vm.users[userIndex];
                            let role = user.roles[roleIndex];

                            role.targets.push({
                                id: id,
                                name: text,
                            });

                            user.roles.splice(roleIndex, 1, role);

                            vm.users.splice(userIndex, 1, user);
                        });

                        $(this).on('select2:unselect', (e) => {
                            const userIndex = $(e.target).data('user-index');
                            const roleIndex = $(e.target).data('role-index');

                            const { id, text } = e.params.data;

                            let user = vm.users[userIndex];
                            let role = user.roles[roleIndex];

                            role.targets.splice(
                                role.targets.findIndex((item) => item.id == id),
                                1
                            );

                            user.roles.splice(roleIndex, 1, role);

                            vm.users.splice(userIndex, 1, user);
                        });
                    });
                },

                setupBranchesSelect() {
                    const options = {
                        placeholder: '-- Select Merchant Branch --',
                        ajax: {
                            url: '{{ route("json.core.merchant_branches.index") }}',
                            dataType: 'json',
                            processResults: (paginator) => {
                                const results = paginator.data.map((branch) => {
                                    return {
                                        id: branch.id,
                                        text: branch.name,
                                    };
                                });

                                return {
                                    results,
                                    pagination: {
                                        more: paginator.current_page < paginator.last_page,
                                    },
                                };
                            },
                        },
                    };

                    const vm = this;

                    $('.merchant-branch').each(function () {
                        $(this).select2(options).val($(this).data('default')).trigger('change');

                        $(this).on('select2:select', (e) => {
                            const userIndex = $(e.target).data('user-index');
                            const roleIndex = $(e.target).data('role-index');

                            const { id, text } = e.params.data;

                            let user = vm.users[userIndex];
                            let role = user.roles[roleIndex];

                            role.targets.push({
                                id: id,
                                name: text,
                            });

                            user.roles.splice(roleIndex, 1, role);

                            vm.users.splice(userIndex, 1, user);
                        });

                        $(this).on('select2:unselect', (e) => {
                            const userIndex = $(e.target).data('user-index');
                            const roleIndex = $(e.target).data('role-index');

                            const { id, text } = e.params.data;

                            let user = vm.users[userIndex];
                            let role = user.roles[roleIndex];

                            role.targets.splice(
                                role.targets.findIndex((item) => item.id == id),
                                1
                            );

                            user.roles.splice(roleIndex, 1, role);

                            vm.users.splice(userIndex, 1, user);
                        });
                    });
                },

                setupBeneficiariesSelect() {
                    const options = {
                        placeholder: '-- Select Beneficiary --',
                        ajax: {
                            url: '{{ route("json.core.beneficiaries.index") }}',
                            dataType: 'json',
                            processResults: (paginator) => {
                                const results = paginator.data.map((beneficiary) => {
                                    return {
                                        id: beneficiary.id,
                                        text: beneficiary.name,
                                    };
                                });

                                return {
                                    results,
                                    pagination: {
                                        more: paginator.current_page < paginator.last_page,
                                    },
                                };
                            },
                        },
                    };

                    const vm = this;

                    $('.beneficiary').each(function () {
                        $(this).select2(options).val($(this).data('default')).trigger('change');

                        $(this).on('select2:select', (e) => {
                            const userIndex = $(e.target).data('user-index');
                            const roleIndex = $(e.target).data('role-index');

                            const { id, text } = e.params.data;

                            let user = vm.users[userIndex];
                            let role = user.roles[roleIndex];

                            role.targets.push({
                                id: id,
                                name: text,
                            });

                            user.roles.splice(roleIndex, 1, role);

                            vm.users.splice(userIndex, 1, user);
                        });

                        $(this).on('select2:unselect', (e) => {
                            const userIndex = $(e.target).data('user-index');
                            const roleIndex = $(e.target).data('role-index');

                            const { id, text } = e.params.data;

                            let user = vm.users[userIndex];
                            let role = user.roles[roleIndex];

                            role.targets.splice(
                                role.targets.findIndex((item) => item.id == id),
                                1
                            );

                            user.roles.splice(roleIndex, 1, role);

                            vm.users.splice(userIndex, 1, user);
                        });
                    });
                },

                addRole(userIndex) {
                    let user = this.users[userIndex];

                    user.roles.push({
                        id: null,
                        name: null,
                        target_type: null,
                        targets: [],
                    });

                    this.users.splice(userIndex, 1, user);

                    setTimeout(() => {
                        this.setupRolesSelect();
                    }, 100);
                },

                removeRole(userIndex, roleIndex) {
                    let user = this.users[userIndex];

                    user.roles.splice(roleIndex, 1);

                    this.users.splice(userIndex, 1, user);
                },

                async save() {
                    this.errors = {};

                    try {
                        const { data } = await axios.post('{{ route("json.store.users.import") }}', {
                            _token: '{{ csrf_token() }}',
                            users: this.users,
                        });

                        Swal.fire({
                            text: data.status_message,
                            icon: 'success',
                            toast: true,
                            timer: 3000,
                            showConfirmButton: false,
                            position: 'top-right',
                        });

                        window.location.href = '{{ route("cms.store.users.list") }}';
                    } catch (err) {
                        if (err.response && err.response.status == 422) {
                            this.errors = err.response.data.result;
                        }

                        Swal.fire({
                            text: err.response ? err.response.data.status_message : err.message,
                            icon: 'error',
                            toast: true,
                            timer: 3000,
                            showConfirmButton: false,
                            position: 'top-right',
                        });

                        console.error(err.message);
                    }
                },
            },

            mounted() {
                this.setupRolesSelect();
                this.setupPartnersSelect();
                this.setupBranchesSelect();
                this.setupBeneficiariesSelect();
            },
        });

        app.mount('#form');
    </script>
@endpush
