var widgetId1;
var widgetId2;
var widgetId3;
$('#forgot_password').click(function(){
    $.post("user.php", {
        forgot_password: $('#forgot_email').val(),
        g_recaptcha_response: $('#recaptcha2').val()
    }, function (result) {
        if(result==1){
            $('#forget_success').fadeIn(100);
            $('#forget_success p').text('Please check your email for reseting password!');
            grecaptcha.reset(widgetId2);
        }
        else if(result==2){
            $('#forget_danger').fadeIn(100);
            $('#forget_danger p').text('ReCaptcha required');
        }
        else{
            $('#forget_danger').fadeIn(100);
            $('#forget_danger p').text('Incorrect an email');
            grecaptcha.reset(widgetId2);
        }
    });
});
var verifyCallback = function(response) {
    $("#recaptcha1").val(response);
};
var verifyCallback1 = function(response) {
    $("#recaptcha2").val(response);
};
var verifyCallback3 = function(response) {
    $("#recaptcha3").val(response);
};
var onloadCallback = function() {
    widgetId1 = grecaptcha.render('RecaptchaField1', {
        'sitekey' : '6LfSXhoTAAAAAC9V-mDsf7yQr2-wOxmnZHKo7nMP',
              'callback' : verifyCallback,
              'theme' : 'light'
    });
    widgetId2 = grecaptcha.render('RecaptchaField2', {
        'sitekey' : '6LfSXhoTAAAAAC9V-mDsf7yQr2-wOxmnZHKo7nMP',
              'callback' : verifyCallback1,
              'theme' : 'light'
    });
    widgetId3 = grecaptcha.render('RecaptchaField3', {
        'sitekey' : '6LfSXhoTAAAAAC9V-mDsf7yQr2-wOxmnZHKo7nMP',
              'callback' : verifyCallback3,
              'theme' : 'light'
    });
};

$('#asem_reg').submit(function(e){
    e.preventDefault();
    var $this = $('#register');
    $this.after('<div id="loading"></div>');
    $this.addClass('disabled');
    $phone = $('#telephone').val();
    console.log($phone);
    
    $.post("user.php", {
        email: $('#reg_email').val(),
        username: $('#firstName').val(),    
        lastname: $('#lastName').val(),
        phone: $('#telephone').val(),
        password: $('#reg_password').val(),
        location: $('#location :selected').text(),
        asem: 1,
        type: $('#meeting_type').val(),
        g_recaptcha_response: $('#recaptcha3').val()
    }, function (result) {
        console.log(result);
        if(result==1){
            /*$.post("meeting.php", {
                section: 8,
                lastName: $('#lastName').val(),
                firstName: $('#firstName').val(),
                telephone: $('#telephone').val(),
                location: $('#location').val(),
                email: $('#reg_email').val(),
                arrival_date: $('#arrival_date').val(),
                arrival_time: $('#arrival_time').val(),
                arrival_flight: $('#arrival_flight').val(),
                type: $('#meeting_type').val(),
            }, function (result) {
                if(result==1){*/
            $('#register-success').fadeIn(500);
            $('#asem_reg').hide();
            $('#register-success').html('<p>Please check your email for activation. Click on activation link .Then <a href="index.php?asem_login">click here</a> to login. If you having any trouble please call us: +976-88021087</p>');
               
        }
        else if(result==2){
            $('#login-alert2').fadeIn(500);
            $('#login-alert2 p').text('reCaptcha error!');
        }
        else if(result == 2020){
            $('#login-alert2').fadeIn(500);
            $('#login-alert2 p').text('Already register an email!');
            grecaptcha.reset(widgetId1);
        }
        else if(result == 202){
            $('#login-alert2').fadeIn(500);
            $('#login-alert2 p').text('Already register an email!');
            grecaptcha.reset(widgetId1);
        }
        else{
            $('#login-alert2').fadeIn(500);
            $('#login-alert2 p').text('Please clear your browser history and try again');
            grecaptcha.reset(widgetId3);
        }
    });
});

$(document).ready(function(){
    $('.input-daterange input').datepicker({todayHighlight: true,});
    $('.input-daterange input').datepicker({ dateFormat: 'dd-mm-yy'}).datepicker("setDate", new Date());
    $('.input-timerange input').timepicker();
    $("#not_sure").change(function() {
        if(this.checked) {
            $("#close").hide();
        }else{
            $("#close").show();
        }
    });
    onloadCallback();
});
