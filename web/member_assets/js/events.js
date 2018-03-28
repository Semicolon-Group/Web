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