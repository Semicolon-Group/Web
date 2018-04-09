var time;
var blinkTime = 0;
var globalVar = 0;
$(function () {
    scrollDownAll();
    global();
});
function closePopup(partId) {
    var popup = $("#" + partId);
    popup.hide();
    popup.css('height', '0');
    popup.css('padding', '0');
    stopUpdate();
    stopBlink();
    closeThread(partId);
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
        stopBlink();
        $("#" + partId + "-popup").css('height', '430px');
        setTimeout(function () {
            $("#" + partId + "-popup-mini").show();
        }, 500);
        readThread(partId);
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
           scrollDownAll();
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
}
function update(id) {
    time = setInterval(function () {
        var nbr = $("#" + id + "-list > li").length;
        var path = $("#update_thread_path").data('path');
        var DATA = {'partId':id, 'nbr':nbr};
        $.ajax({
            url: path,
            data: DATA,
            method: 'post',
            success: function (data) {
                data.forEach(function (text) {
                    appendOther(id, text);
                });
                scrollDownAll();
                if($("#" + id + "-popup").css('height') == '60px' && blinkTime == 0 && data.length > 0)
                    blink(id);
            }
        });
    }, 10000);
}
function stopUpdate() {
    clearInterval(time);
}
function blink(partId){
    blinkTime = setInterval(function () {
        var popup = $("#" + partId + "-popup");
        if(popup.css('background-color') == 'rgb(75, 163, 195)'){
            popup.css('background-color', 'white');
        }
        else{
            popup.css('background-color', '#4ba3c3');
        }
    }, 500);
}
function stopBlink(){
    clearInterval(blinkTime);
    $(".popup").css('background-color', 'white');
    blinkTime = 0;
}
function global() {
    var global = document.getElementById('global');
    if(!global)
        return;
    var partId = $("#global").data('id');
    globalVar = partId;
    $("#" + partId + "-popup").css('height', '60px').css('padding', '10px 20px 20px 20px');
    setTimeout(function () {
        update(partId);
    }, 500);
}
function closeThread(partId) {
    var path = $("#close_thread_path").data('path');
    var DATA =  {'partId':partId};
    $.ajax({
        url: path,
        method: 'post',
        data: DATA
    });
}