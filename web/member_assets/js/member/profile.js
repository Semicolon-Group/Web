$('#about-edit').click(function () {
    $('#about').hide();
    $('#about-input').val($('#about').html());
    $('#about-input').show();
    $('#about-input').focus();
    $('#no-about-container').hide();
});

$('#no-about').click(function () {
    $('#about').hide();
    $('#about-input').val($('#about').html());
    $('#about-input').show();
    $('#about-input').focus();
    $('#no-about-container').hide();
});

$('#about-input').blur(function () {
    updateAbout();
});

$('.profile').click(function () {
   var path = $(this).data('path');
    window.location.href = path;
});

$('#answer-add').click(function () {
    alert('He');
});

$('.photo').click(function () {
    $('#img-canvas').attr('src', $(this).attr('src'));
});