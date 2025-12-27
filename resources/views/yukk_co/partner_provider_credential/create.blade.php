<x-app-layout>
    <x-page.header :title="__('cms.Partner')">
        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            <x-breadcrumb.link :link="route('cms.yukk_co.partner.list')" :text="__('cms.Partner')"/>
            <x-breadcrumb.link :link="route('cms.yukk_co.partner.item', ['partner_id' => $partner['id']])" :text="$partner['name']"/>
            <x-breadcrumb.link :link="route('cms.yukk_co.partners.credentials.index', ['partner_id' => $partner['id']])" :text="__('cms.Manage Credentials')"/>
            <x-breadcrumb.active>{{ __("cms.Create") }}</x-breadcrumb.active>
        </x-slot>
    </x-page.header>

    <x-page.content :title="__('cms.Provider Setting')">
        <div style="background-color: #202125; padding: 10px;">
            <form id="form_create" action="{{ route("cms.yukk_co.partners.credentials.store", ["partner_id" => $partner['id']]) }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-sm-12 col-lg-6">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">{{ __("cms.Provider Setting") }}</h5>
                                    </div>

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label">{{ __("cms.Select Provider") }}</label>
                                                    <div class="col-lg-8">
                                                        <select class="form-control" name="provider" id="provider" required>
                                                            <option value="">Select Provider</option>
                                                            @foreach ($providers as $provider)
                                                                <option value="{{ $provider['code'] . '|' . $provider['id'] }}"
                                                                @if (old('provider') == ($provider['code'] . '|' . $provider['id']))
                                                                    selected
                                                                @endif
                                                                >{{ $provider['name'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label">Credential</label>
                                                    <div class="col-lg-8">
                                                        <div id="div_credential">
                                                            @if(old('name'))
                                                                @foreach(old('name') as $key => $item)
                                                                    <div class="row mb-2">
                                                                        <div class="col-lg-6">
                                                                            <input type="text" class="form-control" name="name[]" value="{{ $item }}" readonly>
                                                                        </div>
                                                                        <div class="col-lg-6">
                                                                            <textarea class="form-control" name="value[]" cols="10" rows="2">{{ old('value')[$key] }}</textarea>
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
                                                        <input type="hidden" name="status" value="0">
                                                        <input type="checkbox" class="custom-control-input" name="status" value="1" 
                                                        @if (old('status') == 1 || is_null(old('status')))
                                                            checked
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

                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <button class="btn btn-block btn-primary" id="btn_submit">{{ __("cms.Submit") }}</button>
                    </div>
                </div>
            </form>
        </div>
    </x-page.content>
    @swal

    @push('scripts')
    <script>
        $(document).ready(function() {
            var paymentChannelPartner = '{{ $partnerPaymentChannels }}';

            paymentChannelPartner = paymentChannelPartner.split(",");

            $("#form_create").submit(function () {
                $('#btn_submit').prop('disabled', true);
                return true;
            });

            $('#provider').on('change', async function(e) {
                var val = e.target.value;
                $("#div_credential").empty();
                $('#provider').prop('disabled', true);
                if(val != ""){
                    $("#payment_channel_table tbody tr").remove();
                    $("#payment_channel_table tbody").append("<tr> <td colspan='2'>Loading..</td> </tr>");

                    if(val.split("|")[0] == "OTTOPAY") {
                        $("#credential").prop('disabled', true);
                    } else {
                        $("#credential").prop('disabled', false);
                    }

                    await $.get("{{ route('cms.yukk_co.partners.credentials.payment_channels', ['partner_id' => $partner['id']]) }}?provider=" + val.split("|")[1], 
                    function(data, status){
                        $("#payment_channel_table tbody tr").remove();

                        data.forEach(function(item, index) {
                            $("#payment_channel_table tbody").append("<tr> <td>" + item.name + "</td> <td class='text-center'>"  
                                + (paymentChannelPartner.includes(item.code) 
                                    ? "<input type='checkbox' name='payment_channels[]' value='"+ item.code +"'>"
                                    : "<i class='icon-cross2 text-danger'></i>")
                                + "</td> </tr>");
                        })
                    });
                
                    $('#provider').prop('disabled', false);

                    //div credential
                    if(val.split("|")[0] == "NICEPAY"){
                        var append = '<div class="row mb-2">'+
                                        '<div class="col-lg-6">'+
                                            '<input type="text" class="form-control" name="name[]" value="merchant_id" readonly>'+
                                        '</div>'+
                                        '<div class="col-lg-6">'+
                                            '<textarea class="form-control" name="value[]" cols="10" rows="2"></textarea>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="row mb-2">'+
                                        '<div class="col-lg-6">'+
                                            '<input type="text" class="form-control" name="name[]" value="merchant_key" readonly>'+
                                        '</div>'+
                                        '<div class="col-lg-6">'+
                                            '<textarea class="form-control" name="value[]" cols="10" rows="2"></textarea>'+
                                        '</div>'+
                                    '</div>';
                        
                        $("#div_credential").append(append);
                    }else if(val.split("|")[0] == "2C2P"){
                        var append = '<div class="row mb-2">'+
                                        '<div class="col-lg-6">'+
                                            '<input type="text" class="form-control" name="name[]" value="merchant_id" readonly>'+
                                        '</div>'+
                                        '<div class="col-lg-6">'+
                                            '<textarea class="form-control" name="value[]" cols="10" rows="2"></textarea>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="row mb-2">'+
                                        '<div class="col-lg-6">'+
                                            '<input type="text" class="form-control" name="name[]" value="secret_key" readonly>'+
                                        '</div>'+
                                        '<div class="col-lg-6">'+
                                            '<textarea class="form-control" name="value[]" cols="10" rows="2"></textarea>'+
                                        '</div>'+
                                    '</div>';
                        
                        $("#div_credential").append(append);
                    }else if(val.split("|")[0] == "PAPER"){
                        var append = '<div class="row mb-2">'+
                                        '<div class="col-lg-6">'+
                                            '<input type="text" class="form-control" name="name[]" value="partner_id" readonly>'+
                                        '</div>'+
                                        '<div class="col-lg-6">'+
                                            '<textarea class="form-control" name="value[]" cols="10" rows="2"></textarea>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="row mb-2">'+
                                        '<div class="col-lg-6">'+
                                            '<input type="text" class="form-control" name="name[]" value="client_id" readonly>'+
                                        '</div>'+
                                        '<div class="col-lg-6">'+
                                            '<textarea class="form-control" name="value[]" cols="10" rows="2"></textarea>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="row mb-2">'+
                                        '<div class="col-lg-6">'+
                                            '<input type="text" class="form-control" name="name[]" value="client_secret" readonly>'+
                                        '</div>'+
                                        '<div class="col-lg-6">'+
                                            '<textarea class="form-control" name="value[]" cols="10" rows="2"></textarea>'+
                                        '</div>'+
                                    '</div>';
                        
                        $("#div_credential").append(append);
                    }else if(val.split("|")[0] == "MIDTRANS"){
                        var append = '<div class="row mb-2">'+
                                        '<div class="col-lg-6">'+
                                            '<input type="text" class="form-control" name="name[]" value="merchant_id" readonly>'+
                                        '</div>'+
                                        '<div class="col-lg-6">'+
                                            '<textarea class="form-control" name="value[]" cols="10" rows="2"></textarea>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="row mb-2">'+
                                        '<div class="col-lg-6">'+
                                            '<input type="text" class="form-control" name="name[]" value="client_key" readonly>'+
                                        '</div>'+
                                        '<div class="col-lg-6">'+
                                            '<textarea class="form-control" name="value[]" cols="10" rows="2"></textarea>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="row mb-2">'+
                                        '<div class="col-lg-6">'+
                                            '<input type="text" class="form-control" name="name[]" value="server_key" readonly>'+
                                        '</div>'+
                                        '<div class="col-lg-6">'+
                                            '<textarea class="form-control" name="value[]" cols="10" rows="2"></textarea>'+
                                        '</div>'+
                                    '</div>';
                        
                        $("#div_credential").append(append);
                    }else if(val.split("|")[0] == "SHOPEEPAY"){
                        var append = '<div class="row mb-2">'+
                                        '<div class="col-lg-6">'+
                                            '<input type="text" class="form-control" name="name[]" value="client_id" readonly>'+
                                        '</div>'+
                                        '<div class="col-lg-6">'+
                                            '<textarea class="form-control" name="value[]" cols="10" rows="2"></textarea>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="row mb-2">'+
                                        '<div class="col-lg-6">'+
                                            '<input type="text" class="form-control" name="name[]" value="client_secret" readonly>'+
                                        '</div>'+
                                        '<div class="col-lg-6">'+
                                            '<textarea class="form-control" name="value[]" cols="10" rows="2"></textarea>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="row mb-2">'+
                                        '<div class="col-lg-6">'+
                                            '<input type="text" class="form-control" name="name[]" value="merchant_id" readonly>'+
                                        '</div>'+
                                        '<div class="col-lg-6">'+
                                            '<textarea class="form-control" name="value[]" cols="10" rows="2"></textarea>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="row mb-2">'+
                                        '<div class="col-lg-6">'+
                                            '<input type="text" class="form-control" name="name[]" value="store_id" readonly>'+
                                        '</div>'+
                                        '<div class="col-lg-6">'+
                                            '<textarea class="form-control" name="value[]" cols="10" rows="2"></textarea>'+
                                        '</div>'+
                                    '</div>';
                        
                        $("#div_credential").append(append);
                    }else if(val.split("|")[0] == "OTTOPAY"){
                        var append = '<div class="row mb-2">'+
                                        '<div class="col-lg-6">'+
                                            '<input type="text" class="form-control" name="name[]" value="partner_code" readonly>'+
                                        '</div>'+
                                        '<div class="col-lg-6">'+
                                            '<textarea class="form-control" name="value[]" cols="10" rows="2" disabled></textarea>'+
                                        '</div>'+
                                    '</div>';
                        
                        $("#div_credential").append(append);
                    }else{
                        $("#div_credential").empty();
                    }
                } else {
                    $("#payment_channel_table tbody tr").remove();
                    
                    $('#provider').prop('disabled', false);
                }
            });

            @if (old('provider'))
                var provider = "{{ old('provider') }}"

                $("#payment_channel_table tbody").append("<tr> <td colspan='2'>Loading..</td> </tr>");

                if(provider.split("|")[0] == "OTTOPAY") {
                    $("#credential").prop('disabled', true);
                } else {
                    $("#credential").prop('disabled', false);
                }

                $.get("{{ route('cms.yukk_co.partners.credentials.payment_channels', ['partner_id' => $partner['id']]) }}?provider=" + provider.split("|")[1], 
                function(data, status){
                    $("#payment_channel_table tbody tr").remove();

                    data.forEach(function(item, index) {
                        $("#payment_channel_table tbody").append("<tr> <td>" + item.name + "</td> <td class='text-center'>"  
                            + (paymentChannelPartner.includes(item.code) 
                                ? "<input type='checkbox' name='payment_channels[]' value='"+ item.code +"'>"
                                : "<i class='icon-cross2 text-danger'></i>")
                            + "</td> </tr>");
                    })
                });
            @endif

        });
    </script>
    @endpush
</x-app-layout>
