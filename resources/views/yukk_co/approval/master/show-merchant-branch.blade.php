<x-app-layout>
@push('styles')
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
@endpush
    <x-page.header :title="__('cms.Manage Approval')">
        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            <x-breadcrumb.link :link="$mainMenuUrl" :text="__('cms.' . $title)"/>
            <x-breadcrumb.link :link="$approvalUrl" text="Action Page"/>
            <x-breadcrumb.active>
                Master Detail
            </x-breadcrumb.active>
        </x-slot>
    </x-page.header>

    <x-page.content :title="__('cms.' . $title)">
        <div>
            <div class="row">
                <div class="col-sm-6 form-horizontal">
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.Company')</label>
                        <div class="col-9">
                            <input type="text" class="form-control" readonly
                                   value="{{ $master['merchant']['company']['name'] ?? ''}}">
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.Merchant')</label>
                        <div class="col-9">
                            <input type="text" class="form-control" readonly
                                   value="{{ $master['merchant']['name'] ?? ''}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.Owner')</label>
                        <div class="col-9">
                            <input type="text" class="form-control" readonly
                                   value="{{ $master['owner']['name'] ?? ''}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.Name')</label>
                        <div class="col-9">
                            <input type="text" class="form-control" name="name" id="name"
                                   value="{{ $master['name'] ?? '' }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.Start Date')</label>
                        <div class="col-9">
                            <input type="text" class="form-control" name="start_time" id="start_time"
                                   value="{{ date_format(date_create($master['start_date'] ?? ''), 'd-M-Y') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.End Date')</label>
                        <div class="col-9">
                            <input type="text" class="form-control" name="end_time" id="end_time"
                                   value="{{ date_format(date_create($master['end_date'] ?? ''), 'd-M-Y') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.Total Terminal (Kasir)')</label>
                        <div class="col-9">
                            <input type="number" class="form-control" name="total_terminal" id="total_terminal"
                                   value="{{ $master['total_terminal'] ?? '' }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.Longitude')</label>
                        <div class="col-9">
                            <input type="text" class="form-control" readonly name="longitude" id="longitude"
                                   value="{{ $master['longitude'] ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.Latitude')</label>
                        <div class="col-9">
                            <input type="text" class="form-control" readonly name="latitude" id="latitude"
                                   value="{{ $master['latitude'] ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.Province')</label>
                        <div class="col-9">
                            <input type="text" class="form-control" name="name" id="name"
                                   value="{{ $master['province']['name'] ?? '' }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.City')</label>
                        <div class="col-9">
                            <input type="text" class="form-control" name="name" id="name"
                                   value="{{ $master['city']['name'] ?? '' }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.Region')</label>
                        <div class="col-9">
                            <input type="text" class="form-control" name="name" id="name"
                                   value="{{ $master['region']['name'] ?? '' }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.Postal Code')</label>
                        <div class="col-9">
                            <input type="text" class="form-control" name="postal_code" id="postal_code"
                                   value="{{ $master['zipcode'] ?? '' }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.Address')</label>
                        <div class="col-9">
                            <textarea class="form-control" name="address" id="address" readonly>{{ $master['address'] ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 mt-1">@lang('cms.Active')</label>
                        <div class="col-9 form-control-plaintext">
                            @if (@ $master['active'] ?? false)
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
        </div>
    </x-page.content>

@push('scripts')
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
    </script>

    <script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initAutocomplete"
        async defer></script>
@endpush
</x-app-layout>