$(function () {
    scrollDown();
    $(".popup-close").click(function () {
        var popup = $(".popup");
        popup.hide();
        popup.css('height', '0');
        popup.css('padding', '0');
        setTimeout(function () {
            popup.show();
        }, 500);
    });
    $(".popup-header, .popup-mini").on('click', function (e) {
        if(e.target != this)
            return;
        if($(".popup").css('height') == '430px'){
            $(".popup").css('height', '60px');
            setTimeout(function () {
                $(".popup-mini").hide();
            }, 500);
        }
        else{
            $(".popup").css('height', '430px');
            setTimeout(function () {
                $(".popup-mini").show();
            }, 500);
        }
    });
});
function scrollDown() {
    var messageList = document.querySelector('.message-list');
    if(messageList)
        messageList.scrollTop = messageList.scrollHeight - messageList.clientHeight;
}
function send(threadId) {
    var area = $("#" + threadId + "-popup-textarea");
    var text = area.val();
    if(text == '' || area.length == 0)
        return;
    var DATA = {'text':text, 'threadId':threadId};
    var path = $("#send_msg_path").data('path');
    $.ajax({
       url: path,
       method: 'post',
       data: DATA,
       success: function (data) {
           area.val('');
           $(".message-list").append(
               "<li class=\"popup-msg msg-yours\">" +
               "<div class=\"message-text text-yours\">" + text + "</div>" +
               "</li>"
           );
           var threadBody = $("#" + threadId + "-body");
           if(threadBody.length != 0)
               threadBody.html(text);
           scrollDown();
       }
    });
}