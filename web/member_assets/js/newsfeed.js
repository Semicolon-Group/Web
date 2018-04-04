var popupId;
var timeout;
var emoPath;
var onlineId;

$(function () {
    emoPath = $("#emoticon_path").data('path');
    onlineId = $("#online_id").data('id');

    $("#post_button").click(function () {
        var text = $("#post_space").val().replace(/\n/g, "<br>");
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
                var type = data['type'];
                var pic = $("#post_writing_pic").attr('src');
                var username = $("#online_name").data('name');
                var post_html = "<div class='media'>" +
                    "<div class='update_box'>" +
                    "<button onclick='updateModalText(\"" + id + "\")' class='button' data-toggle='modal' data-target='#edit_post_modal'><img class='update_img' src='" + editIcon + "'></button>" +
                    "<button class='button' data-toggle='modal' data-target='#delete_post_modal' onclick='showDeleteModal(" + id + ")'><img class='update_img' src='" + deleteIcon + "'></button>" +
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
                    "<button class=\"button\" onclick=\"toggleComments(" + id + ")\">" +
                    "<img class='button-icon' src='" + emoPath + "/comment.png'> " +
                    "<p>Comment</p>" +
                    "</button>" +
                    "</div>" +
                    "</div>" +
                    "<div class=\"comment-box\" id=\"" + id + "-comment-box\">\n" +
                    "<div id=\"" + id + "-comments\"></div>" +
                    "<div class=\"writing-comment\">\n" +
                    "<div class=\"comment-profile-pic\">\n" +
                    "<img class=\"comment_pic\" src='" + pic + "' alt=\"\">\n" +
                    "</div>\n" +
                    "<div class=\"comment-body comment comment-border comment-space\">\n" +
                    "<div contenteditable=\"true\" id=\"" + id + "-comment-space\" class=\"comment-space\" data-text=\"Leave a comment...\" onkeypress=\"addComment(event, " + id + ", " + type + ")\"></div>\n" +
                    "</div>\n" +
                    "</div>" +
                    "</div>" +
                    "</div>";
                $("#feed").prepend(post_html);
                $("#post_space").val('');
                $("#post_space").blur();
            }
        });
    });
    $("#save_changes").click(function () {
        var id = $("#selected_post").data('id');
        var element = document.getElementById(id + "-comment-content");
        var path = element ? $("#edit_comment_path").data('path') : $("#edit_post_path").data('path');
        element = element ? $("#" + id + "-comment-content") : $("#" + id);

        var currentText = element.html();
        var newText = $("#modal_post").val().replace(/\n/g, "<br>");
        if(currentText === newText)
            return;
        element.html(newText);

        var DATA = {'id':id, 'text':newText};
        $.ajax({
            type: 'POST',
            data: DATA,
            url: path
        });
    });
    $("#delete").click(function () {
        var id = $("#selected_post").data('id');
        var element = document.getElementById(id + "-comment-content");
        var DATA = {'id':id};
        var path = element ? $("#delete_comment_path").data('path') : $("#delete_post_path").data('path');
        element = element ? $("#" + id + "-comment-content") : $("#" + id);
        element.parent().parent().remove();
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
    var element = $("#" + id + "-comment-content");
    element = element.length != 0 ? element : $("#" + id);
    var text = element.html().replace(/<br>/g, "\n");
    $("#modal_post").val(text);
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
function toggleComments(id) {
    $("#" + id + "-comment-box").toggle();
}
function addComment(event, id, type) {
    if(event.keyCode == 13 && !event.shiftKey){

        var postId = id;
        var photoId = 0;
        if(type == $("#picture_type").data('type')){
            postId = 0;
            photoId = id;
        }
        var pic = $("#post_writing_pic").attr('src');
        var commentSpace = $("#" + id + "-comment-space");
        var content = commentSpace.val().replace(/\n/g, "<br>");
        var comments = $("#" + id + "-comments");

        var DATA = {'postId':postId, 'photoId':photoId, 'content':content};
        var path = $("#add_comment_path").data('path');
        $.ajax({
            type: 'POST',
            data: DATA,
            url: path,
            success: function (data) {
                comments.append(
                    "<div class=\"comment-sub-box\">\n" +
                    "<div class=\"comment-profile-pic\">\n" +
                    "<img class=\"comment_pic\" src='" + pic + "'>\n" +
                    "</div>\n" +
                    "<div class=\"comment-body comment\">\n" +
                    "<p id=\"" + data['id'] + "-comment-content\">" + content + "</p>\n" +
                    "</div>\n" +
                    "<div class=\"update-comment-box\">\n" +
                    "<span class=\"button far fa-edit\" onclick=\"updateModalText(" + data['id'] + ")\" data-toggle=\"modal\" data-target=\"#edit_post_modal\"></span><span class=\"button far fa-trash-alt\" onclick=\"showDeleteModal(" + data['id'] + ")\" data-toggle=\"modal\" data-target=\"#delete_post_modal\"></span>\n" +
                    "</div>" +
                    "</div>"
                );
                var newComment = $("#" + id + "-comments > div").last();
                newComment.css('background-color', '#e0dede');
                window.setTimeout(function(){
                    newComment.css('background-color', 'transparent');
                }, 2000);
                commentSpace.val('');
                commentSpace.blur();
            }
        });
        event.preventDefault();
    }else{
        expandArea(id, 100, 26);
    }
}

function expandArea(id, max, def){
    var area = $("#" + id + "-comment-space");
    var count = (area.val().match(/\n/g) || []).length;
    var height = def + count * 20;
    console.log(count);
    if(height < max) {
        area.css('height', height + 'px');
        area = document.getElementById(id + "-comment-space");
        area.scrollTop = area.scrollHeight;
    }
}