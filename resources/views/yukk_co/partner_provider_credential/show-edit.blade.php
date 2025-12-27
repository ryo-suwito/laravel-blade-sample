<x-app-layout>
    <x-page.header :title="__('cms.Partner')">
        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            <x-breadcrumb.link :link="route('cms.yukk_co.partner.list')" :text="__('cms.Partner')"/>
            <x-breadcrumb.link :link="route('cms.yukk_co.partner.item', ['partner_id' => $partner['id']])" :text="$partner['name']"/>
            <x-breadcrumb.link :link="route('cms.yukk_co.partners.credentials.index', ['partner_id' => $partner['id']])" :text="__('cms.Manage Credentials')"/>
            <x-breadcrumb.active>
                @if (Route::current()->getName() == "cms.yukk_co.partners.credentials.edit")
                    {{ __("cms.Edit") }}
                @else
                    {{ __("cms.Detail") }}
                @endif
            </x-breadcrumb.active>
        </x-slot>
    </x-page.header>

    <x-page.content :title="__('cms.Provider Setting')">
        <div style="background-color: #202125; padding: 10px;">
            <form id="form_edit" action="{{ route("cms.yukk_co.partners.credentials.update", ["partner_id" => $partner['id'], "token" => $token]) }}" method="post">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-sm-12 col-lg-6">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label">{{ __("cms.Select Provider") }}</label>
                                                    <div class="col-lg-8">
                                                        <input type="hidden" class="form-control" name="provider" id="provider" value="{{ $providerCredential['code'] }}"/>
                                                        <input type="text" class="form-control" value="{{ $providerCredential['name'] }}" readonly/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label">Credential</label>
                                                    <div class="col-lg-8">
                                                        
                                                        <div id="div_credential">
                                                            @if($providerCredential['name'] == 'YUKK')
                                                                @foreach($providerCredential['credential'] as $item)
                                                                    <div class="row mb-2">
                                                                        <div class="col-lg-6">
                                                                            <input type="text" class="form-control" name="name[]" value="merchant_branch_id" readonly>
                                                                        </div>
                                                                        <div class="col-lg-6">
                                                                            <textarea class="form-control" name="merchant_branch_id[]" cols="10" rows="2" {{ (Route::current()->getName() == "cms.yukk_co.partners.credentials.show") ? 'readonly' : 'readonly'}}>{{ $item['merchant_branch_id'] }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-2">
                                                                        <div class="col-lg-6">
                                                                            <input type="text" class="form-control" name="name[]" value="client_id" readonly>
                                                                        </div>
                                                                        <div class="col-lg-6">
                                                                            <textarea class="form-control" name="client_id[]" cols="10" rows="2" {{ (Route::current()->getName() == "cms.yukk_co.partners.credentials.show") ? 'readonly' : 'readonly'}}>{{ $item['client_id'] }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-2">
                                                                        <div class="col-lg-6">
                                                                            <input type="text" class="form-control" name="name[]" value="client_secret" readonly>
                                                                        </div>
                                                                        <div class="col-lg-6">
                                                                            <textarea class="form-control" name="client_secret[]" cols="10" rows="2" {{ (Route::current()->getName() == "cms.yukk_co.partners.credentials.show") ? 'readonly' : 'readonly'}}>{{ $item['client_secret'] }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                @foreach($providerCredential['credential'] as $key => $item)
                                                                    <div class="row mb-2">
                                                                        <div class="col-lg-6">
                                                                            <input type="text" class="form-control" name="name[]" value="{{ $key }}" readonly>
                                                                        </div>
                                                                        <div class="col-lg-6">
                                                                            <textarea class="form-control" name="value[]" cols="10" rows="2" {{ (Route::current()->getName() == "cms.yukk_co.partners.credentials.show") ? 'readonly' : (($providerCredential["code"] == "OTTOPAY") ? 'readonly': '') }}>{{ $item }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row ">
                                                    <label class="col-lg-4 col-form-label">{{ __("cms.Status") }}</label>
                                                    <div class="col-lg-8 custom-control custom-switch form-control" style="background: transparent; padding-left: 0.875rem;">
                                                        <input type="checkbox" class="custom-control-input" name="status" value="1"
                                                        @if ($providerCredential['active'])
                                                            checked
                                                        @endif
                                                        @if (Route::current()->getName() == "cms.yukk_co.partners.credentials.show")
                                                            disabled
                                                        @endif
                                                        id="switch-credential">
                                                        <label class="custom-control-label" for="switch-credential"></label>
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
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">{{ __("cms.Payment Channel List") }}</h5>
                            </div>

                            <div class="card-body">
                                <div class="row">

                                    <div class="col-lg-12">
                                        <table class="table table-bordered table-striped dataTable" id="payment_channel_table">
                                            <thead>
                                            <tr>
                                                <th>{{ __("cms.Name") }}</th>
                                                <th class="text-center">{{ __("cms.Status") }}</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($providerPaymentChannels as $ppc)
                                                <tr>
                                                    <td>{{ $ppc["name"] }}</td> 
                                                    <td class="text-center">
                                                        @if (in_array($ppc["code"], $partnerPaymentChannels))
                                                            @if (Route::current()->getName() == "cms.yukk_co.partners.credentials.edit")
                                                                <input type="checkbox" name="payment_channels[]" value="{{ $ppc["code"] }}"
                                                                @if (in_array($ppc["code"], explode(",", $providerCredential["payment_channels"])))
                                                                    checked
                                                                @endif
                                                                >
                                                            @else
                                                                @if (in_array($ppc["code"], explode(",", $providerCredential["payment_channels"])))
                                                                    <i class="icon-checkmark text-success"></i>
                                                                @else
                                                                    <i class='icon-cross2 text-danger'></i>
                                                                @endif
                                                            @endif
                                                        @else
                                                            <i class='icon-cross2 text-danger'></i>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (Route::current()->getName() == "cms.yukk_co.partners.credentials.edit")
                    <div class="col-sm-12">
                        <button class="btn btn-block btn-primary" id="btn_submit">{{ __("cms.Submit") }}</button>
                    </div>
                    @endif
                </div>
            </form>
        </div>
    </x-page.content>
    @swal

    @push('scripts')
    <script>
        $(document).ready(function() {
            $("#form_edit").submit(function () {
                $('#btn_submit').prop('disabled', true);
                return true;
            });
        });
    </script>
    @endpush
</x-app-layout>
