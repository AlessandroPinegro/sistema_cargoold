<html>
    <head>
        <title>Earthquake Markers</title>
        <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

        <link rel="stylesheet" type="text/css" href="./style.css" />
        <script type="module" src="./index.js"></script>
    </head>
    <body>
        <div id="map"></div>

        <!-- 
         The `defer` attribute causes the callback to execute after the full HTML
         document has been parsed. For non-blocking uses, avoiding race conditions,
         and consistent behavior across browsers, consider loading using Promises
         with https://www.npmjs.com/package/@googlemaps/js-api-loader.
        -->
       
        <script>
            let map;

            function initMap() {
                map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 2,
                    center: new google.maps.LatLng(2.8, -187.3),
                    mapTypeId: "terrain",
                });

                // Create a <script> tag and set the USGS URL as the source.
                const script = document.createElement("script");

                // This example uses a local copy of the GeoJSON stored at
                // http://earthquake.usgs.gov/earthquakes/feed/v1.0/summary/2.5_week.geojsonp
                script.src =
                        "https://developers.google.com/maps/documentation/javascript/examples/json/earthquake_GeoJSONP.js";
                document.getElementsByTagName("head")[0].appendChild(script);
            }

    // Loop through the results array and place a marker for each
    // set of coordinates.
            const eqfeed_callback = function (results) {
                for (let i = 0; i < results.features.length; i++) {
                    const coords = results.features[i].geometry.coordinates;
                    const latLng = new google.maps.LatLng(coords[1], coords[0]);

                    new google.maps.Marker({
                        position: latLng,
                        map: map,
                    });
                }
            };

            window.initMap = initMap;
            window.eqfeed_callback = eqfeed_callback;

        </script>
         <script
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBCKiIqCdZGrVxx06LSbe7uG3zXOq1Cz5k&callback=initMap&v=weekly"
            defer
        ></script>
    </body>
</html>