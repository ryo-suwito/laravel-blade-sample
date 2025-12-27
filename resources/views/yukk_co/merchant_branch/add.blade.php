@extends('layouts.master')

@section('html_head')
    <style type="text/css">
        #myMap {
            margin: 10px;
            width: 100%;
            height: 400px;
            padding: 10px;
        }

        #myMap .gm-style {
            color: black;
        }

        #description {
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
        }

        #infowindow-content .title {
            font-weight: bold;
        }

        #infowindow-content {
            display: none;
        }

        #map #infowindow-content {
            display: inline;
        }

        .pac-card {
            margin: 10px 10px 0 0;
            border-radius: 2px 0 0 2px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            outline: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            background-color: #fff;
            font-family: Roboto;
        }

        #pac-container {
            padding-bottom: 12px;
            margin-right: 12px;
        }

        .pac-controls {
            display: inline-block;
            padding: 5px 11px;
        }

        .pac-controls label {
            font-family: Roboto;
            font-size: 13px;
            font-weight: 300;
        }

        #pac-input {
            background-color: #fff;
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
            margin-left: 0px;
            padding: 0 11px 0 13px;
            text-overflow: ellipsis;
            width: 330px;
            margin-top: 13px;
        }

        #pac-input:focus {
            border-color: #4d90fe;
        }

        #title {
            color: #fff;
            background-color: #4d90fe;
            font-size: 25px;
            font-weight: 500;
            padding: 6px 12px;
        }

        #target {
            width: 345px;
        }
    </style>
@endsection

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang('cms.Add Branches')</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('cms.Home')</a>
                    <a href="{{ route("yukk_co.merchant_branch.list") }}" class="breadcrumb-item">@lang("cms.Merchant Branch List")</a>
                    <span class="breadcrumb-item active">@lang("cms.Merchant Branch Add")</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-body">
            <form method="post" id="form_add" enctype="multipart/form-data" action="{{ route('yukk_co.merchant_branch.store') }}">
                {{ csrf_field() }}
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 form-horizontal">
                            <div class="form-group row">
                                <label class="col-3 mt-1">@lang("cms.Company")</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="company_id" id="company_id">
                                        <option value="-1">Select Company</option>
                                    </select>
                                    @if ($errors->has("company_id"))
                                        <span class="help-block text-danger pt-1">{{ $errors->first("company_id") }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 mt-1">@lang("cms.Merchant")</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="merchant_id" id="merchant_id">
                                        <option value="-1">Select Merchant</option>
                                    </select>
                                    @if ($errors->has("merchant_id"))
                                        <span class="help-block text-danger pt-1">{{ $errors->first("merchant_id") }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 mt-1">@lang("cms.Owner")</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="owner_id" id="owner_id">
                                        <option value="">Select Owner</option>
                                        @foreach ($owners as $owner)
                                            <option @if(old("owner_id", "") == $owner->id) selected @endif value="{{ $owner->id }}">{{ $owner->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has("owner_id"))
                                        <span class="help-block text-danger pt-1">{{ $errors->first("owner_id") }}</span>
                                    @endif
                                </div>
                                <div class="col-12">
                                    <div id="owner_details" class="card col-lg-12 mt-3" style="display: none; background-color: black; color: white; border-radius: 20px; border: 1px solid #969699; width: 100%;">
                                        <div class="card-body">
                                            <div class="form-group row" style="margin-left: 2px; margin-bottom: -15px; align-items: center;">
                                                <p id="owner_name_label" style="font-size: 18px;"></p>
                                                <p id="owner_id_label" style="margin-left: 5px; font-size: 12px; color: #969699;"></p>
                                            </div>
                                            <hr style="border-color: #969699;">
                                            <div class="form-group row">
                                                <div class="col-lg-6">
                                                    <p id="owner_phone" style="font-size: 12px; color: #969699;"></p>
                                                    <p id="owner_email" style="font-size: 12px; color: #969699;"></p>
                                                </div>
                                                <div class="col-lg-6">
                                                    <p id="owner_ktp" style="font-size: 12px; color: #969699;"></p>
                                                    <p id="owner_npwp" style="font-size: 12px; color: #969699;"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 mt-1">@lang("cms.Partner")</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="partner_id" id="partner_id">
                                        <option value="-1">Select Partner</option>
                                    </select>
                                    @if ($errors->has("partner_id"))
                                        <span class="help-block text-danger pt-1">{{ $errors->first("partner_id") }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 mt-1">@lang("cms.Name")</label>
                                <div class="col-9">
                                    <input type="text" class="form-control @if ($errors->has("name")) is-invalid @endif" name="name" id="name" value="{{ old('name') }}">
                                    @if ($errors->has("name"))
                                        <span class="help-block text-danger pt-1">{{ $errors->first("name") }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 mt-1">@lang("cms.Merchant Type")</label>
                                <div class="col-9">
                                    <select class="form-control @if ($errors->has("type")) is-invalid @endif" name="type" id="type">
                                        <option value="ONLINE" @if(old("type", "BOTH") == "ONLINE") selected @endif>@lang("cms.ONLINE")</option>
                                        <option value="OFFLINE" @if(old("type", "BOTH") == "OFFLINE") selected @endif>@lang("cms.OFFLINE")</option>
                                        <option value="BOTH" @if(old("type", "BOTH") == "BOTH") selected @endif>@lang("cms.BOTH")</option>
                                    </select>
                                    @if ($errors->has("type"))
                                        <span class="help-block text-danger pt-1">{{ $errors->first("type") }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 mt-1">@lang("cms.Start Date")</label>
                                <div class="col-9">
                                    <input type="text" class="form-control @if ($errors->has("start_time")) is-invalid @endif" name="start_time" id="start_time" value="{{ old('start_time') }}">
                                    @if ($errors->has("start_time"))
                                        <span class="help-block text-danger pt-1">{{ $errors->first("start_time") }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 mt-1">@lang("cms.End Date")</label>
                                <div class="col-9">
                                    <input type="text" class="form-control @if ($errors->has("end_time")) is-invalid @endif" name="end_time" id="end_time" value="{{ old('end_time') }}">
                                    @if ($errors->has("end_time"))
                                        <span class="help-block text-danger pt-1">{{ $errors->first("end_time") }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 mt-1">@lang("cms.Total Terminal (Kasir)")</label>
                                <div class="col-9">
                                    <input type="number" class="form-control @if ($errors->has("total_terminal")) is-invalid @endif" name="total_terminal" id="total_terminal" value="1">
                                    @if ($errors->has("total_terminal"))
                                        <span class="help-block text-danger pt-1">{{ $errors->first("total_terminal") }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 mt-1">@lang("cms.QR Type")</label>
                                <div class="col-9">
                                    <select class="form-control @if ($errors->has("qr_type")) is-invalid @endif" name="qr_type" id="qr_type">
                                        <option value="b" @if(old("qr_type", "b") == "b") selected @endif>@lang("cms.Both (Static and Dynamic)")</option>
                                        <option value="s" @if(old("qr_type", "b") == "s") selected @endif>@lang("cms.Static Only")</option>
                                        <option value="d" @if(old("qr_type", "b") == "d") selected @endif>@lang("cms.Dynamic Only")</option>
                                    </select>
                                    @if ($errors->has("qr_type"))
                                        <span class="help-block text-danger pt-1">{{ $errors->first("qr_type") }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 mt-1">@lang("cms.Longitude")</label>
                                <div class="col-9">
                                    <input type="text" class="form-control" value="{{old('longitude')}}" readonly name="longitude" id="longitude">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 mt-1">@lang("cms.Latitude")</label>
                                <div class="col-9">
                                    <input type="text" class="form-control" value="{{old('latitude')}}" readonly name="latitude" id="latitude">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 mt-1">@lang("cms.Province")</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="province" id="province">
                                        <option>@lang('cms.Select Province')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 mt-1">@lang("cms.City")</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="city" id="city">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 mt-1">@lang("cms.Region")</label>
                                <div class="col-9">
                                    <select class="form-control select2" name="region" id="region">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 mt-1">@lang("cms.Postal Code")</label>
                                <div class="col-9">
                                    <input type="text" class="form-control @if ($errors->has("postal_code")) is-invalid @endif" name="postal_code" id="postal_code" value="{{ old('postal_code') }}">
                                    @if ($errors->has("postal_code"))
                                        <span class="help-block text-danger pt-1">{{ $errors->first("postal_code") }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 mt-1">@lang("cms.Address")</label>
                                <div class="col-9">
                                    <input type="text" class="form-control @if ($errors->has("address")) is-invalid @endif" name="address" id="address" value="{{ old('address') }}">
                                    @if ($errors->has("address"))
                                        <span class="help-block text-danger pt-1">{{ $errors->first("address") }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <input id="pac-input" class="controls" type="text" placeholder="Search Box">
                            <div id="myMap"></div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center mb-5">
                    <button class="btn btn-primary btn-block col-3 submitBtn" type="submit">@lang("cms.Submit")</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('/js/plugins/axios/axios.min.js') }}"></script>
    <script>
        function initAutocomplete() {
            var map;
            var marker;
            var myLatlng = new google.maps.LatLng(-6.188075199999999, 106.73879820000002);
            var geocoder = new google.maps.Geocoder();
            var infowindow = new google.maps.InfoWindow();

            var mapOptions = {
                zoom: 13,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("myMap"), mapOptions);
            // Create the search box and link it to the UI element.
            var input = document.getElementById('pac-input');
            var searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            // Bias the SearchBox results towards current map's viewport.
            map.addListener('bounds_changed', function() {
                searchBox.setBounds(map.getBounds());
            });

            marker = new google.maps.Marker({
                map: map,
                position: myLatlng,
                draggable: true
            });

            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener('places_changed', function() {
                var places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }

                // For each place, get the icon, name and location.
                var bounds = new google.maps.LatLngBounds();
                places.forEach(function(place) {
                    if (!place.geometry) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
//

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        map.center = place.geometry.location;
                        bounds.union(place.geometry.viewport);
                        marker.setMap(null);
                        myLatlng = new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng());
                        marker = new google.maps.Marker({
                            map: map,
                            position: myLatlng,
                            draggable: true
                        });

                        geocoder.geocode({'latLng': place.geometry.location}, function (results, status) {

                            if (status == google.maps.GeocoderStatus.OK) {

                                if (results[0]) {
                                    $('#address').val('');
                                    $('#latitude').val('');
                                    $('#longitude').val('');

                                    $('#address').val(results[0].formatted_address);
                                    $('#latitude').val(place.geometry.location.lat());
                                    $('#longitude').val(place.geometry.location.lng());
                                    $.getJSON('https://nominatim.openstreetmap.org/reverse?lat='+place.geometry.location.lat()+'&lon='+place.geometry.location.lng()+'&format=json', function(data) {
                                        $('#postal_code').val(data.address.postcode);
                                    });

                                    infowindow.setContent(results[0].formatted_address);
                                    infowindow.open(map, marker);
                                }
                            }


                        });
                        google.maps.event.addListener(marker, 'dragend', function () {

                            geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
                                if (status == google.maps.GeocoderStatus.OK) {
                                    if (results[0]) {
                                        $('#address').val('');
                                        $('#latitude').val('');
                                        $('#longitude').val('');

                                        $('#address').val(results[0].formatted_address);
                                        $('#latitude').val(marker.getPosition().lat());
                                        $('#longitude').val(marker.getPosition().lng());
                                        $.getJSON('https://nominatim.openstreetmap.org/reverse?lat='+marker.getPosition().lat()+'&lon='+marker.getPosition().lng()+'&format=json', function(data) {
                                            $('#postal_code').val(data.address.postcode);
                                        });
                                        $('input[name=postal_code]').on('change', function() { $('#postal_code').val(data.address.postcode) });

                                        infowindow.setContent(results[0].formatted_address);
                                        infowindow.open(map, marker);
                                    }
                                }
                            });
                        });

                    } else {
                        bounds.extend(place.geometry.location);
                    }

                });
                map.fitBounds(bounds);
            });
            google.maps.event.addListener(marker, 'dragend', function () {

                geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
//                        console.log(results);
                        if (results[0]) {
                            $('#address').val('');
                            $('#latitude').val('');
                            $('#longitude').val('');

                            $('#address').val(results[0].formatted_address);
                            $('#latitude').val(marker.getPosition().lat());
                            $('#longitude').val(marker.getPosition().lng());

                            $.getJSON('https://nominatim.openstreetmap.org/reverse?lat='+marker.getPosition().lat()+'&lon='+marker.getPosition().lng()+'&format=json', function(data) {
                                $('#postal_code').val(data.address.postcode);
                            });
                            $('input[name=postal_code]').on('change', function() { $('#postal_code').val(data.address.postcode) });

                            infowindow.setContent(results[0].formatted_address);
                            infowindow.open(map, marker);
                        }
                    }
                });
            });
//                    google.maps.event.addDomListener(window, 'load', initAutocomplete);
        }

        initAutocomplete();
    </script>

    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initAutocomplete"
            async defer></script>

    <script>
        $(document).ready(function () {
            $(document).on('select2:open', () => {
                document.querySelector('.select2-container--open .select2-search__field').focus();
            });

            let maxDate = $('#start_time').val();
            $("#start_time").daterangepicker({
                parentEl: '.content-inner',
                singleDatePicker: true,
                locale: {
                    format: 'DD-MMM-YYYY',
                    firstDay: 1,
                },
            }).on('change', function (selected) {
                maxDate = selected['target']['value'];
            });

            $("#end_time").daterangepicker({
                parentEl: '.content-inner',
                singleDatePicker: true,
                locale: {
                    format: 'DD-MMM-YYYY',
                },
                minDate: {
                    maxDate
                },
            });

            // add onchange start_time, re-set minDate end_time
            $("#start_time").on('change', function() {
                var start_date = new Date($(this).val());
                $("#end_time").daterangepicker({
                    parentEl: '.content-inner',
                    singleDatePicker: true,
                    locale: {
                        format: 'DD-MMM-YYYY',
                        firstDay: 1,
                    },
                    minDate: start_date,
                });
            });

            $("#form_add").submit(function () {
                $(".submitBtn").attr("disabled", true);
                return true;
            });

            $('#owner_id').on('change', function() {
                var ownerId = $(this).val();
                if (ownerId) {
                    fetchOwnerData(ownerId);
                } else {
                    $('#owner_details').hide();
                }
            });

            // Fungsi untuk mem-fetch data owner
            function fetchOwnerData(ownerId) {
                showLoadingSpinner();
                $.ajax({
                    url: '{{ route('yukk_co.owners.get_owner', ['id' => ':id']) }}'.replace(':id', ownerId),
                    type: 'GET',
                    data: { id: ownerId },
                    success: function(response) {
                        hideLoadingSpinner();
                        ownerData = response.owner;
                        if (ownerId) {
                            $('#owner_details').show();
                        } else {
                            $('#owner_details').hide();
                        }

                        if (ownerData.ktp_url) {
                            $('#owner_image').attr('src', ownerData.ktp_url).show();
                        } else {
                            $('#owner_image').hide();
                        }

                        $('#owner_name_label').text(ownerData.name || '-');
                        $('#owner_name').text('Name: ' + (ownerData.name || '-'));
                        $('#owner_phone').text('Phone: ' + (ownerData.phone || '-'));
                        $('#owner_email').text('Email: ' + (ownerData.email || '-'));
                        $('#owner_ktp').text('KTP: ' + (ownerData.id_card_number || '-'));
                        $('#owner_npwp').text('NPWP: ' + (ownerData.npwp_number || '-'));
                    },
                    error: function(xhr, status, error) {
                        hideLoadingSpinner();
                        console.error('Error fetching owner details:', error);
                        Swal.fire({
                            title: "Error fetching owner",
                            text: "Data reading is not successful. Please retry the file upload.",
                            icon: "error",
                            button: "OK",
                        });
                    }
                });
            }
        
            $.ajax({
                url: "{{ route("yukk_co.province.list_json") }}",
                method: "POST",
                data: {
                    "_token": '{{ csrf_token() }}',
                },
                success: function(data) {
                    $("#province").html(""); // Clear the Selection
                    if (data) {
                        $("#province").append("<option value=''>Select Province</option>");
                        $(data).each(function (index, item) {
                            var option = $("<option></option>");
                            option.html(item.name);
                            option.val(item.id);
                            if (item.id == "{{ old('province') }}") {
                                option.attr("selected", true);
                            }
                            $("#province").append(option);
                        });
                    }
                },
            });

            $('#province').change(function () {
                var self = $(this);
                var provinceId = self.val();

                $.ajax({
                    url: "{{ route("yukk_co.city.list_json") }}",
                    method: "POST",
                    data: {
                        "province_id": provinceId,
                        "_token": '{{ csrf_token() }}',
                    },
                    success: function(data) {
                        $("#city").html("");
                        $("#region").html("");
                        $("#region").append("<option value=''>Select Region</option>");
                        $("#city").append("<option value=''>Select City</option>");
                        if (data) {
                            $(data).each(function (index, item) {
                                var option = $("<option></option>");
                                option.html(item.name);
                                option.val(item.id);
                                if (item.id == "{{ old('city') }}") {
                                    option.attr("selected", true);
                                }
                                $("#city").append(option);
                            });
                        }
                    },
                });
            });
            $('#city').change(function () {
                var self = $(this);
                var cityId = self.val();

                $.ajax({
                    url: "{{ route("yukk_co.region.list_json") }}",
                    method: "POST",
                    data: {
                        "city_id": cityId,
                        "_token": '{{ csrf_token() }}',
                    },
                    success: function(data) {
                        $("#region").html(""); // Clear the Selection
                        $("#region").append("<option value=''>Select Region</option>");
                        if (data) {
                            $(data).each(function (index, item) {
                                var option = $("<option></option>");
                                option.html(item.name);
                                option.val(item.id);
                                if (item.id == "{{ old('region') }}") {
                                    option.attr("selected", true);
                                }
                                $("#region").append(option);
                            });
                        }
                    },
                });
            });
            
            if("{{ old('city') }}") {
                var provinceId = "{{ old('province') }}" ?? $('#province').val();

                $.ajax({
                    url: "{{ route("yukk_co.city.list_json") }}",
                    method: "POST",
                    data: {
                        "province_id": provinceId,
                        "_token": '{{ csrf_token() }}',
                    },
                    success: function(data) {
                        $("#city").html("");
                        $("#region").html("");
                        $("#region").append("<option value=''>Select Region</option>");
                        $("#city").append("<option value=''>Select City</option>");
                        if (data) {
                            $(data).each(function (index, item) {
                                var option = $("<option></option>");
                                option.html(item.name);
                                option.val(item.id);
                                if (item.id == "{{ old('city') }}") {
                                    option.attr("selected", true);
                                }
                                $("#city").append(option);
                            });
                        }
                    },
                });
            }
            if("{{ old('region') }}") {
                var cityId = "{{ old('city') }}" ?? $('#city').val();

                $.ajax({
                    url: "{{ route("yukk_co.region.list_json") }}",
                    method: "POST",
                    data: {
                        "city_id": cityId,
                        "_token": '{{ csrf_token() }}',
                    },
                    success: function(data) {
                        $("#region").html(""); // Clear the Selection
                        $("#region").append("<option value=''>Select Region</option>");
                        if (data) {
                            $(data).each(function (index, item) {
                                var option = $("<option></option>");
                                option.html(item.name);
                                option.val(item.id);
                                if (item.id == "{{ old('region') }}") {
                                    option.attr("selected", true);
                                }
                                $("#region").append(option);
                            });
                        }
                    },
                });
            }
        });
        
        function showLoadingSpinner() {
            $('body').after('<div class="loading"></div>');
            $('.loading').append('<div class="spinner-border text-primary justify-content-center" role="status" id="loading_spinner"><span class="sr-only">Loading...</span></div>');
        }

        function hideLoadingSpinner() {
            $('.loading').remove();
        }
    </script>
@endsection

@section('post_scripts')
    <script>
        $(document).ready(function() {
            $('#company_id').select2({
                ajax: {
                    url: "{{ route("yukk_co.company.list_json") }}",
                    type: "POST",
                    dataType: 'json',
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1,
                            _token: '{{ csrf_token() }}'
                        };
                    },
                    processResults: function(data, params) {
                        let more = data.more;
                        params.page = params.page || 1;

                        let result = data.result.map(function(item) {
                            item.id = item.id;
                            item.text = item.name;

                            return item;
                        });

                        return {
                            pagination: {
                                more: more
                            },
                            results: result,
                        };
                    },
                    cache: true
                },
                placeholder: "Select Company",
            });

            let oldCompanyId = "{{ old('company_id') }}";
            let oldPartnerId = "{{ old('partner_id') }}";

            @if(old('company_id'))
                $.ajax({
                    url: "{{ route('yukk_co.company.list_json') }}",
                    type: "POST",
                    data: {
                        'flag': 'old',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.result.length > 0) {
                            data.result.forEach(function(item) {
                                if (item.id == oldCompanyId) {
                                    var option = new Option(item.name, item.id, true, true);
                                    $('#company_id').append(option).trigger('change');
                                }
                            });
                        }
                    }
                });
            @endif

            $('#company_id').on('change', function() {
                var companyId = $(this).val();

                showLoadingSpinner();
                if (companyId !== -1){
                    $.ajax({
                        url: "{{ route("yukk_co.merchant.list_json") }}",
                        method: "POST",
                        data: {
                            "company_id": companyId,
                            "_token": '{{ csrf_token() }}',
                        },
                        success: function(data) {
                            hideLoadingSpinner();
                            $("#merchant_id").html(""); // Clear the Selection
                            $("#merchant_id").append("<option value=''>Select Merchant</option>");
                            if (data) {
                                $(data).each(function (index, item) {
                                    var option = $("<option></option>");
                                    option.html(item.name);
                                    option.val(item.id);
                                    if (item.id == "{{ old('merchant_id') }}") {
                                        option.attr("selected", true);
                                    }
                                    $("#merchant_id").append(option);
                                });
                            }
                        },
                    });
                }
            });

            $('#partner_id').select2({
                ajax: {
                url: "{{ route("yukk_co.partner.list_json") }}",
                    type: "POST",
                    dataType: 'json',
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1,
                            _token: '{{ csrf_token() }}'
                        };
                    },
                    processResults: function(data, params) {
                        let more = data.more;
                        params.page = params.page || 1;

                        let result = data.result.map(function(item) {
                            item.id = item.id;
                            item.text = item.name;

                            return item;
                        });

                        return {
                            pagination: {
                                more: more
                            },
                            results: result,
                        };
                    },
                    cache: true
                },
                placeholder: "Select Partner",
            });

            @if(old('partner_id'))
                $.ajax({
                    url: "{{ route("yukk_co.partner.list_json") }}",
                    type: "POST",
                    data: {
                        flag: "old",
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.result.length > 0) {
                            data.result.forEach(function(item) {
                                if (item.id == oldPartnerId) {
                                    var option = new Option(item.name, item.id, true, true);
                                    $('#partner_id').append(option).trigger('change');
                                }
                            });
                        }
                    }
                });
            @endif
        });
    </script>
@endsection
