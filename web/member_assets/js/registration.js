jQuery(document).ready(function() {
    $('.js-datepicker').datepicker();
});

$(function () {
    showFirstStep();
});

$('#fos_user_registration_form_email').change(function () {
    document.getElementById('fos_user_registration_form_email').setCustomValidity("");
});

$('#fos_user_registration_form_username').change(function () {
    document.getElementById('fos_user_registration_form_username').setCustomValidity("");
});

$('.next_step').click(function () {
    checkEmailUnicity();
    checkUsernameUnicity();
    showSecondPage();
});

$('.scd_step').click(function () {
    showThirdPage();
});

$('.bck_scd_step').click(function () {
    showFirstStep();
});

$('.thrd_step').click(function () {
    showFourthPage();
});

$('.bck_thrd_step').click(function () {
    showSecondPage();
});

$('.bck_frth_step').click(function () {
    showThirdPage();
});

$('#final_register').click(function () {
    checkValidity();
});

$('#register_form').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
        e.preventDefault();
        return false;
    }
});

$('#fos_user_registration_form_plainPassword_first').change(function () {
    checkPasswordMatch();
});

$('#fos_user_registration_form_plainPassword_second').change(function () {
    checkPasswordMatch();
});

$('#fos_user_registration_form_birthDate').change(function () {
    var selectedDate = new Date($('#fos_user_registration_form_birthDate').val());
    var today = new Date();
    var age = Math.floor((today-selectedDate) / (365.25 * 24 * 60 * 60 * 1000));
    if(age < 18){
        document.getElementById("fos_user_registration_form_birthDate").setCustomValidity("You must be at least 18 years old to register!");
    }else{
        document.getElementById("fos_user_registration_form_birthDate").setCustomValidity("");
    }
});

function checkPasswordMatch() {
    if($('#fos_user_registration_form_plainPassword_second').val() != $('#fos_user_registration_form_plainPassword_first').val()){
        document.getElementById('fos_user_registration_form_plainPassword_second').setCustomValidity("Passwords Don't Match");
    }else{
        document.getElementById('fos_user_registration_form_plainPassword_second').setCustomValidity('');
    }
}

function checkValidity() {
    if($('div#fos_user_registration_form_preferedStatuses input[type=checkbox]:checked').length == 0){
        document.getElementById('fos_user_registration_form_preferedStatuses_0').setCustomValidity("At least one prefered marital status needs to be selected");
        showThirdPage();
        return;
    }else{
        document.getElementById('fos_user_registration_form_preferedStatuses_0').setCustomValidity("");
    }
    if($('div#fos_user_registration_form_preferedRelations input[type=checkbox]:checked').length == 0){
        document.getElementById('fos_user_registration_form_preferedRelations_0').setCustomValidity("At least one prefered relation needs to be selected");
        showThirdPage();
        return;
    }else{
        document.getElementById('fos_user_registration_form_preferedRelations_0').setCustomValidity("");
    }
    $('.control').each(function (i, v) {
        if(!v.checkValidity()){
            if(v.classList.contains('first-control')){
                showFirstStep();
            }else if(v.classList.contains('second-control')){
                showSecondPage();
            }else if(v.classList.contains('third-control')){
                showThirdPage();
            }else if(v.classList.contains('fourth-control')){
                showFourthPage();
            }
            return false;
        }
    });
    return true;
}

$('.popup-with-zoom-anim').click(function () {
    resetForm();
});

function showFirstStep() {
    $('#first').show();
    $('#step_one').show();

    $('#second').hide();
    $('#step_two').hide();

    $('#third').hide();
    $('#step_three').hide();

    $('#fourth').hide();
    $('#step_four').hide();
}

function showSecondPage() {
    $('#first').hide();
    $('#step_one').hide();

    $('#second').show();
    $('#step_two').show();

    $('#third').hide();
    $('#step_three').hide();

    $('#fourth').hide();
    $('#step_four').hide();
}

function showThirdPage() {
    $('#first').hide();
    $('#step_one').hide();

    $('#second').hide();
    $('#step_two').hide();

    $('#third').show();
    $('#step_three').show();

    $('#fourth').hide();
    $('#step_four').hide();
}

function showFourthPage() {
    $('#first').hide();
    $('#step_one').hide();

    $('#second').hide();
    $('#step_two').hide();

    $('#third').hide();
    $('#step_three').hide();

    $('#fourth').show();
    $('#step_four').show();
}

function updateMaxAge() {
    $('#fos_user_registration_form_maxAge').attr('min', parseInt($('#fos_user_registration_form_minAge').val()) + 1);
    if($('#fos_user_registration_form_maxAge').val() == '' || $('#fos_user_registration_form_maxAge').val()<=$('#fos_user_registration_form_minAge').val()) {
        $('#fos_user_registration_form_maxAge').val(parseInt($('#fos_user_registration_form_minAge').val()) + 1);
    }
}