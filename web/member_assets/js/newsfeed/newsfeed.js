var popupId;
var timeout;
var emoPath;
var onlineId;
var selectedPost;

$(function () {
    emoPath = $("#emoticon_path").data('path');
    onlineId = $("#online_id").data('id');

    $("#post_button").click(function () {
        var text = $("#post_space").val().replace(/\n/g, "<br>");
        if(text === '')
            return;

        var DATA = {'text':text};
        var path = $("#create_post_path").data('path');
        $.ajax({
            type:"POST",
            url:path,
            data:DATA,
            success:function(data){
                $("#feed").prepend(data[0]);
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
        var comment = !!element;
        var path = element ? $("#delete_comment_path").data('path') : $("#delete_post_path").data('path');
        element = element ? $("#" + id + "-comment-content") : $("#" + id);
        element.parent().parent().remove();
        $.ajax({
            type: 'POST',
            data: DATA,
            url: path,
            success: function (data) {
                if(comment){
                    var stat = $("#" + selectedPost + "-nbr-comment");
                    var nbr = stat.data('nbr');
                    stat.html(nbr - 1);
                }
            }
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

function showDeleteModal(id, postId) {
    $("#selected_post").data('id',id);
    selectedPost = postId;
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
    var stat = $("#" + id + "-nbr-reaction");
    var nbr = stat.data('nbr');
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
                if(nbr != -1) stat.html(nbr);
            }else{
                button.html(
                    "<img class='button-icon' src='" + emoPath + "/" + title + ".png'>\n" +
                    "<p>" + title + "</p>"
                );
                if(nbr != -1) stat.html("You and " + nbr + " others");
                else stat.html("You");
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
                comments.append(data[0]);
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
    if(height < max) {
        area.css('height', height + 'px');
        area = document.getElementById(id + "-comment-space");
        area.scrollTop = area.scrollHeight;
    }
}
function viewPhoto(id){
    var src = $("#" + id).attr('src');
    $("#photo_modal").html("<img src='" + src + "' style='width:100%;height:auto;'>");
}