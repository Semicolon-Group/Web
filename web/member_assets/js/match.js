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
           updateSmokeDrink();
           updateDistanceLogin();
           updateBodyReligionStatus();
       }
   });
    $('#reset-icon').click(function(){
        toggleFilter();
        resetVars();
        resetWidgets();
    });
   $("#age-slider").slider({
       range: true,
       min: 18,
       max: 100,
       values: [ 18, 100 ],
       slide: function( event, ui ) {
           $( "#age-amount" ).val( ui.values[ 0 ] + " - " + ui.values[ 1 ] );
           minAge = ui.values[0];
           maxAge = ui.values[1];
       }
   });
    $("#height-slider").slider({
        range: true,
        min: 150,
        max: 250,
        values: [ 150, 250 ],
        slide: function( event, ui ) {
            $( "#height-amount" ).val( ui.values[ 0 ] + "cm - " + ui.values[ 1 ] + 'cm' );
            minHeight = ui.values[0];
            maxHeight = ui.values[1];
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
        $(".criteria, .reset-icon").delay(350).animate({opacity:1}, 300);
        $(".reset-label").delay(350).animate({opacity:0.3}, 300);
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