<x-app-layout>
    <x-slot name="header">
        <x-page.header :title="__('cms.Preview')">
            <x-slot name="breadcrumb">
                <x-breadcrumb.home />
                <x-breadcrumb.link link="/" :text="__('cms.Users')" />
                <x-breadcrumb.link :link="route('store.users.import_form')"  :text="__('cms.Import')" />
                <x-breadcrumb.active>@lang('cms.Preview')</x-breadcrumb.active>
            </x-slot>
        </x-page.header>
    </x-slot>

    <x-page.content :title="__('cms.Preview')">
        <form id="form" action="{{ route('store.users.import') }}" method="POST">
            @csrf

            <div v-for="(user, i) in users" :key="i" class="mb-4 p-4 border">
                <div class="row">
                    <div class="col-lg-6">
                        <x-form.item :label="__('cms.Username')" label-position="left">
                            <input v-model="user.username" class="form-control" />
                            <p v-show="errors && errors[`users.${i}.username`]" class="mt-1 text-danger">
                                @{{ errors && errors[`users.${i}.username`] ? errors[`users.${i}.username`][0] : null }}
                            </p>
                        </x-form.item>
                        <x-form.item :label="__('cms.Full Name')" label-position="left">
                            <input v-model="user.full_name" class="form-control" />
                            <p v-show="errors && errors[`users.${i}.full_name`]" class="mt-1 text-danger">
                                @{{ errors && errors[`users.${i}.full_name`] ? errors[`users.${i}.full_name`][0] : null }}
                            </p>
                        </x-form.item>
                        <x-form.item :label="__('cms.Email')" label-position="left">
                            <input v-model="user.email" class="form-control" />
                            <p v-show="errors && errors[`users.${i}.email`]" class="mt-1 text-danger">
                                @{{ errors && errors[`users.${i}.email`] ? errors[`users.${i}.email`][0] : null }}
                            </p>
                        </x-form.item>
                        <x-form.item :label="__('cms.Phone')" label-position="left">
                            <input v-model="user.phone" class="form-control" />
                            <p v-show="errors && errors[`users.${i}.phone`]" class="mt-1 text-danger">
                                @{{ errors && errors[`users.${i}.phone`] ? errors[`users.${i}.phone`][0] : null }}
                            </p>
                        </x-form.item>
                    </div>
                    <div class="col-lg-6">
                        <x-form.item :label="__('cms.Gender')" label-position="left">
                            <select v-model="user.gender" class="form-control">
                                <option value="MALE" :selected="user.gender == 'MALE'">MALE</option>
                                <option value="FEMALE" :selected="user.gender == 'FEMALE'">FEMALE</option>
                            </select>
                            <p v-show="errors && errors[`users.${i}.gender`]" class="mt-1 text-danger">
                                @{{ errors && errors[`users.${i}.gender`] ? errors[`users.${i}.gender`][0] : null }}
                            </p>
                        </x-form.item>
                        <x-form.item :label="__('cms.Description')" label-position="left">
                            <textarea v-model="user.description" class="form-control">@{{ user.description }}</textarea>
                        </x-form.item>
                        <x-form.item :label="__('cms.Status')" label-position="left">
                            <select v-model="user.active" class="form-control">
                                <option value="0" :selected="user.active == 0">INACTIVE</option>
                                <option value="1" :selected="user.active == 1">ACTIVE</option>
                            </select>
                            <p v-show="errors && errors[`users.${i}.active`]" class="mt-1 text-danger">
                                @{{ errors && errors[`users.${i}.active`] ? errors[`users.${i}.active`][0] : null }}
                            </p>
                        </x-form.item>
                    </div>
                </div>

                @include('store.user.partials.import-roles')
            </div>

            <x-form.actions :cancel="route('store.users.import_form')">
                <button class="btn btn-primary" type="button" @click="save">Save</button>
            </x-form.actions>
        </form>
    </x-page.content>

    @include('store.user.partials.import-js')
</x-app-layout>
