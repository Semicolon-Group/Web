$(function () {
    $("#post_button").click(function () {
        var text = $("#post_space").html();
        if(text === '')
            return;

        var DATA = {'text':text};
        var path = $("#create_post_path").data('path');
        $.ajax({
            type:"POST",
            url:path,
            data:DATA,
            success:function(data){
                var pic = $("#post_writing_pic").attr('src');
                var username = $("#online_name").data('name');
                var post_html = "<div class='media'>" +
                    "<div class='media-left'>" +
                    "<img class='post_pic' src='" + pic + "' alt=''>" +
                    "</div>" +
                    "<div class='media-body'>" +
                    "<b>" + username + "</b>" +
                    "<p>Now</p><br>" +
                    "<p>" + text + "</p>" +
                    "</div>" +
                    "</div>";
                var current_html = $("#feed").html();
                $("#feed").html(post_html + current_html);
                $("#post_space").empty();
            }
        });
    });
    $("#save_changes").click(function () {
        var id = $("#selected_post").data('id');
        var currentText = $("#" + id).html();
        var newText = $("#modal_post").html();
        if(currentText === newText)
            return;
        $("#" + id).html(newText);

        var DATA = {'id':id, 'text':newText};
        var path = $("#edit_post_path").data('path');
        $.ajax({
            type: 'POST',
            data: DATA,
            url: path
        });
    });
    $("#delete").click(function () {
        var id = $("#selected_post").data('id');
        $("#" + id).parent().parent().remove();
        var DATA = {'id':id};
        var path = $("#delete_post_path").data('path');
        $.ajax({
            type: 'POST',
            data: DATA,
            url: path
        });
    });
});

function updateModalText(id){
    var text = $("#" + id).html();
    $("#modal_post").html(text);
    $("#selected_post").data('id',id);
}

function showDeleteModal(id) {
    $("#selected_post").data('id',id);
}