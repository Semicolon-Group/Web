jQuery(document).ready(function() {
    $('.js-datepicker').datepicker();
});

$(function () {
    showFirstStep();
    activateAutoComplete();
});

function activateAutoComplete() {
    var input = document.getElementById('fos_user_registration_form_address_city');
    var options = {
        types: ['(cities)'],
        componentRestrictions: {country: 'tn'}
    };
    var autocomplete = new google.maps.places.Autocomplete(input, options);

    autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();
        $('#fos_user_registration_form_address_latitude').val(place.geometry.location.lat());
        $('#fos_user_registration_form_address_longitude').val(place.geometry.location.lng());
        var results = place.formatted_address.split(',');
        var country = results[results.length-1].trim();
        var city = place.formatted_address.replace(', '+country, '');
        $('#fos_user_registration_form_address_city').val(city);
        $('#fos_user_registration_form_address_country').val(country);
        $('#fos_user_registration_form_address_city').blur();
    });
}

$('#fos_user_registration_form_address_city').on('input', function () {
   $('#fos_user_registration_form_address_country').val('');
});

$('#fos_user_registration_form_address_city').blur(function () {
    if($('#fos_user_registration_form_address_city').val() != '' && $('#fos_user_registration_form_address_country').val() == ''){
        document.getElementById("fos_user_registration_form_address_city").setCustomValidity("Please provide a valid address or let us get your location!");
    }else{
        document.getElementById("fos_user_registration_form_address_city").setCustomValidity("");
    }
})

$('#fos_user_registration_form_email').change(function () {
    document.getElementById('fos_user_registration_form_email').setCustomValidity("");
});

$('#fos_user_registration_form_username').change(function () {
    document.getElementById('fos_user_registration_form_username').setCustomValidity("");
});

$('.next_step').click(function () {
    checkEmailUnicity();
    checkUsernameUnicity();
    showSecondPage();
});

$('.scd_step').click(function () {
    showThirdPage();
});

$('.bck_scd_step').click(function () {
    showFirstStep();
});

$('.thrd_step').click(function () {
    showFourthPage();
});

$('.bck_thrd_step').click(function () {
    showSecondPage();
});

$('.bck_frth_step').click(function () {
    showThirdPage();
});

$('#final_register').click(function () {
    checkValidity();
});

$('#register_form').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
        e.preventDefault();
        return false;
    }
});

$('#fos_user_registration_form_plainPassword_first').change(function () {
    checkPasswordMatch();
});

$('#fos_user_registration_form_plainPassword_second').change(function () {
    checkPasswordMatch();
});

$('#fos_user_registration_form_birthDate').change(function () {
    var selectedDate = new Date($('#fos_user_registration_form_birthDate').val());
    var today = new Date();
    var age = Math.floor((today-selectedDate) / (365.25 * 24 * 60 * 60 * 1000));
    if(age < 18){
        document.getElementById("fos_user_registration_form_birthDate").setCustomValidity("You must be at least 18 years old to register!");
    }else{
        document.getElementById("fos_user_registration_form_birthDate").setCustomValidity("");
    }
});

function checkPasswordMatch() {
    if($('#fos_user_registration_form_plainPassword_second').val() != $('#fos_user_registration_form_plainPassword_first').val()){
        document.getElementById('fos_user_registration_form_plainPassword_second').setCustomValidity("Passwords Don't Match");
    }else{
        document.getElementById('fos_user_registration_form_plainPassword_second').setCustomValidity('');
    }
}

function checkValidity() {
    $('.control').each(function (i, v) {
        if(!v.checkValidity()){
            if(v.classList.contains('first-control')){
                showFirstStep();
            }else if(v.classList.contains('second-control')){
                showSecondPage();
            }else if(v.classList.contains('third-control')){
                showThirdPage();
            }else if(v.classList.contains('fourth-control')){
                showFourthPage();
            }
            return false;
        }
    });
    return true;
}

$('.popup-with-zoom-anim').click(function () {
    showFirstStep();
});

function showFirstStep() {
    $('#first').show();
    $('#step_one').show();

    $('#second').hide();
    $('#step_two').hide();

    $('#third').hide();
    $('#step_three').hide();

    $('#fourth').hide();
    $('#step_four').hide();
}

function showSecondPage() {
    $('#first').hide();
    $('#step_one').hide();

    $('#second').show();
    $('#step_two').show();

    $('#third').hide();
    $('#step_three').hide();

    $('#fourth').hide();
    $('#step_four').hide();
}

function showThirdPage() {
    $('#first').hide();
    $('#step_one').hide();

    $('#second').hide();
    $('#step_two').hide();

    $('#third').show();
    $('#step_three').show();

    $('#fourth').hide();
    $('#step_four').hide();
}

function showFourthPage() {
    $('#first').hide();
    $('#step_one').hide();

    $('#second').hide();
    $('#step_two').hide();

    $('#third').hide();
    $('#step_three').hide();

    $('#fourth').show();
    $('#step_four').show();
}

$('#fos_user_registration_form_minAge').change(function () {
   updateMaxAge();
});

var maxAge;
$('#fos_user_registration_form_maxAge').focus(function () {
    maxAge = $(this).val();
});

$('#fos_user_registration_form_maxAge').change(function () {
   if($(this).val()<=$('#fos_user_registration_form_minAge').val()){
       $(this).val(maxAge);
   }
});

function updateMaxAge() {
    $('#fos_user_registration_form_maxAge').attr('min', parseInt($('#fos_user_registration_form_minAge').val()) + 1);
    if($('#fos_user_registration_form_maxAge').val() == '' || $('#fos_user_registration_form_maxAge').val()<=$('#fos_user_registration_form_minAge').val()) {
        $('#fos_user_registration_form_maxAge').val(parseInt($('#fos_user_registration_form_minAge').val()) + 1);
    }
}

$('#locate').click(function () {
    locate();
});

function locate() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
        alert("Geolocation is not supported by this browser, please provide your address");
    }
}

function showPosition(position) {
    $('#fos_user_registration_form_address_latitude').val(position.coords.latitude);
    $('#fos_user_registration_form_address_longitude').val(position.coords.longitude);
    geocodeLatLng(position.coords.latitude, position.coords.longitude);
}

function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            alert("Please provide your address");
            break;
        case error.POSITION_UNAVAILABLE:
            alert("Location unavailable, , please provide your address");
            break;
        case error.TIMEOUT:
            alert("Location request timed out, please provide your address");
            break;
        case error.UNKNOWN_ERROR:
            alert("An unknown error has occurred, please provide your address");
            break;
    }
}

function geocodeLatLng(lt, lg) {
    var geocoder = new google.maps.Geocoder;
    var latlng = {lat: lt, lng: lg};
    geocoder.geocode({'location': latlng}, function(results, status) {
        if (status === 'OK') {
            if (results[0]) {
                component = results[0].formatted_address.split(',');
                var country = component[component.length-1].trim();
                $('#fos_user_registration_form_address_country').val(country);
                $('#fos_user_registration_form_address_city').val(results[0].formatted_address.replace(', '+country,''));
            } else {
                window.alert('No results found');
            }
        } else {
            window.alert('Geocoder failed due to: ' + status);
        }
    });
}