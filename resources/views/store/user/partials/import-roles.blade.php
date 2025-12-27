<div class="mt-4">
    <div v-for="(role, j) in user.roles" :key="j" class="row mb-2">
        <div class="col-lg-3 mb-2">
            <select
                class="form-control select2 role is-invalid"
                :data-user-index="i"
                :data-role-index="j"
            >
                <option :value="role.id">@{{ role.name }}</option>
            </select>

            <p v-show="errors && errors[`users.${i}.roles.${j}.id`]" class="mt-1 text-danger">
                Role is required.
            </p>
        </div>

        <div class="col-lg-3 mb-2">
            <input class="form-control" type="text" :value="role.target_type" readonly>
        </div>

        <div class="col-lg-5 mb-2">
            <select
                v-if="role.target_type == 'PARTNER'"
                class="form-control select2 target partner"
                :data-user-index="i"
                :data-role-index="j"
                :data-default="JSON.stringify(role.partners.map(partner => partner.id))"
                multiple
            >
                <option
                    v-for="partner in role.partners"
                    :key="partner.id"
                    :value="partner.id">
                    @{{ partner.name }}
                </option>
            </select>

            <select
                v-if="role.target_type == 'MERCHANT_BRANCH'"
                class="form-control select2 target merchant-branch"
                :data-user-index="i"
                :data-role-index="j"
                :data-default="JSON.stringify(role.merchant_branches.map(branch => branch.id))"
                multiple
            >
                <option
                    v-for="branch in role.merchant_branches"
                    :key="branch.id"
                    :value="branch.id">
                    @{{ branch.name }}
                </option>
            </select>

            <select
                v-if="role.target_type == 'CUSTOMER'"
                class="form-control select2 target beneficiary"
                :data-user-index="i"
                :data-role-index="j"
                :data-default="JSON.stringify(role.beneficiaries.map(beneficiary => beneficiary.id))"
                multiple
            >
                <option
                    v-for="beneficiary in role.beneficiaries"
                    :key="beneficiary.id"
                    :value="beneficiary.id">
                    @{{ beneficiary.name }}
                </option>
            </select>

            <input v-if="!role.target_type" class="form-control" readonly />

            <p v-show="errors && errors[`users.${i}.roles.${j}.targets`]" class="mt-1 text-danger">
                Role target's is required.
            </p>
        </div>

        <div class="col-lg-1 mb-2 d-flex align-items-center justify-content-center">
            <a
                class="text-danger"
                href="#"
                @click.prevent="removeRole(i, j)"
            >
                <i class="icon-trash"></i>
            </a>
        </div>
    </div>

    <div class="row mt-4 mb-2">
        <div class="col-12 text-center">
            <button class="btn btn-secondary" type="button" @click="addRole(i)">@lang('cms.Add Role')</button>
        </div>
    </div>
</div>
