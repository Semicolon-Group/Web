var slider = document.getElementById("range");
var output = document.getElementById("output");
var open = false;
var type = 'cafe';
var map;
var rank = 'name';
var currentLat;
var currentLng;

output.innerHTML = slider.value; // Display the default slider value

// Update the current slider value (each time you drag the slider handle)
slider.oninput = function() {
    output.innerHTML = this.value;
};

$(function () {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
        alert("Geolocation is not supported by this browser, your provided address will be used instead");
        currentLat = parseFloat($('#lat').html());
        currentLng = parseFloat($('#lng').html());
        initialize();
    }
});

function showPosition(position) {
    currentLat = position.coords.latitude;
    currentLng = position.coords.longitude;
    initialize();
}

function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            alert("Your provided address will be used instead");
            break;
        case error.POSITION_UNAVAILABLE:
            alert("Location unavailable, your provided address will be used instead");
            break;
        case error.TIMEOUT:
            alert("Location request timed out, your provided address will be used instead");
            break;
        case error.UNKNOWN_ERROR:
            alert("An unknown error has occurred, your provided address will be used instead");
            break;
    }
    currentLat = parseFloat($('#lat').html());
    currentLng = parseFloat($('#lng').html());
    initialize();
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

$('#sort').change(function () {
    rank = $(this).val();
    initialize();
});

$('#cafe').click(function () {
    if(type!='cafe'){
        resetTabSelection();
        resetFilter();
        $(this).toggleClass("active");
        type = 'cafe';
        initialize();
    }
});

$('#restaurant').click(function () {
    if(type!='restaurant'){
        resetTabSelection();
        resetFilter();
        $(this).toggleClass("active");
        type = 'restaurant';
        initialize();
    }
});

$('#park').click(function () {
    if(type!='park'){
        resetTabSelection();
        resetFilter();
        $(this).toggleClass("active");
        type = 'park';
        initialize();
    }
});

function resetFilter() {
    $('#all').prop('checked', true);
    open = false;
    $('#search-box').val('');
    $('#range').val(1000);
    output.innerHTML = $('#range').val();
}

function resetTabSelection() {
    $('#cafe').removeClass('active');
    $('#restaurant').removeClass('active');
    $('#park').removeClass('active');
}

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

    drawMapDirections(currentLat, currentLng, lat, lng);
    fillPlaceDetails(placeId);
    map.setZoom(20);
}

function initialize() {
    console.log(currentLat+' '+currentLng);
    $('#recommandations').html('<div class="loader-container"><div class="loader"></div></div>');
    var pyrmont = new google.maps.LatLng(currentLat,currentLng);
    var request;
    if(open === 'true'){
        request = {
            location: pyrmont,
            radius: $('#range').val(),
            name: $('#search-box').val(),
            openNow: true,
            types: [type]
        };
    }else{
        request = {
            location: pyrmont,
            radius: $('#range').val(),
            name: $('#search-box').val(),
            types: [type]
        };
    }

    var service = new google.maps.places.PlacesService($('#nearBYSearch-container').get(0));
    service.nearbySearch(request, callback);
}

function callback(results, status) {
    if (status == google.maps.places.PlacesServiceStatus.OK) {
        if(results.length == 0){
            $('#recommandations').html("<h1 class='pressed'>No places found :(</h1>");
            return;
        }
        $('#recommandations').html("");
        results.forEach(function (place) {
           if(place.rating == null){
               place.rating = 0;
           }
        });
        if(rank === 'name'){
            results.sort(function (a,b) {
                if (a.name < b.name)
                    return -1;
                if (a.name > b.name)
                    return 1;
                return 0;
            });
        }else if(rank === 'rating'){
            results.sort(function (a, b) {
                return b.rating - a.rating;
            });
        }

        for(i=0; i<results.length; i++){
            place = results[i];
            photoPath = $('#placeholder-img').html();
            if(place.photos !== undefined && place.photos.length != 0){
                photoPath = place.photos[0].getUrl({ 'maxWidth': 800, 'maxHeight': 800 });
            }
            var open = "not provided";
            var color = "black";
            if(place.opening_hours != null && place.opening_hours.open_now != null){
                open = place.opening_hours.open_now?"Open":"Closed";
                color = place.opening_hours.open_now?"green":"red";
            }
            $('#recommandations').append("" +
                "<div class=\"row card-container margins\" style=\"width: 100%;\">\n" +
                "    <div class=\"col-sm-offset-1 col-md-10\">\n" +
                "       <div id='place-"+i+"' class=\"card horizontal clickable\" onclick='showMap(this);' data-toggle=\"modal\" data-target=\"#map\"  data-place-id="+place.place_id+" data-place-lat="+place.geometry.location.lat()+" data-place-lng="+place.geometry.location.lng()+">\n" +
                "           <div class=\"card-image\" style=\"width: 25%; min-width: 120px; overflow: hidden; \">\n" +
                "               <img style='min-width: 100%; min-height: 100%;' src='"+photoPath+"'>\n" +
                "           </div>\n" +
                "           <div class=\"card-stacked\">\n" +
                "               <div class=\"card-content\">\n" +
                "                   <span class=\"card-title\">"+place.name+"<span style='float: right;'> "+place.rating+"   <span class='glyphicon glyphicon-star'></span> </span><br/><sub style='font-size: medium;'><span>"+place.vicinity+"</span> - <b><span style='color: "+color+";'>"+open+"</span></b></sub></span><br/>\n" +
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
    }else{
        $('#recommandations').html("<h1 class='pressed'>No places found :(</h1>");
    }
}

function getDistance(i) {
    var distanceService = new google.maps.DistanceMatrixService();
    var selector = '#place-'+i;
    var destLat = $(selector).data('placeLat');
    var destLng = $(selector).data('placeLat');
    distanceService.getDistanceMatrix({
            origins: [{lat: currentLat, lng: currentLat}],
            destinations: [{lat: destLat, lng: destLng}],
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
            if(place.rating===undefined)place.rating=0;
            const starPercentage = (place.rating / starTotal) * 100;
            const starPercentageRounded = (Math.round(starPercentage / 10) * 10);
            photoPath = $('#placeholder-img').html();
            if(place.photos !== undefined && place.photos.length != 0){
                photoPath = place.photos[0].getUrl({ 'maxWidth': 800, 'maxHeight': 800 });
            }
            var open = "not provided";
            var color = "black";
            if(place.opening_hours != null && place.opening_hours.open_now != null){
                open = place.opening_hours.open_now?"Open":"Closed";
                color = place.opening_hours.open_now?"green":"red";
            }

            $('#start').css('width', starPercentageRounded+"%");
            $('#place-name').html(place.name);
            $('#place-img').attr('src', photoPath);
            $('#open').html("<b><span style='color: "+color+";'>"+open+"</span></b>");
            $('#place-address').html(place.adr_address);
            if(place.formatted_phone_number !== undefined)
                $('#phone-number').html("<b>Phone number: </b>"+place.formatted_phone_number);
            if(place.website !== undefined)
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