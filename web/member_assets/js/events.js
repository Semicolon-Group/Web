$('.participate').click(function(e){
    e.preventDefault();
    var id = $(this).attr('data-id');
    var url = $(this).attr('data-url');
    var that = this;
    $.ajax({
        url: url,
        data: {id: id},
        dataType: 'json',
        type: 'POST',
        success: function(data){
            if($(that).hasClass('green')){
                $(that).removeClass('green');
                $(that).html('Participate <i class="fa fa-angle-double-right"></i>');
                var currentUrl = $(that).attr('data-url').split('/');
                currentUrl[currentUrl.length-1] = 'participate';
                $(that).attr('data-url', currentUrl.join('/'));
            }
            else{
                $(that).addClass('green');
                $(that).html('Participated <i class="fa fa-check"></i>');
                var currentUrl = $(that).attr('data-url').split('/');
                currentUrl[currentUrl.length-1] = 'unparticipate';
                $(that).attr('data-url', currentUrl.join('/'));
            }


        }
    })
})
$(document).ready(function () {


    $('#test').click(function () {

        var start = new Date(
            $('#basebundle_event_startDate_date_year').val()
            ,
            $('#basebundle_event_startDate_date_month').val()-1
            ,
            $('#basebundle_event_startDate_date_day').val());
        var end = new Date(
            $('#basebundle_event_endDate_year').val()
            ,
            $('#basebundle_event_endDate_month').val()-1
            ,
            $('#basebundle_event_endDate_day').val());

        var s = (end.getTime()-start.getTime());
        var diff = Math.ceil(s / (1000 * 3600 * 24));
        if(diff<0){
            alert("Invalid date");
        }

        else return null;
    });
});