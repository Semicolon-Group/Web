function popup(partId) {
    closeAllOthers(partId);
    $("#" + partId + "-popup").css('height', '430px').css('padding', '10px 20px 20px 20px');
    readThread(partId);
    setTimeout(function () {
        $("#" + partId + "-popup-mini").show();
        update(partId);
    }, 500);
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

function closeAllOthers(partId){
    var list = document.getElementsByClassName('popup');
    for(var i=0; i < list.length; i++){
        if(list[i].id !== (partId + "-popup"))
            closePopup(list[i].id);
    }
}