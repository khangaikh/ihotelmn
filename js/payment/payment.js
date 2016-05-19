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
    }, function (result) {
        if(result==1){
            window.location.replace(window.location.href);   
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
$('#forgot_password').click(function(){
    var $this = $(this);
    $this.after('<div id="loading"></div>');
    $this.addClass('disabled');
    $.post("user.php", {
        forgot_password: $('#forgot_email').val(),
        g_recaptcha_response: $('#recaptcha3').val()
    }, function (result) {
        if(result==1){
            $('#forget_success').fadeIn(100);
            $('#forget_success p').text('Та и-мэйл рүүгээ орж нууц үгээ сэргээнэ үү!');
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

    $('#loading').remove();
    $this.removeClass('disabled');
    });
});
$("#golomt_pay").submit(function( event ) {
    if (checkuser) {
        event.preventDefault();
        $.post("order.php", {
            end: $('#end_4').val(),
        }, function (result) {
            if(result!=0){
                var orderID = result;  
                post('https://m.egolomt.mn/billingnew/cardinfo.aspx', {key_number: '254251235225252244203251255231202171168165162162182164175',
                    trans_number: orderID, 
                    trans_amount: $('#total_4').val(), lang_ind: '1',
                    customer_id: '',
                    subID: '1'
                    });
            }
            else{
                $('#loading').remove();
                $this.removeClass('disabled');
                alert(result);
            }
        });
    }
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
function post(path, params, method) {
    method = method || "post"; 
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);
            form.appendChild(hiddenField);
        }
    }
    document.body.appendChild(form);
    form.submit();

};
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
