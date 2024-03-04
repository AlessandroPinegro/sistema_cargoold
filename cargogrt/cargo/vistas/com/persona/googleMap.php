
<?php
include_once __DIR__ . '/../util/Seguridad.php';
include_once __DIR__ . '/../../../util/Configuraciones.php';
$url_libs_imagina = Configuraciones::url_base() . "/vistas/libs/imagina/";
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="shortcut icon" href="<?php echo Configuraciones::url_base(); ?>vistas/images/icono_ittsa.ico">


        <!-- Bootstrap core CSS -->
        <link href="<?php echo $url_libs_imagina; ?>css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo $url_libs_imagina; ?>css/bootstrap-reset.css" rel="stylesheet">

        <!--Animation css-->
        <link href="<?php echo $url_libs_imagina; ?>css/animate.css" rel="stylesheet">

        <!--Icon-fonts css-->
        <link href="<?php echo $url_libs_imagina; ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        <link href="<?php echo $url_libs_imagina; ?>assets/ionicon/css/ionicons.min.css" rel="stylesheet" />

        <title>ITTSA CARGO - INTERNACIONAL DE TRANSPORTE TURISTICO Y SERVICIOS SRL</title>
        <style>
            html, body, #map-canvas {
                height: 100%;
                margin: 5px;
                padding: 5px;
            }

            /*            #map-canvas {
                            height: 75%;
                            width: 75%;
                        }
            
                        label {
                            padding: 20px 10px;
                            display: inline-block;
                            font-size: 1.5em;
                        }
            
                        input {
                            font-size: 0.75em;
                            padding: 10px;
                        }*/


        </style>
        <link href="vistas/libs/imagina/css/bootstrap.min.css" />
        <link href="vistas/libs/imagina/css/bootstrap-reset.css" /> 
    </head>
    <body>

        <div class="row" >
            <div class="col-md-6">
                <label for="">Dirección: <input id="map-search" class="form-control" type="text" placeholder="Buscar dirección" size="500"></label>
                <input type="hidden" class="latitude">
                <input type="hidden" class="longitude">
                <input type="hidden" class="reg-input-city" placeholder="City">
            </div>
            <div class="input-group col-md-6   ">
                <label>&nbsp;&nbsp;</label>
                <button type="button" onclick="enviar()" value="enviar" name="env" id="env" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;margin-top: 20px;"><i class="fa fa-send-o"></i>&ensp;Enviar</button>
            </div>

        </div> 

        

        <div id="map-canvas"></div>
       

        <script >
            //        function isEmpty(value)
            //        {
            //            if ($.type(value) === 'undefined')
            //                return true;
            //            if ($.type(value) === 'null')
            //                return true;
            //            if ($.type(value) === 'string' && value.length <= 0)
            //                return true;
            //            if ($.type(value) === 'array' && value.length === 0)
            //                return true;
            //            if ($.type(value) === 'number' && isNaN(parseInt(value)))
            //                return true;
            //            if ($.type(value) === 'object' && Object.keys(value).length === 0)
            //                return true;
            //
            //            return false;
            //        }
            function getParameterByName(name) {
                name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                        results = regex.exec(location.search);
                return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
            }

            function enviar() {
                var
                        addressEl = document.querySelector('#map-search'),
                        latEl = document.querySelector('.latitude'),
                        longEl = document.querySelector('.longitude');
                window.opener.setRespuestaMaps(latEl.value, longEl.value, addressEl.value);
                setTimeout("self.close();", 700);
            }
            function initialize() {

                var mapOptions, map, marker, searchBox, city,
                        infoWindow = '',
                        addressEl = document.querySelector('#map-search'),
                        latEl = document.querySelector('.latitude'),
                        longEl = document.querySelector('.longitude'),
                        element = document.getElementById('map-canvas');
                city = document.querySelector('.reg-input-city'); 
                var latitud = getParameterByName('latitud');
                var logintud = getParameterByName('logintud');

                latitud = (!(latitud == null || latitud == '') ? latitud : -8.11167);
                logintud = (!(logintud == null || logintud == '') ? logintud : -79.0286);
                mapOptions = {
                    // How far the maps zooms in.
                    zoom: 14,
                    // Current Lat and Long position of the pin/
                    center: new google.maps.LatLng(latitud, logintud),
                    // center : {
                    // 	lat: -34.397,
                    // 	lng: 150.644
                    // },
                    disableDefaultUI: false, // Disables the controls like zoom control on the map if set to true
                    scrollWheel: true, // If set to false disables the scrolling on the map.
                    draggable: true, // If set to false , you cannot move the map around.
                    // mapTypeId: google.maps.MapTypeId.HYBRID, // If set to HYBRID its between sat and ROADMAP, Can be set to SATELLITE as well.
                    // maxZoom: 11, // Wont allow you to zoom more than this
                    // minZoom: 9  // Wont allow you to go more up.

                };

                /**
                 * Creates the map using google function google.maps.Map() by passing the id of canvas and
                 * mapOptions object that we just created above as its parameters.
                 *
                 */
                // Create an object map with the constructor function Map()
                map = new google.maps.Map(element, mapOptions); // Till this like of code it loads up the map.

                /**
                 * Creates the marker on the map
                 *
                 */
                marker = new google.maps.Marker({
                    position: mapOptions.center,
                    map: map,
                    // icon: 'http://pngimages.net/sites/default/files/google-maps-png-image-70164.png',
                    draggable: true
                });

                /**
                 * Creates a search box
                 */
                searchBox = new google.maps.places.SearchBox(addressEl);

                /**
                 * When the place is changed on search box, it takes the marker to the searched location.
                 */
                google.maps.event.addListener(searchBox, 'places_changed', function () {
                    var places = searchBox.getPlaces(),
                            bounds = new google.maps.LatLngBounds(),
                            i, place, lat, long, resultArray,
                            addresss = places[0].formatted_address;

                    for (i = 0; place = places[i]; i++) {
                        bounds.extend(place.geometry.location);
                        marker.setPosition(place.geometry.location);  // Set marker position new.
                    }

                    map.fitBounds(bounds);  // Fit to the bound
                    map.setZoom(15); // This function sets the zoom to 15, meaning zooms to level 15.
                    // console.log( map.getZoom() );

                    lat = marker.getPosition().lat();
                    long = marker.getPosition().lng();
                    latEl.value = lat;
                    longEl.value = long;

                    resultArray = places[0].address_components;

                    // Get the city and set the city input value to the one selected
                    for (var i = 0; i < resultArray.length; i++) {
                        if (resultArray[ i ].types[0] && 'administrative_area_level_2' === resultArray[ i ].types[0]) {
                            citi = resultArray[ i ].long_name;
                            city.value = citi;
                        }
                    }

                    // Closes the previous info window if it already exists
                    if (infoWindow) {
                        infoWindow.close();
                    }
                    /**
                     * Creates the info Window at the top of the marker
                     */
                    infoWindow = new google.maps.InfoWindow({
                        content: addresss
                    });

                    infoWindow.open(map, marker);
                });


                /**
                 * Finds the new position of the marker when the marker is dragged.
                 */
                google.maps.event.addListener(marker, "dragend", function (event) {
                    var lat, long, address, resultArray, citi;

                    console.log('i am dragged');
                    lat = marker.getPosition().lat();
                    long = marker.getPosition().lng();

                    var geocoder = new google.maps.Geocoder();
                    geocoder.geocode({latLng: marker.getPosition()}, function (result, status) {
                        if ('OK' === status) {  // This line can also be written like if ( status == google.maps.GeocoderStatus.OK ) {
                            address = result[0].formatted_address;
                            resultArray = result[0].address_components;

                            // Get the city and set the city input value to the one selected
                            for (var i = 0; i < resultArray.length; i++) {
                                if (resultArray[ i ].types[0] && 'administrative_area_level_2' === resultArray[ i ].types[0]) {
                                    citi = resultArray[ i ].long_name;
                                    console.log(citi);
                                    city.value = citi;
                                }
                            }
                            addressEl.value = address;
                            latEl.value = lat;
                            longEl.value = long;

                        } else {
                            console.log('Geocode was not successful for the following reason: ' + status);
                        }

                        // Closes the previous info window if it already exists
                        if (infoWindow) {
                            infoWindow.close();
                        }

                        /**
                         * Creates the info Window at the top of the marker
                         */
                        infoWindow = new google.maps.InfoWindow({
                            content: address
                        });

                        infoWindow.open(map, marker);
                    });
                });

                google.maps.event.trigger(marker, 'dragend', function (event) {
                    var lat, long, address, resultArray, citi;

                    console.log('i am dragged');
                    lat = marker.getPosition().lat();
                    long = marker.getPosition().lng();

                    var geocoder = new google.maps.Geocoder();
                    geocoder.geocode({latLng: marker.getPosition()}, function (result, status) {
                        if ('OK' === status) {  // This line can also be written like if ( status == google.maps.GeocoderStatus.OK ) {
                            address = result[0].formatted_address;
                            resultArray = result[0].address_components;

                            // Get the city and set the city input value to the one selected
                            for (var i = 0; i < resultArray.length; i++) {
                                if (resultArray[ i ].types[0] && 'administrative_area_level_2' === resultArray[ i ].types[0]) {
                                    citi = resultArray[ i ].long_name;
                                    console.log(citi);
                                    city.value = citi;
                                }
                            }
                            addressEl.value = address;
                            latEl.value = lat;
                            longEl.value = long;

                        } else {
                            console.log('Geocode was not successful for the following reason: ' + status);
                        }

                        // Closes the previous info window if it already exists
                        if (infoWindow) {
                            infoWindow.close();
                        }

                        /**
                         * Creates the info Window at the top of the marker
                         */
                        infoWindow = new google.maps.InfoWindow({
                            content: address
                        });

                        infoWindow.open(map, marker);
                    });
                });



            }</script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcZ9DvsKlyfZLJLC1K1_mRrGbYiRwIFPo&libraries=places&callback=initialize"></script>
    </body>
</html>
