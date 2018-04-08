function popup(threadId) {
    $("#" + threadId + "-popup").css('height', '430px').css('padding', '10px 20px 20px 20px');
    setTimeout(function () {
        $(".popup-mini").show();
    }, 500);
    readThread(threadId);
}

function readThread(threadId) {
    var path = $("#read_thread_path").data('path');
    var DATA =  {'threadId':threadId};
    $.ajax({
        url: path,
        method: 'post',
        data: DATA,
        success: function () {
            var threadBody = $("#" + threadId + "-body");
            var thread = $("#" + threadId);
            if(threadBody.length != 0){
                threadBody.addClass('read-msg');
                thread.removeClass('new-msg');
            }
        }
    });
}