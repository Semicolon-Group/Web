$(document).ready(function () {
    $('#1').mouseenter(function () {
        $('#photo').show();
        $('#video').hide();


    });
    $('#2').click(function () {
        $('#photo').hide();
        $('#video').show();
    });
    $('#1').css('background', "{{ asset(member_assets/images/') }}{{ m.photoUrl}}' no-repeat scroll center center')"
);

});