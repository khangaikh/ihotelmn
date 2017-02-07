var widgetId1;
var widgetId2;
var widgetId3;
$('#login').click(function(){
    $('.alert').hide(0);
    var $this = $(this);
    $this.after('<div id="loading"></div>');
    $this.addClass('disabled');
    $.post("login.php", {
        email: $('#email').val(),
        password: $('#password').val(),
        g_recaptcha_response: $('#recaptcha1').val()
    },
    function (result) {
        console.log(result);
        if(result==1){
            window.location.replace("index.php");
        }
        else if(result==2){
            $('#loading').remove();
            $this.removeClass('disabled');
            $('#login-alert').fadeIn(500);
            $('#login-alert p').text('Капча-г заавал бөглөх ёстой!');
        }
        else{
            $('#loading').remove();
            $this.removeClass('disabled');
            $('#login-alert').fadeIn(500);
            $('#login-alert p').text('Нэвтрэх нэр эсвэл нууц үг буруу байна!');
            grecaptcha.reset(widgetId1);
        }
    });
});
$('#add').click(function(){
    $('#register-success').hide();
    $('#register-alert').hide();
    var $this = $(this);
    $this.after('<div id="loading"></div>');
    $this.addClass('disabled');
    $.post("user.php", {
        email: $('#remail').val(),
        username: $('#rusername').val(),
        password: $('#rpassword').val(),
        location: 'Mongolia',
        g_recaptcha_response: $('#recaptcha2').val()
    }, function (result) {
        if(result==1){
            $('#loading').remove();
            $('#register-success').fadeIn(500);
            $('#register-success p').text('Бүртгэл амжилттай боллоо. Та и-мэйл рүүгээ орж бүртгэлээ идэвхижүүлнэ үү!');
            grecaptcha.reset(widgetId3);
        }
        else if(result==2){
            $('#loading').remove();
            $this.removeClass('disabled');
            $('#login-alert').fadeIn(500);
            $('#login-alert p').text('Капча-г заавал бөглөх ёстой!');
        }
        else if(result == 2020){
            $('#loading').remove();
            $this.removeClass('disabled');
            $('#register-alert').fadeIn(500);
            $('#register-alert p').text('Бүртгэлтэй имэйл хаяг байна!');
            grecaptcha.reset(widgetId3);
        }
        else{
            $('#loading').remove();
            $this.removeClass('disabled');
            $('#register-alert').fadeIn(500);
            $('#register-alert p').text('Та өмнө нь бүртгэгдсэн эсвэл бүртгэлийн мэдээллээ буруу бөглөсөн байна!');
            grecaptcha.reset(widgetId3);
        }
    });
});
$('#forgot_password').click(function(){
    $('#register-success1').hide();
    $('#login-alert1').hide();
    $.post("user.php", {
        forgot_password: $('#forgot_email').val(),
        g_recaptcha_response: $('#recaptcha3').val()
    }, function (result) {
        if(result==1){
            $(".mfp-close").trigger('click');
            $('#register-success1').fadeIn(500);
            $('#register-success1 p').text('Та и-мэйл рүүгээ орж нууц үгээ сэргээнэ үү!');
            grecaptcha.reset(widgetId2);
        }
        else if(result==2){
            $(".mfp-close").trigger('click');
            $('#login-alert1').fadeIn(500);
            $('#login-alert1 p').text('reCaptche error!');
        }
        else{
            $(".mfp-close").trigger('click');
            $('#login-alert1').fadeIn(500);
            $('#login-alert1 p').text('Таны и-мэйл хаяг буруу байна!');
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
var verifyCallback2 = function(response) {
    $("#recaptcha3").val(response);
};
var onloadCallback = function() {
    widgetId1 = grecaptcha.render('RecaptchaField1', {
        'sitekey' : '6LfSXhoTAAAAAC9V-mDsf7yQr2-wOxmnZHKo7nMP',
              'callback' : verifyCallback,
              'theme' : 'light'
    });
    widgetId2 = grecaptcha.render('RecaptchaField3', {
        'sitekey' : '6LfSXhoTAAAAAC9V-mDsf7yQr2-wOxmnZHKo7nMP',
              'callback' : verifyCallback2,
              'theme' : 'light'
    });
    widgetId3 = grecaptcha.render('RecaptchaField2', {
        'sitekey' : '6LfSXhoTAAAAAC9V-mDsf7yQr2-wOxmnZHKo7nMP',
              'callback' : verifyCallback1,
              'theme' : 'light'
    });
};
$(document).ready(function(){
    onloadCallback();
});
