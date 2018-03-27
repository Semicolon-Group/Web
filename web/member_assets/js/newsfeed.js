var popupId;
var timeout;
var emoPath;

$(function () {
    emoPath = $("#emoticon_path").data('path');

    $("#post_button").click(function () {
        var text = $("#post_space").html();
        if(text === '')
            return;

        var DATA = {'text':text};
        var path = $("#create_post_path").data('path');
        var editIcon = $("#edit_icon_path").data('path');
        var deleteIcon = $("#delete_icon_path").data('path');
        $.ajax({
            type:"POST",
            url:path,
            data:DATA,
            success:function(data){
                var id = data['id'];
                var pic = $("#post_writing_pic").attr('src');
                var username = $("#online_name").data('name');
                var post_html = "<div class='media'>" +
                    "<div class='update_box'>" +
                    "<button class='update_btn' data-toggle='modal' data-target='#edit_post_modal'><img class='update_img' src='" + editIcon + "' onclick='updateModalText(" + id + ")'></button>" +
                    "<button class='update_btn' data-toggle='modal' data-target='#delete_post_modal' onclick='showDeleteModal(" + id + ")'><img class='update_img' src='" + deleteIcon + "'></button>" +
                    "</div>" +
                    "<div class='media-left'>" +
                    "<img class='post_pic' src='" + pic + "' alt=''>" +
                    "</div>" +
                    "<div class='media-body'>" +
                    "<b>" + username + "</b>" +
                    "<p>Now</p><br>" +
                    "<p  id = '" + id + "'>" + text + "</p>" +
                    "</div>" +
                    "<hr>" +
                    "<div class='reaction-box'>" +
                    "<div class='react-action'>" +
                    "<button class='button'>" +
                    "<img class='button-icon' src='" + emoPath + "/comment.png'>" +
                    "<p>Comment</p>" +
                    "</button>" +
                    "</div>" +
                    "</div>" +
                    "</div>";
                $("#feed").prepend(post_html);
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
    $(".reaction-popup").mouseenter(function () {
        clearTimeout(timeout);
    });
    $(".reaction-popup").mouseleave(function () {
        clearTimeout(timeout);
        hide(popupId);
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

function react(id, type, reaction) {
    clearTimeout(timeout);
    $(".reaction-popup").hide();
    var button = $("#" + id + "-react");
    var old = button.html();
    if(reaction == 'None'){
        button.html(
            "<p>React</p>"
        );
    }else{
        button.html(
            "<img class='button-icon' src='" + emoPath + "/" + reaction + ".png'>\n" +
            "<p>" + reaction + "</p>"
        );
    }
    var DATA = {'id':id, 'type':type, 'reaction':reaction};
    var path = $("#react_path").data('path');
    $.ajax({
        type: 'POST',
        data: DATA,
        url: path,
        success: function (data) {
            var title = data['title'];
            if(title == 'None'){
                button.html(
                    "<p>React</p>"
                );
            }else{
                button.html(
                    "<img class='button-icon' src='" + emoPath + "/" + title + ".png'>\n" +
                    "<p>" + title + "</p>"
                );
            }
        },
        error: function () {
            button.html(old);
        }
    });
}

function show(id){
    clearTimeout(timeout);
    timeout = window.setTimeout(function(){
        $("#" + id + "-popup").show();
        popupId = id;
    }, 500);
}
function hide(id){
    clearTimeout(timeout);
    timeout = window.setTimeout(function(){
        $("#" + id + "-popup").hide();
    }, 1000);
}