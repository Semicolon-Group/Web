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
    generateAnswer();
});

$('.photo').click(function () {
    initPhotoModal(this);
});

function initPhotoModal(node) {
    if($(node).attr('id') == 'cover' || $(node).attr('id') == 'profile'){
        $('#photo-display-action').hide();
    }else{
        $('#photo-display-action').show();
    }
    $('#img-canvas').attr('src', $(node).attr('src'));
    var id = $(node).data('id');
    document.getElementById('img-canvas').dataset.id = id;
}

$('#basebundle_photo_imageFile_file').change(function () {
    $("#photo-form").submit();
});

$("#photo-form").submit(function(event) {
    event.preventDefault();

    var data = new FormData();
    data.append('basebundle_photo[imageFile][file]', document.getElementById('basebundle_photo_imageFile_file').files[0]);

    $.ajax({
        type: 'POST',
        url: $(this).attr( 'action' ),
        data: data,
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,

        success: function(response) {
            updatePhotoView();
        },
        error: function (response, desc, err){
            if (response.responseJSON && response.responseJSON.message) {
                alert(response.responseJSON.message);
            }
            else{
                alert(desc);
            }
        }
    });
});

$(function () {
   $('#basebundle_photo_imageFile_file').attr('accept', '.png, .jpg, .jpeg');
});