$(function(){
    $("#announce_save").click(function(){
       var path = $("#announce_path").data('path');
       var text = $("#announce_modal_post").val();
       var DATA = {'text':text};
       $.ajax({
           url: path,
           data: DATA,
           type: 'post',
           success: function(){
               alert('Your announcement has been made!');
           }
       })
    });
});