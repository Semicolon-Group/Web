var minAge = 18;
var maxAge = 100;
var minHeight = 150;
var maxHeight = 250;
var distance = "";
var login = "";
var smokes = -1;
var drinks = -1;
var body = [];
var religion = [];
var statuses = [];

$(function(){
   $('#search-icon').click(function(){
       toggleFilter();
       if($(".filter").css('width') != '0px'){
           updateAgeHeight();
           updateSmokeDrink();
           updateDistanceLogin();
           updateBodyReligionStatus();
           search();
       }
   });
    $('#reset-icon').click(function(){
        toggleFilter();
        resetVars();
        resetWidgets();
        search();
    });
    $('#close-icon').click(function(){
        toggleFilter();
    });
   $("#age-slider").slider({
       range: true,
       min: 18,
       max: 100,
       values: [ 18, 100 ],
       slide: function( event, ui ) {
           $( "#age-amount" ).val( ui.values[ 0 ] + " - " + ui.values[ 1 ] );
       }
   });
    $("#height-slider").slider({
        range: true,
        min: 150,
        max: 250,
        values: [ 150, 250 ],
        slide: function( event, ui ) {
            $( "#height-amount" ).val( ui.values[ 0 ] + "cm - " + ui.values[ 1 ] + 'cm' );
        }
    });
});

function toggleFilter(){
    if($(".filter").css('width') == '0px'){
        $(".filter").css('width', '23%');
        $(".grid").css('margin-right', '23%');
        $(".search").css('right', '24%');
        $(".filler").css('width', '23%');
        $(".active_members").css('padding-left', '0');
        $(".criteria, #reset-icon, #close-icon").delay(350).animate({opacity:1}, 300);
        $("#reset-label, #close-label").delay(350).animate({opacity:0.3}, 300);
    }else{
        $(".filter").css('width', '0');
        $(".grid").css('margin-right', '0');
        $(".search").css('right', '2%');
        $(".filler").css('width', '0');
        $(".active_members").css('padding-left', '15%');
        $(".criteria").animate({opacity:0}, 100);
        $(".reset").delay(300).animate({opacity:0}, 300);
    }
}

function search() {
    var DATA = {'minAge':minAge, 'maxAge':maxAge, 'minHeight':minHeight, 'maxHeight':maxHeight, 'distance':distance, 'login':login,
                'smokes':smokes, 'drinks':drinks, 'body':body, 'religion':religion, 'status':statuses};
    var path = $("#filter-path").data("path");
    $.ajax({
        url: path,
        data: DATA,
        type: 'POST',
        success: function (data) {
            var grid = $(".active_members");
            grid.html(data[0]);
            //grid.empty();
            /*data.forEach(function (card) {
                grid.append("<div class=\"matchcard\" onclick=\"\">\n" +
                    "<div class=\"photo-div\">\n" +
                    "<img class=\"profile-photo\" src='" + card['photo'] + "'>\n" +
                    "</div>\n" +
                    "<div class=\"matchcard-text\">\n" +
                    "<div class=\"profile-info\">\n" +
                    "<div class=\"username\">\n" +
                    card['user']['firstname'] + ' ' + card['user']['lastname'] +
                    "</div>\n" +
                    "<div class=\"user-info\">\n" +
                    "<span class=\"age\">" + card['age'] + "</span>\n" +
                    "<span class=\"dot fas fa-circle\"></span>\n" +
                    "<span class=\"location\">" + card['user']['address']['city'] + ", " + card['user']['address']['city'] + "</span>\n" +
                    "</div>\n" +
                    "</div>\n" +
                    "<div class=\"percentages\">\n" +
                    "<div class=\"percentage-wrapper\">\n" +
                    "<span class=\"percentage\">" + card['match'] + "%</span>\n" +
                    "<span class=\"percentage-label\">Match</span>\n" +
                    "</div><div class=\"percentage-wrapper enemy\">\n" +
                    "<span class=\"percentage\">" + card['enemy'] + "%</span>\n" +
                    "<span class=\"percentage-label\">Enemy</span>\n" +
                    "</div>\n" +
                    "</div>\n" +
                    "</div>\n" +
                    "</div>")
            });*/
        }
    });
}

function updateAgeHeight(){
    minAge = $("#age-slider").slider("values", 0);
    maxAge = $("#age-slider").slider("values", 1);
    minHeight = $("#height-slider").slider("values", 0);
    maxHeight = $("#height-slider").slider("values", 1);
}

function updateSmokeDrink() {
    var selector;
    selector = document.querySelector('input[name="smokes"]:checked');
    smokes = selector ? selector.value : -1;
    selector = document.querySelector('input[name="drinks"]:checked');
    drinks = selector ? selector.value : -1;
}

function updateDistanceLogin() {
    distance = $("#distance").val();
    login = $("#login").val();
}

function updateBodyReligionStatus(){
    /* Body types */
    var checkboxes = document.getElementsByName('body');
    body = [];
    for (var i=0, n=checkboxes.length;i<n;i++)
    {
        if (checkboxes[i].checked)
        {
            body.push(checkboxes[i].value);
        }
    }
    /* Religions */
    checkboxes = document.getElementsByName('religion');
    religion = [];
    for (var i=0, n=checkboxes.length;i<n;i++)
    {
        if (checkboxes[i].checked)
        {
            religion.push(checkboxes[i].value);
        }
    }
    /* Statuses */
    checkboxes = document.getElementsByName('status');
    statuses = [];
    for (var i=0, n=checkboxes.length;i<n;i++)
    {
        if (checkboxes[i].checked)
        {
            statuses.push(checkboxes[i].value);
        }
    }
}

function resetVars(){
    minAge = 18;
    maxAge = 100;
    minHeight = 150;
    maxHeight = 250;
    distance = "";
    login = "";
    smokes = -1;
    drinks = -1;
    body = [];
    religion = [];
    statuses = [];
}

function resetWidgets(){

}
function enter(id) {
    var photo = $("#" + id + "-photo");
    var perc = $("." + id + "-perc");
    var star = $("#" + id + "-star");
    photo.css('transform', 'scale(1.05)');
    perc.css('opacity', '0');
    star.css('cssText', 'display: inline !important');
}
function leave(id) {
    var photo = $("#" + id + "-photo");
    var perc = $("." + id + "-perc");
    var star = $("#" + id + "-star");
    photo.css('transform', 'scale(1)');
    perc.css('opacity', '1');
    star.css('cssText', 'display: none !important');
}
function enterStar(id) {
    var star = $("#" + id);
    if(star.data('liked') == true)
        star.attr('class', 'far fa-star like-star');
    else
        star.attr('class', 'fas fa-star like-star');
}
function leaveStar(id) {
    var star = $("#" + id);
    if(star.data('liked') == true)
        star.attr('class', 'fas fa-star like-star');
    else
        star.attr('class', 'far fa-star like-star');
}
function like(id){
    var path = $("#like-path").data('path');
    var DATA = {'id':id};
    $.ajax({
       url: path,
       type: 'post',
       data: DATA,
       success: function () {
           var star = $("#" + id + "-star");
           star.attr('class', 'fas fa-star like-star');
           star.css('font-size', '25px');
           setTimeout(function () {
               star.css('font-size', '50px');
           }, 200);
           star.data('liked', true);
       },
        error: function () {

        }
    });
}
function dislike(id){
    var path = $("#dislike-path").data('path');
    var DATA = {'id':id};
    $.ajax({
        url: path,
        type: 'post',
        data: DATA,
        success: function () {
            var star = $("#" + id + "-star");
            star.attr('class', 'far fa-star like-star');
            star.css('font-size', '25px');
            setTimeout(function () {
                star.css('font-size', '50px');
            }, 200);
            star.data('liked', false);
        },
        error: function () {

        }
    });
}
function decide(id) {
    var star = $("#" + id + "-star");
    if(star.data('liked') == true)
        dislike(id);
    else
        like(id);
}
function goTo(event, id) {
   if(event.target == document.getElementById(id + '-star')){
       event.preventDefault();
   }
}