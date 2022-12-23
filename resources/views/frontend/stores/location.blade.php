@extends('layouts.frontend.app')
@section('content')
<style type="text/css">
    #map {
        height: 400px;
    }

    .map-search {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .map-search .search-wrap {
        margin-right: 15px
    }

    .map-search .search-wrap input[type=text] {
        width: 100%;
        height: 100%;
        border: 1;
        box-shadow: none;
    }

    .fa-map-marker {
        position: relative;
        top: -48px;
        left: 30px;
        font-size: 23px;
    }

    .searchbox {
        padding: 1.5rem 1rem 1.5rem 1rem;
        position: relative;
        flex-grow: 1 !important;
    }

    .border {
        border: 2px solid #000 !important;
        border-radius: 1.75rem;
    }

    .current {
        border: none;
        z-index: 24;
        background: none;
        color: #000;
        text-decoration: none;
        outline: none !important;
    }

    .gm-ui-hover-effect {
        outline: none !important;
    }

    .view-btn.grid-view {
        background-image: url({{ asset('frontend/images/grid-view-icon.png') }});
    }

    .view-btn.list-view {
        background-image: url({{ asset('frontend/images/list-view-icon.png') }});
    }

    .view-btn.active {
        background-position: 0 -42px;
    }
    .view-btn {
        display: inline-block;
        width: 50px;
        height: 34px;
        background-repeat: no-repeat;
        background-position: 0 0;
        margin-left: 16px;
    }

    .categorylist {
        display: none;
    }

    .select2-container--default .select2-selection--multiple {
        border-radius: 1.75rem !important;
    }

    .pagination {
        justify-content: center;
    }
</style>

<div class="page-header">
    <div class="page-header__container container">
        <div class="page-header__breadcrumb ">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}">Home</a>
                        <svg class="breadcrumb-arrow" width="6px" height="9px">
                            <use xlink:href="{{ asset('frontend/images/sprite.svg') }}#arrow-rounded-right-6x9"></use>
                        </svg>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Cashback To Your Door</li>
                </ol>
            </nav>
        </div>
        <div class="container p-2 my-2">
            <div class="row">
                <div class="col-12">
                    <div class="block-finder__body">
                            <img class="banner__size" style="width:1110px;" src="{{url('storage/photos/static_image_banner.jpg')}}" alt="Store Image Missing">
                            <div class="block-finder__header">
                                <div class="block-finder__title">Cashback To Your Door</div>
                                <div class="block-finder__subtitle"></div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="category-text panel rounded-border mb-4 mt-4 pt-3">
            <p>
                With Cashblack To Your Door, you can discover local restaurants and grocery stores to order African, Caribbean or 
                from any other authentic Black-owned vendors and earn cashback through our partnerships with your favourite 
                delivery apps such as Deliveroo and Uber Eats. Whether there’s rice at home or not, you can be sure that through 
                Cashblack To Your Door, you’ll always be able to find local Black-owned retailers for your next order.
                You can go to <span class="text-bold text-success"> Grocery Stores</span> or 
                <span class="text-bold text-success">Restaurants.</span>
            </p>
        </div>
        <div class="page-header__title">
            <div class="row">
                <div class="col-md-12 pl-0">
                    <h1 class="col-md-5 float-left">Cashback To Your Door</h1>
                    <div class="col-md-5 float-right  d-flex flex-justify-between">
                        <select class="form-control width store form-control-select2" multiple
                            data-placeholder="Select Stores" id="select-categories" onchange="showStores()">
                            <option value="default_option">All</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <a href="javascript:;" id="gridview" class="grid-view view-btn mt-1 active"></a>
                        <a href="javascript:;" id="listview" class="list-view view-btn mt-1"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="map-search">
        <button href="javascript:;" class="d-sm-block current  mb-3" style="outline:0 !important" onclick="getCurrentLocation();"><img src="{{ asset('frontend/images/map-pointer.png') }}" alt="" style="width: 85%;" /></button>
        <div class="search-wrap searchbox">
            <div>
                <div class="d-none">
                    <div id="title">Autocomplete search</div>
                    <div id="type-selector" class="pac-controls">
                        <input type="radio" name="type" id="changetype-all" checked="checked" />
                        <label for="changetype-all">All</label>

                        <input type="radio" name="type" id="changetype-establishment" />
                        <label for="changetype-establishment">establishment</label>

                        <input type="radio" name="type" id="changetype-address" />
                        <label for="changetype-address">address</label>

                        <input type="radio" name="type" id="changetype-geocode" />
                        <label for="changetype-geocode">geocode</label>

                    <input type="radio" name="type" id="changetype-cities" />
                    <label for="changetype-cities">(cities)</label>

                    <input type="radio" name="type" id="changetype-regions" />
                    <label for="changetype-regions">(regions)</label>
                </div>
                <br />
                <div id="strict-bounds-selector" class="pac-controls">
                    <input type="checkbox" id="use-location-bias" value="" checked />
                    <label for="use-location-bias">Bias to map viewport</label>

                    <input type="checkbox" id="use-strict-bounds" value="" />
                    <label for="use-strict-bounds">Strict bounds</label>
                </div>
            </div>
                <div>
                    <input id="pac-input" style="padding-left: 50px; " name="user_address" class="form-control searchbox border" type="text" placeholder="Enter a locations" value="" />
                    <span><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                </div>
            </div>
        </div>
    </div>
    {{-- <div id="infowindow-content" class="d-none">
        <span id="place-name" class="title"></span><br />
        <span id="place-address"></span>
    </div>
    <button class="icon-btn black d-none"><i class="bi bi-search"></i></button> --}}
</div>

<div class="container mt-4">
    <div class="map-area">
        <div id="floating-panel" style="display: none;">
            <b>Mode of Travel:</b>
            <select id="mode">
                <option value="DRIVING" selected>Driving</option>
                <option value="WALKING">Walking</option>
                <option value="TRANSIT">Transit</option>
            </select>
        </div>
        <div id="map"></div>
    </div>
</div>

<div id="get-stores">
    @include('frontend.stores.stores')
</div>

    {{-- for maping variables --}}
    <?php
    $radius = '';
    $user_lat = '';
    $user_lng = '';
    ?>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#listview').on('click', function() {
                $('#gridview.active').removeClass('active');
                $(this).addClass('active');
                $('.categorylist').css("display", "block");
                $('.categorygrid').css("display", "none");
            });

            $('#gridview').on('click', function() {
                $('#listview.active').removeClass('active');
                $(this).addClass('active');
                $('.categorygrid').css("display", "block");
                $('.categorylist').css("display", "none");
            });

            $("select.store").change(function() {
                var selectedStore = $(this).children("option:selected").val();
                if (selectedStore != "default_option") {
                    $(".all_stores").css("display", "none");
                    $(selectedStore + "_store").css("display", "block");
                } else {
                    $(".all_stores").css("display", "block");
                }
            });
        });

        ajaxPagination();

        var map;
        var center;
        var infowindow;
        var directionsService;
        var directionsRenderer;
        var service;
        let myLat = "";
        let myLng = "";

        var locations = <?php print_r(json_encode($locations)); ?>;
        locations = locations.data;

        function getLocation() {
            if (navigator.geolocation) {

                navigator.geolocation.getCurrentPosition(showPosition, errorCashback);
            } else {
                console.log("Geolocation is not supported by this browser.");
            }
        }

        // Show stores on select categories
        function showStores() {
            let storeValue = $('#select-categories').val();
            let url = "{{ route('store.location') }}";
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    id: storeValue,
                },
                success: function(response) {
                    let html = $('#get-stores').html(response);
                },
                error: function(errors) {
                    console.log(errors)
                }
            });
        }

        function showPosition(position) {

            myLat = position.coords.latitude;
            myLng = position.coords.longitude;

            if (getCookie('position_latitude') && getCookie('position_longitude')) {} else {

                setCookie("position_latitude", myLat);
                setCookie("position_longitude", myLng);
                window.location.reload();
            }


        }

        function errorCashback(error) { //console.log("User Rejected geolocation");
            if (error.code == error.PERMISSION_DENIED) {
                myLat = 51.509865;
                myLng = -0.118092;

                if (getCookie('position_latitude') && getCookie('position_longitude')) {

                } else {
                    setCookie("position_latitude", myLat);
                    setCookie("position_longitude", myLng);
                    window.location.reload();
                }
            }
        }

        function initMap() {

            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer();
            getLocation();
            setTimeout(function() {

                center = new google.maps.LatLng(myLat, myLng);

                infowindow = new google.maps.InfoWindow();
                var marker, i;
                var origins = [];
                var destinations = [];
                map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 7,
                    center: center,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    mapTypeControl: false,
                });

                addressLocationSearch();
                var markerTime = 500;

                setTimeout(function() {
                    directionRenderFn();
                }, markerTime);
            }, 1000);

            document.getElementById("mode").addEventListener("change", () => {
                calculateAndDisplayRoute();
            });

        }

        window.initMap = initMap;

        function getCurrentLocation() {

            infoWindow = new google.maps.InfoWindow({
                content: "<img src=<?= url('') ?>/frontend/images/human1.png>"
            });
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };

                        myLat = position.coords.latitude;
                        myLng = position.coords.longitude;
                        center = new google.maps.LatLng(myLat, myLng);

                        infoWindow.setPosition(pos);

                        infoWindow.open(map);

                        map.setCenter(pos);
                    },
                    () => {
                        handleLocationError(true, infoWindow, map.getCenter());
                    }
                );
            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false, infoWindow, map.getCenter());
            }
        }

        function calculateAndDisplayRoute(dLat = null, dLng = null) {

            if (dLat != null && dLng != null) {
                $("#destinationLat").val(dLat);
                $("#destinationLng").val(dLng);
            }
            if ((dLat == null && $("#destinationLat").val() == "") && (dLng == null && $("#destinationLng").val() == "")) {
                window.alert('Please Select Destination First ');
                return false;
            }
            var pointA = center;
            var pointB = new google.maps.LatLng(dLat, dLng);

            service = new google.maps.DistanceMatrixService();
            const selectedMode = document.getElementById("mode").value;


            var pointBB = new google.maps.LatLng($("#destinationLat").val(), $("#destinationLng").val());

            var start = pointA;
            var end = pointBB;

            var request = {
                origin: start,
                destination: end,
                travelMode: google.maps.TravelMode[selectedMode]
            };

            var travelModeIcon = "fa fa-car";
            if (selectedMode == "WALKING") {
                var travelModeIcon = "fa fa-male";
            }
            directionsService.route(request, function(result, status) {

                if (status == 'OK') {
                    directionsRenderer.setDirections(result);
                    var request = {
                        origins: [start],
                        destinations: [end],
                        travelMode: google.maps.TravelMode[selectedMode],
                        unitSystem: google.maps.UnitSystem.METRIC,
                        avoidHighways: false,
                        avoidTolls: false,

                    };
                    service.getDistanceMatrix(request).then((response) => {

                        var element = response.rows[0].elements[0];
                        infowindow.setContent('<i class="' + travelModeIcon + '" aria-hidden="true"></i> ' +
                            element.distance.text +
                            '<br><i class="fa fa-clock-o" aria-hidden="true"></i> ' + element.duration
                            .text);
                        infowindow.open({
                            map,
                            shouldFocus: false,
                        });

                    });
                    directionsRenderer.setMap(map);
                } else {
                    window.alert('Directions request failed due to ' + status);
                }
            });
        }


        //new
        function directionRenderFn() {

            directionsRenderer.setMap(map);
            infowindow = new google.maps.InfoWindow();

            var marker, i;
            var origins = [];
            var destinations = [];

            for (i = 0; i < locations.length; i++) {

                for (j = 0; j < locations[i]['store_address'].length; j++) {
                    var address = locations[i]['store_address'];
                    origins.push(new google.maps.LatLng(myLat, myLng));
                    destinations.push(new google.maps.LatLng(address[j]['latitude'], address[j]['longitude']));

                    marker = new google.maps.Marker({
                        position: new google.maps.LatLng(address[j]['latitude'], address[j]['longitude']),
                        map: map,
                        icon: '<?= url('') ?>/frontend/images/map-cart.png'
                    });
                    var origin = window.location.origin;
                    const contentString =
                        '<div id="content">' +
                        '<div id="siteNotice">' +
                        "</div>" +
                        '<div id="mapPopupHeader">' +
                        '<a href="' + origin + '/cashback/silk-center' +
                        '"><div id="headerTitleAddress"><h4 id="firstHeading" class="firstHeading">' + locations[i][
                        'name'] + '</h4></a>' +
                        "</div></div>" +
                        '<div id="bodyContent">' +
                        '<p><span class="addressIcon"><i class="ion-location mr2" aria-hidden="true"></i></span>' +
                        locations[i]['store_address'][j]['address'] + '</p>' +
                        '<div class="storeTimings d-none">' +
                        '<h4><span><i class="ion-clock mr2" aria-hidden="true"></i></i></span>Timings</h4>' +
                        '<ul>12PM</ul>' +
                        '</div>' +
                        '<p id="directionBtn"><button class="btn btn-primary" onclick="calculateAndDisplayRoute(' +
                        locations[i]['store_address'][j]['latitude'] + ',' + locations[i]['store_address'][j]['longitude'] +
                        ')">GET DIRECTION</button></p>' +
                        "</div>" +
                        "</div>";

                    google.maps.event.addListener(marker, 'click', (function(marker, i) {
                        return function() {
                            infowindow.setContent(contentString);
                            infowindow.open(map, marker);
                        }
                    })(marker, i));
                }
            }
            service = new google.maps.DistanceMatrixService();

            var request = {
                origins: origins,
                destinations: destinations,
                travelMode: 'DRIVING',
                unitSystem: google.maps.UnitSystem.IMPERIAL,
                avoidHighways: false,
                avoidTolls: false,
            };

            service.getDistanceMatrix(request).then((response) => {

                var elementRows = response.rows;
                $(".calculatedDistance").each(function(index) {
                    locations.push(parseFloat(elementRows[index].elements[index].distance.text.replace(
                        /[^\d.]/g, '')));

                    $(this).html(elementRows[index].elements[index].distance.text + "les away");
                });
            });
            setTimeout(function() {
                var arrs = [];
                var arrs = locations;

                orderByDistanceRendering(arrs);
            }, 700);
        }



        //serach location on field
        function addressLocationSearch() {
            /*****For Address Search input field Starts*****/

            const card = document.getElementById("pac-card");
            const input = document.getElementById("pac-input");
            const biasInputElement = document.getElementById("use-location-bias");
            const strictBoundsInputElement = document.getElementById("use-strict-bounds");
            const options = {
                fields: ["formatted_address", "geometry", "name"],
                strictBounds: false,
                types: ["establishment"],
            };
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(card);

            const autocomplete = new google.maps.places.Autocomplete(input, options);

            // Bind the map's bounds (viewport) property to the autocomplete object,
            // so that the autocomplete requests use the current map bounds for the
            // bounds option in the request.
            autocomplete.bindTo("bounds", map);

            var infowindow = new google.maps.InfoWindow();
            var infowindowContent = document.getElementById("infowindow-content");

            infowindow.setContent(infowindowContent);

            const marker = new google.maps.Marker({
                map,
                anchorPoint: new google.maps.Point(0, -29),
            });

            autocomplete.addListener("place_changed", () => {
                infowindow.close();
                marker.setVisible(false);

                const place = autocomplete.getPlace();
                let pos = place.geometry.location;
                var cur_lat = place.geometry.location.lat();
                var cur_lng = place.geometry.location.lng();
                myLat = cur_lat;
                myLng = cur_lng;
                center = new google.maps.LatLng(myLat, myLng);


                map.panTo(pos);

                if (!place.geometry || !place.geometry.location) {
                    // User entered the name of a Place that was not suggested and
                    // pressed the Enter key, or the Place Details request failed.
                    window.alert("No details available for input: '" + place.name + "'");
                    return;
                }

                // If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    map.setZoom(8);
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(8);
                }

                marker.setPosition(place.geometry.location);
                marker.setVisible(true);
                infowindowContent.children["place-name"].textContent = place.name;
                infowindowContent.children["place-address"].textContent =
                    place.formatted_address;
                infowindow.open(map, marker);
            });

            // Sets a listener on a radio button to change the filter type on Places
            // Autocomplete.
            function setupClickListener(id, types) {
                const radioButton = document.getElementById(id);

                radioButton.addEventListener("click", () => {
                    autocomplete.setTypes(types);
                    input.value = "";
                });
            }

            setupClickListener("changetype-all", []);
            setupClickListener("changetype-address", ["address"]);
            setupClickListener("changetype-establishment", ["establishment"]);
            setupClickListener("changetype-geocode", ["geocode"]);
            setupClickListener("changetype-cities", ["(cities)"]);
            setupClickListener("changetype-regions", ["(regions)"]);
            biasInputElement.addEventListener("change", () => {
                if (biasInputElement.checked) {
                    autocomplete.bindTo("bounds", map);
                } else {
                    // User wants to turn off location bias, so three things need to happen:
                    // 1. Unbind from map
                    // 2. Reset the bounds to whole world
                    // 3. Uncheck the strict bounds checkbox UI (which also disables strict bounds)
                    autocomplete.unbind("bounds");
                    autocomplete.setBounds({
                        east: 180,
                        west: -180,
                        north: 90,
                        south: -90
                    });
                    strictBoundsInputElement.checked = biasInputElement.checked;
                }

                input.value = "";
            });
            strictBoundsInputElement.addEventListener("change", () => {
                autocomplete.setOptions({
                    strictBounds: strictBoundsInputElement.checked,
                });
                if (strictBoundsInputElement.checked) {
                    biasInputElement.checked = strictBoundsInputElement.checked;
                    autocomplete.bindTo("bounds", map);
                }

                input.value = "";
            });

            /*****For Address Search input field End********/
        }


        function setCookie(cname, cvalue, exdays) {
            const d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            let expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function getCookie(cname) {
            let name = cname + "=";
            let decodedCookie = decodeURIComponent(document.cookie);
            let ca = decodedCookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        function orderByDistanceRendering(arrs) {

            $("#storesListN").empty();
            var storesHtml = "";
            $.each(arrs, function(index, location) {
                var tags = '<ul class="tags">';
                if (location[14] != "" && location[14] != null) {
                    var menus = location[14].split(",");
                    $.each(menus, function(index, menu) {
                        tags += '<li><a href="' + location[13] + '/' + menu + '">' + location[15][menu] +
                            '</a></li>';
                    })
                }
                tags += '</ul>';
                storesHtml += '<li class="list-item col">\n' +
                    '                            <a href="' + location[5] + '">\n' +
                    '                                <div class="takeaway-item__logo">\n' +
                    '                                    <img src="' + location[12] +
                    '" alt="" class="img-fluid">\n' +
                    '                                    <span class="logo-wrap">\n' +
                    '                                        <img src="' + location[4] + '" alt="" class="">\n' +
                    '                                    </span>\n' +
                    '                                </div>\n' +
                    '                            </a>\n' +
                    '                            <div class="category-item__detail">\n' +
                    '                                <div class="item-name-tags">\n' +
                    '                                    <h5 class="store-name">' + location[0] + '</h5>\n' +
                    '                                    ' + tags + '\n' +
                    '                                <p class="address">' + location[6].replace("-", "'") +
                    '</p>\n' +
                    '                                <div class="distance calculatedDistance" id="distance">' +
                    location[16] + ' miles away</div>\n' +
                    '                            </div>\n' +
                    '                        </li>';
            });

            $("#storesListN").html(storesHtml);
        }

        // Ajax pagination
        function ajaxPagination () {
            $('.pagination a').on('click', function(e){
                e.preventDefault();
                form = $(this);
                var url = $(this).attr('href');
                $.get(url, form.serialize(), function(data){
                    $('#get-stores').html(data);
                    ajaxPagination()
                });
            });
        }
    </script>
    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key={{ SiteSetting()['map_key'] }}&callback=initMap&libraries=places&v=weekly" async></script>
@endpush
