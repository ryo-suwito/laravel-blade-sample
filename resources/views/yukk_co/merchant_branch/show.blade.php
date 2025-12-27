@extends('layouts.master')

@section('html_head')
    <style type="text/css">
        #myMap {
            margin: 10px;
            width: 100%;
            height: 400px;
            padding: 10px;
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
                <h4>@lang('cms.Merchant Branch Detail')</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('cms.Home')</a>
                    <a href="{{ route("yukk_co.merchant_branch.list") }}" class="breadcrumb-item">@lang("cms.Merchant Branch List")</a>
                    <span class="breadcrumb-item active">@lang("cms.Detail")</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">@lang("cms.Detail")</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-sm-6 form-horizontal">
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.Company')</label>
                        <div class="col-9">
                            <input type="text" class="form-control" readonly
                                   value="{{ @$merchant_branch->merchant->company->name }}">
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.Merchant')</label>
                        <div class="col-9">
                            <input type="text" class="form-control" readonly
                                   value="{{ @$merchant_branch->merchant->name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.Owner')</label>
                        <div class="col-9">
                            <input type="text" class="form-control" readonly
                                   value="{{ isset($merchant_branch->owner) ? $merchant_branch->owner->name : '' }}">
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
                        <label class="col-3 mt-1">@lang('cms.Partner')</label>
                        <div class="col-9">
                            <input type="text" class="form-control" readonly
                                   value="{{ @$partner_has_merchant_branch->partner->name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.Name')</label>
                        <div class="col-9">
                            <input type="text" class="form-control" name="name" id="name"
                                   value="{{ @$merchant_branch->name }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                            <label class="col-3 mt-1">@lang("cms.Merchant Type")</label>
                            <div class="col-9">
                                <input type="text" class="form-control" name="type" id="type"
                                    value="{{ Str::upper(@$merchant_branch->type) }}" readonly>
                                </div>
                            </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.Start Date')</label>
                        <div class="col-9">
                            <input type="text" class="form-control" name="start_time" id="start_time"
                                   value="{{ $start_date }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.End Date')</label>
                        <div class="col-9">
                            <input type="text" class="form-control" name="end_time" id="end_time"
                                   value="{{ $end_date }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.Total Terminal (Kasir)')</label>
                        <div class="col-9">
                            <input type="number" class="form-control" name="total_terminal" id="total_terminal"
                                   value="{{ $merchant_branch->total_terminal }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.QR Type')</label>
                        <div class="col-9">
                            <input type="text" class="form-control" value="@if($merchant_branch->qr_type == "d") @lang("cms.Dynamic Only") @elseif($merchant_branch->qr_type == "s") @lang("cms.Static Only") @else @lang("cms.Both (Static and Dynamic)") @endif" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.Longitude')</label>
                        <div class="col-9">
                            <input type="text" class="form-control" readonly name="longitude" id="longitude"
                                   value="{{ @$merchant_branch->longitude }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.Latitude')</label>
                        <div class="col-9">
                            <input type="text" class="form-control" readonly name="latitude" id="latitude"
                                   value="{{ @$merchant_branch->latitude }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.Province')</label>
                        <div class="col-9">
                            <input type="text" class="form-control" name="name" id="name"
                                   value="{{ @$merchant_branch->province->name }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.City')</label>
                        <div class="col-9">
                            <input type="text" class="form-control" name="name" id="name"
                                   value="{{ @$merchant_branch->city->name }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.Region')</label>
                        <div class="col-9">
                            <input type="text" class="form-control" name="name" id="name"
                                   value="{{ @$merchant_branch->region->name }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.Postal Code')</label>
                        <div class="col-9">
                            <input type="text" class="form-control" name="postal_code" id="postal_code"
                                   value="{{ @$merchant_branch->zipcode }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.Address')</label>
                        <div class="col-9">
                            <textarea class="form-control" name="address" id="address" readonly>{{ @$merchant_branch->address }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.Active')</label>
                        <div class="col-9 form-control-plaintext">
                            @if (@ $merchant_branch->active)
                                <i class="icon icon-check text-success"></i>
                            @else
                                <i class="icon icon-cross3 text-danger"></i>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <input id="pac-input" disabled class="controls" type="text" placeholder="Search Box">
                    <div id="myMap"></div>
                </div>
            </div>

            @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("MASTER_DATA.MERCHANT_BRANCH_ACTIVATION.UPDATE", "AND"))
                @if (@$merchant_branch->active)
                    <div class="row">
                        <div class="col-12 text-center">
                            <form method="post" action="{{ route("yukk_co.merchant_branch.inactive") }}">
                                @csrf
                                <input name="merchant_branch_id" type="hidden" value="{{ @$merchant_branch->id }}">
                                <button class="btn btn-danger btn-confirm">@lang("cms.Delete")</button>
                            </form>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function initAutocomplete() {
            var map;
            var marker;
            var lat = document.getElementById('latitude').value;
            var long = document.getElementById('longitude').value;
            var myLatlng = '';
            if (lat || long) {
                myLatlng = new google.maps.LatLng(lat, long);
            } else {
                myLatlng = new google.maps.LatLng(-6.188075199999999, 106.73879820000002);
            }

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
                draggable: false
            });

        }

        initAutocomplete();
    </script>

    <script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key=&libraries=places&callback=initAutocomplete"
        async defer></script>

    <script>
        $(document).ready(function() {
            var countMerchant = 0;

            var initialOwnerId = "{{ $merchant_branch->owner_id }}";

            if (initialOwnerId) {
                setTimeout(() => {
                    fetchOwnerData(initialOwnerId);
                }, 1000);
            }

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

            function showLoadingSpinner() {
                $('body').after('<div class="loading"></div>');
                $('.loading').append('<div class="spinner-border text-primary justify-content-center" role="status" id="loading_spinner"><span class="sr-only">Loading...</span></div>');
            }

            function hideLoadingSpinner() {
                $('.loading').remove();
            }

            $(".btn-confirm").click(function(e) {
                if (window.confirm("@lang("cms.general_confirmation_dialog_content")")) {

                } else {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
