var slider = document.getElementById("range");
var output = document.getElementById("output");
output.innerHTML = slider.value; // Display the default slider value

// Update the current slider value (each time you drag the slider handle)
slider.oninput = function() {
    output.innerHTML = this.value;
}

$('#range').change(function () {
    initialize();
});
$('#search-box').change(function () {
    initialize();
});
$('input:radio[name=show]').change(function () {
    initialize();
});

var open = false;
$('input:radio[name="openToggle"]').change(
    function(){
        open = $(this).val();
        initialize();
    });

function showMap(node) {
    var lat = $(node).data('placeLat');
    var lng = $(node).data('placeLng');
    var placeId = $(node).data('placeId');
    $('#map_canvas').html('');

    drawMapDirections(-33.8665433, 151.1956316, lat, lng);
    fillPlaceDetails(placeId);
    map.setZoom(20);
}

$(initialize());

function initialize() {
    var pyrmont = new google.maps.LatLng(-33.8665433,151.1956316);
    var request;
    if(open === 'true'){
        request = {
            location: pyrmont,
            radius: $('#range').val(),
            name: $('#search-box').val(),
            openNow: true,
            types: ['store']
        };
    }else{
        request = {
            location: pyrmont,
            radius: $('#range').val(),
            name: $('#search-box').val(),
            types: ['store']
        };
    }

    var service = new google.maps.places.PlacesService($('#nearBYSearch-container').get(0));
    service.nearbySearch(request, callback);
}

function callback(results, status) {
    if (status == google.maps.places.PlacesServiceStatus.OK) {
        $('#recommandations').html("");
        for (var i = 0; i < results.length; i++) {
            var place = results[i];
            $('#recommandations').append("" +
                "<div class=\"row card-container margins\" style=\"width: 100%;\">\n" +
                "    <div class=\"col-sm-offset-1 col-md-10\">\n" +
                "       <div class=\"card horizontal clickable\" onclick='showMap(this);' data-toggle=\"modal\" data-target=\"#map\"  data-place-id="+place.place_id+" data-place-lat="+place.geometry.location.lat()+" data-place-lng="+place.geometry.location.lng()+">\n" +
                "           <div class=\"card-image\" style=\"width: 25%; min-width: 120px; overflow: hidden; \">\n" +
                "               <img style='min-width: 100%; min-height: 100%;' src="+place.photos[0].getUrl({ 'maxWidth': 800, 'maxHeight': 800 })+">\n" +
                "           </div>\n" +
                "           <div class=\"card-stacked\">\n" +
                "               <div class=\"card-content\">\n" +
                "                   <span class=\"card-title\">"+place.name+"<span style='float: right;'> "+place.rating+"   <span class='glyphicon glyphicon-star'></span> </span><br/><sub style='font-size: medium;'><span>"+place.vicinity+"</span> - <b><span style='color: "+((place.opening_hours.open_now == true)?"green":"red")+";'>"+((place.opening_hours.open_now == true)?"Open":"Closed")+"</span></b></sub></span><br/>\n" +
                "                   " +
                "               </div>\n" +
                "               <div class=\"card-action\">\n" +
                "                   <span id="+i+"></span>\n" +
                "               </div>\n" +
                "           </div>\n" +
                "       </div>\n" +
                "    </div>\n" +
                "</div>");
            getDistance(i);
        }
    }
}


function getDistance(i) {
    var distanceService = new google.maps.DistanceMatrixService();
    distanceService.getDistanceMatrix({
            origins: [{lat: 55.93, lng: -3.118}],
            destinations: [{lat: 50.087, lng: 14.421}],
            travelMode: google.maps.TravelMode.DRIVING,
            unitSystem: google.maps.UnitSystem.METRIC,
            durationInTraffic: true,
            avoidHighways: false,
            avoidTolls: false
        },
        function (response, status) {
            if (status !== google.maps.DistanceMatrixStatus.OK) {
                console.log('Error:', status);
            } else {
                var distance = response.rows[0].elements[0].distance.text;
                var duration = response.rows[0].elements[0].duration.text;
                $('#'+i).html(duration+" ("+distance+") by car");
            }
        }
    );
}

var map;
var marker;

function drawMapDirections(originLat, originlng, destinationLat, destinationLng) {
    var defLatLng = new google.maps.LatLng(originLat, originlng);
    var mapOptions = {
        center: defLatLng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDefaultUI: true,
        panControl: false
    };
    map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
    showDirection(originLat, originlng, destinationLat, destinationLng);
}

function showDirection(orgLat, orgLng, distLat, distLng){
    var directionsDisplay = new google.maps.DirectionsRenderer({
        map: map
    });

    var request = {destination: {lat: distLat, lng: distLng}, origin: {lat: orgLat, lng: orgLng}, travelMode: 'DRIVING'};

    var directionsService = new google.maps.DirectionsService();
    directionsService.route(request, function(response, status){
        if(status == 'OK'){
            directionsDisplay.setDirections(response);
        }
    });
}

function fillPlaceDetails(placeId) {
    var service = new google.maps.places.PlacesService(map);

    service.getDetails({
        placeId: placeId
    }, function(place, status) {
        if (status === google.maps.places.PlacesServiceStatus.OK) {
            const starTotal = 5;

            const starPercentage = (place.rating / starTotal) * 100;
            const starPercentageRounded = (Math.round(starPercentage / 10) * 10);
            $('#start').css('width', starPercentageRounded+"%");
            $('#place-name').html(place.name);
            $('#place-img').attr('src', place.photos[0].getUrl({ 'maxWidth': 800, 'maxHeight': 800 }));
            $('#open').html("<b><span style='color: "+((place.opening_hours.open_now == true)?"green":"red")+";'>"+((place.opening_hours.open_now == true)?"Open":"Closed")+"</span></b>");
            $('#place-address').html(place.adr_address);
            if(place.formatted_phone_number != null)
                $('#phone-number').html("<b>Phone number: </b>"+place.formatted_phone_number);
            if(place.website != null)
                $('#website').html('<a target="_blank" href="'+place.website+'">WebSite</a>');
            $('#reviews').html('');
            console.log(place.reviews);
            for(i=0; i<place.reviews.length; i++){
                var review = place.reviews[i];
                $('#reviews').append("" +
                    "<hr><span><b>"+review.author_name+"</b> - "+review.relative_time_description+"<span style='float: right;'>"+review.rating+" <span class='glyphicon glyphicon-star'></span></span></span><br/>" +
                    "<p style='text-align: justify; text-indent: 5%; margin-left: 5%;'>"+review.text+"</p>" +
                "");
            }
        }
    });
}