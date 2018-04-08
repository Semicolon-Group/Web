var time;
var updatingId = 0;
$(function () {
    scrollDownAll();
});
function closePopup(partId) {
    var popup = $("#" + partId);
    popup.hide();
    popup.css('height', '0');
    popup.css('padding', '0');
    if(partId === updatingId) stopUpdate();
    setTimeout(function () {
        popup.show();
    }, 500);
}
function miniPopup(event, partId) {
    var popup = document.getElementById(partId + "-popup-header");
    var mini = document.getElementById(partId + "-popup-mini");
    if(event.target != popup && event.target != mini)
        return;
    if($("#" + partId + "-popup").css('height') == '430px'){
        $("#" + partId + "-popup").css('height', '60px');
        setTimeout(function () {
            $("#" + partId + "-popup-mini").hide();
        }, 500);
    }
    else{
        $("#" + partId + "-popup").css('height', '430px');
        setTimeout(function () {
            $("#" + partId + "-popup-mini").show();
        }, 500);
    }
}
function scrollDown(partId) {
    var id = "#" + partId + "-list";
    var messageList = document.getElementById(id);
    if(messageList){
        messageList.scrollTop = messageList.scrollHeight - messageList.clientHeight;
    }
}
function scrollDownAll() {
    var list = document.getElementsByClassName('message-list');
    for(var i=0; i < list.length; i++){
        list[i].scrollTop = list[i].scrollHeight - list[i].clientHeight;
    }
}
function send(partId) {
    var area = $("#" + partId + "-popup-textarea");
    var text = area.val();
    if(text == '' || area.length == 0)
        return;
    var DATA = {'text':text, 'partId':partId};
    var path = $("#send_msg_path").data('path');
    $.ajax({
       url: path,
       method: 'post',
       data: DATA,
       success: function (data) {
           area.val('');
           append(partId, text);
           var threadBody = $("#" + partId + "-body");
           if(threadBody.length != 0)
               threadBody.html(text);
           scrollDown(partId);
           readThread(partId);
       }
    });
}
function readThread(partId) {
    var path = $("#read_thread_path").data('path');
    var DATA =  {'partId':partId};
    $.ajax({
        url: path,
        method: 'post',
        data: DATA,
        success: function () {
            var threadBody = $("#" + partId + "-body");
            var thread = $("#" + partId);
            if(threadBody.length != 0){
                threadBody.addClass('read-msg');
                thread.removeClass('new-msg');
            }
        }
    });
}
function appendOther(partId, text) {
    $("#" + partId + "-list").append(
        "<li class=\"popup-msg msg-theirs\">" +
        "<div class=\"message-text text-theirs\">" + text + "</div>" +
        "</li>"
    );
}
function append(partId, text) {
    $("#" + partId + "-list").append(
        "<li class=\"popup-msg msg-yours\">" +
        "<div class=\"message-text text-yours\">" + text + "</div>" +
        "</li>"
    );
}/*
function refresh() {
    var list = document.getElementsByClassName('popup');
    for(var i=0; i < list.length; i++){
        console.log(list[i]);
        if(list[i].height > 0)
            update(list[i].id);
    }
}*/
function update(id) {
    updatingId = id;
    //id = id.substr(0, id.length - 6);
    var nbr = $("#" + id + "-list > li").length;
    var path = $("#update_thread_path").data('path');
    var DATA = {'partId':id, 'nbr':nbr};
    time = setTimeout(function () {
        $.ajax({
            url: path,
            data: DATA,
            method: 'post',
            success: function (data) {
                data.forEach(function (text) {
                    appendOther(id, text);
                });
                if(updatingId != 0) update(id);
            }
        });
    }, 5000);
}
function stopUpdate() {
    clearTimeout(time);
    updatingId = 0;
}