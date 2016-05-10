"use strict";
$('footer-year').append('2016');
$('ul.slimmenu').slimmenu({
    resizeWidth: '992',
    collapserTitle: '',
    animSpeed: 250,
    indentChildren: true,
    childrenIndenter: ''
});


// Countdown
$('.countdown').each(function() {
    var count = $(this);
    $(this).countdown({
        zeroCallback: function(options) {
            var newDate = new Date(),
            newDate = newDate.setHours(newDate.getHours() + 130);

            $(count).attr("data-countdown", newDate);
            $(count).countdown({
                unixFormat: true
            });
        }
    });
});


$('.btn').button();

$("[rel='tooltip']").tooltip();

$('.form-group').each(function() {
    var self = $(this),
    input = self.find('input');

    input.focus(function() {
        self.addClass('form-group-focus');
    })

    input.blur(function() {
        if (input.val()) {
            self.addClass('form-group-filled');
        } else {
            self.removeClass('form-group-filled');
        }
        self.removeClass('form-group-focus');
    });
});

$('.typeahead').typeahead({
    hint: true,
    highlight: true,
    minLength: 3,
    limit: 8
}, {
    source: function(q, cb) {
        return $.ajax({
            dataType: 'json',
            type: 'get',
            url: 'https://gd.geobytes.com/AutoCompleteCity?callback=?&q=' + q,
            chache: false,
            success: function(data) {
                var result = [];
                $.each(data, function(index, val) {
                    result.push({
                        value: val
                    });
                });
                cb(result);
            }
        });
    }
});

$('.booking-item-price-calc .checkbox label').click(function() {
    var checkbox = $(this).find('input'),
        // checked = $(checkboxDiv).hasClass('checked'),
        checked = $(checkbox).prop('checked'),
        price = parseInt($(this).find('span.pull-right').html().replace('$', '')),
        eqPrice = $('#car-equipment-total'),
        tPrice = $('#car-total'),
        eqPriceInt = parseInt(eqPrice.attr('data-value')),
        tPriceInt = parseInt(tPrice.attr('data-value')),
        value,
        animateInt = function(val, el, plus) {
            value = function() {
                if (plus) {
                    return el.attr('data-value', val + price);
                } else {
                    return el.attr('data-value', val - price);
                }
            };
            return $({
                val: val
            }).animate({
                val: parseInt(value().attr('data-value'))
            }, {
                duration: 500,
                easing: 'swing',
                step: function() {
                    if (plus) {
                        el.text(Math.ceil(this.val));
                    } else {
                        el.text(Math.floor(this.val));
                    }
                }
            });
        };
        if (!checked) {
            animateInt(eqPriceInt, eqPrice, true);
            animateInt(tPriceInt, tPrice, true);
        } else {
            animateInt(eqPriceInt, eqPrice, false);
            animateInt(tPriceInt, tPrice, false);
        }
    });


$('div.bg-parallax').each(function() {
    var $obj = $(this);
    if($(window).width() > 992 ){
        $(window).scroll(function() {
            var animSpeed;
            if ($obj.hasClass('bg-blur')) {
                animSpeed = 10;
            } else {
                animSpeed = 15;
            }
            var yPos = -($(window).scrollTop() / animSpeed);
            var bgpos = '50% ' + yPos + 'px';
            $obj.css('background-position', bgpos);

        });
    }
});



$(document).ready(
    function() {

        $('html').niceScroll({
            cursorcolor: "#000",
            cursorborder: "0px solid #fff",
            railpadding: {
                top: 0,
                right: 0,
                left: 0,
                bottom: 0
            },
            cursorwidth: "10px",
            cursorborderradius: "0px",
            cursoropacitymin: 0.2,
            cursoropacitymax: 0.8,
            boxzoom: true,
            horizrailenabled: false,
            zindex: 9999
        });


        // Owl Carousel
        var owlCarousel = $('#owl-carousel'),
        owlItems = owlCarousel.attr('data-items'),
        owlCarouselSlider = $('#owl-carousel-slider'),
        owlNav = owlCarouselSlider.attr('data-nav');
        // owlSliderPagination = owlCarouselSlider.attr('data-pagination');

        owlCarousel.owlCarousel({
            items: owlItems,
            navigation: true,
            navigationText: ['', '']
        });

        owlCarouselSlider.owlCarousel({
            slideSpeed: 300,
            paginationSpeed: 400,
            // pagination: owlSliderPagination,
            singleItem: true,
            navigation: true,
            navigationText: ['', ''],
            transitionStyle: 'fade',
        });


    // footer always on bottom
    var docHeight = $(window).height();
    var footerHeight = $('#main-footer').height();
    var footerTop = $('#main-footer').position().top + footerHeight;

    if (footerTop < docHeight) {
        $('#main-footer').css('margin-top', (docHeight - footerTop) + 'px');
    }
}


);


$('.nav-drop').dropit();



$('.i-check, .i-radio').iCheck({
    checkboxClass: 'i-check',
    radioClass: 'i-radio'
});



$('.booking-item-review-expand').click(function(event) {
    console.log('baz');
    var parent = $(this).parent('.booking-item-review-content');
    if (parent.hasClass('expanded')) {
        parent.removeClass('expanded');
    } else {
        parent.addClass('expanded');
    }
});


$('.stats-list-select > li > .booking-item-rating-stars > li').each(function() {
    var list = $(this).parent(),
    listItems = list.children(),
    itemIndex = $(this).index();

    $(this).hover(function() {
        for (var i = 0; i < listItems.length; i++) {
            if (i <= itemIndex) {
                $(listItems[i]).addClass('hovered');
            } else {
                break;
            }
        };
        $(this).click(function() {
            for (var i = 0; i < listItems.length; i++) {
                if (i <= itemIndex) {
                    $(listItems[i]).addClass('selected');
                } else {
                    $(listItems[i]).removeClass('selected');
                }
            };
        });
    }, function() {
        listItems.removeClass('hovered');
    });
});



$('.booking-item-container').children('.booking-item').click(function(event) {
    if ($(this).hasClass('active')) {
        $(this).removeClass('active');
        $(this).parent().removeClass('active');
    } else {
        $(this).addClass('active');
        $(this).parent().addClass('active');
        $(this).delay(1500).queue(function() {
            $(this).addClass('viewed')
        });
    }
});


$('.form-group-cc-number input').payment('formatCardNumber');
$('.form-group-cc-date input').payment('formatCardExpiry');
$('.form-group-cc-cvc input').payment('formatCardCVC');




if ($('#map-canvas').length) {
    var map,
    service;
    jQuery(function($) {
        $(document).ready(function() {
            var latlng = new google.maps.LatLng(47.921634, 106.922474);
            var myOptions = {
                zoom: 16,
                center: latlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                scrollwheel: false
            };

            map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);


            var marker = new google.maps.Marker({
                position: latlng,
                map: map
            });
            marker.setMap(map);


            $('a[href="#google-map-tab"]').on('shown.bs.tab', function(e) {
                google.maps.event.trigger(map, 'resize');
                map.setCenter(latlng);
            });
        });
    });
}


$('.card-select > li').click(function() {
    self = this;
    $(self).addClass('card-item-selected');
    $(self).siblings('li').removeClass('card-item-selected');
    $('.form-group-cc-number input').click(function() {
        $(self).removeClass('card-item-selected');
    });
});
// Lighbox gallery
$('#popup-gallery').each(function() {
    $(this).magnificPopup({
        delegate: 'a.popup-gallery-image',
        type: 'image',
        gallery: {
            enabled: true
        }
    });
});

// Lighbox image
$('.popup-image').magnificPopup({
    type: 'image'
});

// Lighbox text
$('.popup-text').magnificPopup({
    removalDelay: 500,
    closeBtnInside: true,
    callbacks: {
        beforeOpen: function() {
            this.st.mainClass = this.st.el.attr('data-effect');
        }
    },
    midClick: true
});

// Lightbox iframe
$('.popup-iframe').magnificPopup({
    dispableOn: 700,
    type: 'iframe',
    removalDelay: 160,
    mainClass: 'mfp-fade',
    preloader: false
});


$('.form-group-select-plus').each(function() {
    var self = $(this),
    btnGroup = self.find('.btn-group').first(),
    select = self.find('select');
    btnGroup.children('label').last().click(function() {
        btnGroup.addClass('hidden');
        select.removeClass('hidden');
    });
});
// Responsive videos
$(document).ready(function() {
    $("body").fitVids();
});

$(function($) {
    $("#twitter").tweet({
        username: "remtsoy", //!paste here your twitter username!
        count: 3
    });
});

$(function($) {
    $("#twitter-ticker").tweet({
        username: "remtsoy", //!paste here your twitter username!
        page: 1,
        count: 20
    });
});

$(document).ready(function() {
    var ul = $('#twitter-ticker').find(".tweet-list");
    var ticker = function() {
        setTimeout(function() {
            ul.find('li:first').animate({
                marginTop: '-4.7em'
            }, 850, function() {
                $(this).detach().appendTo(ul).removeAttr('style');
            });
            ticker();
        }, 5000);
    };
    ticker();
});
$(function() {
    $('#ri-grid').gridrotator({
        rows: 4,
        columns: 8,
        animType: 'random',
        animSpeed: 1200,
        interval: 1200,
        step: 'random',
        preventClick: false,
        maxStep: 2,
        w992: {
            rows: 5,
            columns: 4
        },
        w768: {
            rows: 6,
            columns: 3
        },
        w480: {
            rows: 8,
            columns: 3
        },
        w320: {
            rows: 5,
            columns: 4
        },
        w240: {
            rows: 6,
            columns: 4
        }
    });

});


$(function() {
    $('#ri-grid-no-animation').gridrotator({
        rows: 4,
        columns: 8,
        slideshow: false,
        w1024: {
            rows: 4,
            columns: 6
        },
        w768: {
            rows: 3,
            columns: 3
        },
        w480: {
            rows: 4,
            columns: 4
        },
        w320: {
            rows: 5,
            columns: 4
        },
        w240: {
            rows: 6,
            columns: 4
        }
    });

});

var tid = setInterval(tagline_vertical_slide, 2500);

// vertical slide
function tagline_vertical_slide() {
    var curr = $("#tagline ul li.active");
    curr.removeClass("active").addClass("vs-out");
    setTimeout(function() {
        curr.removeClass("vs-out");
    }, 500);

    var nextTag = curr.next('li');
    if (!nextTag.length) {
        nextTag = $("#tagline ul li").first();
    }
    nextTag.addClass("active");
}

function abortTimer() { // to be called when you want to stop the timer
    clearInterval(tid);
}
//Loader
$(document).ready(function(){
  var counter = 0;
  setInterval(function() {
      var frames=12;
      var frameWidth = 30;
      var offset=counter * -frameWidth;
      $("#loading").css('background-position', 0 + "px" + " " + offset + "px");
      $('.loading-input').css('background-position', 0 + "px" + " " + offset + "px");
      counter++; if (counter>=frames) counter =0; }, 100);
});

$('.alert .close').click(function(e){
    e.stopPropagation();
    $(this).parent('.alert').fadeOut(300);

});
$(document).ready(function(){
  $('.disabled').click(function(){
    return false;
});
});
    function format(num){
    var n = num.toString(), p = n.indexOf('.');
    return n.replace(/\d(?=(?:\d{3})+(?:\.|$))/g, function($0, i){
        return p<0 || i<p ? ($0+',') : $0;
    });
}
$(document).ready(function(){
    $('.ajax-full-width').click(function(){
        var width = $(this).width();
        $(this).width(width - 60);
        $(this).after('<div id="loading"></div>');
        $(this).addClass('disabled');
    });
    $('.ajax2').click(function(){
        $(this).append('<div id="loading" style="position:absolute; top:5px; left:20px" ></div>');
        $(this).addClass('disabled');
    });
    $('.ajax').click(function(){
        $(this).after('<div id="loading"></div>');
        $(this).addClass('disabled');
    });
    $(document).ajaxComplete(function(){
        $('#loading').remove();
        $('.disabled').removeClass('disabled');
    });

});
/* add hotel js */

var map = new google.maps.Map(document.getElementById('map-geo'), {
    zoom: 13,
    center: new google.maps.LatLng(47.912318, 106.913816),
    mapTypeId: google.maps.MapTypeId.ROADMAP
});
function initMap() {
    var marker= null;
    map = new google.maps.Map(document.getElementById('map-geo'), {
        zoom: 13,
        center: new google.maps.LatLng(47.912318, 106.913816),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var input = /** @type {!HTMLInputElement} */(
            document.getElementById('pac-input'));

    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);

    autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
        }

        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);  // Why 17? Because it looks good.
        }

        var address = '';
        if (place.address_components) {
            address = [
        (place.address_components[0] && place.address_components[0].short_name || ''),
        (place.address_components[1] && place.address_components[1].short_name || ''),
        (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
        }
    });

    google.maps.event.addListener(map, "click", function(event) {
        if(marker != null){
            marker.setMap(null);
        }

        $("#m_lat").val(event.latLng.lat());
        $("#m_lng").val(event.latLng.lng());

        marker = new google.maps.Marker({
            position: event.latLng,
               map: map
        });
    });
}
var validateFront = function () {
    if ($('#create_basic_info_form')){
        if (true === $('#create_basic_info_form').parsley().isValid()) {
            $('.bs-callout-info').removeClass('hidden');
            $('.bs-callout-warning').addClass('hidden');
        }
        else {
            $('.bs-callout-info').addClass('hidden');
            $('.bs-callout-warning').removeClass('hidden');
        }
    }
    if ($('#create_property_details_form')){

        if (true === $('#create_property_details_form').parsley().isValid()) {
            $('.bs-callout-info').removeClass('hidden');
            $('.bs-callout-warning').addClass('hidden');
        }
        else {
            $('.bs-callout-info').addClass('hidden');
            $('.bs-callout-warning').removeClass('hidden');
        }
    }
    if ($('#create_room_details_form')){

        if (true === $('#create_room_details_form').parsley().isValid()) {
            $('.bs-callout-info').removeClass('hidden');
            $('.bs-callout-warning').addClass('hidden');
        }
        else {
            $('.bs-callout-info').addClass('hidden');
            $('.bs-callout-warning').removeClass('hidden');
        }
    }
    if ($('#create_photo_upload_form')){

        if (true === $('#create_photo_upload_form').parsley().isValid()) {
            $('.bs-callout-info').removeClass('hidden');
            $('.bs-callout-warning').addClass('hidden');
        }
        else {
            $('.bs-callout-info').addClass('hidden');
            $('.bs-callout-warning').removeClass('hidden');
        }
    }
    if ($('#create_agreement_form')){

        if (true === $('#create_agreement_form').parsley().isValid()) {
            $('.bs-callout-info').removeClass('hidden');
            $('.bs-callout-warning').addClass('hidden');
        }
        else {
            $('.bs-callout-info').addClass('hidden');
            $('.bs-callout-warning').removeClass('hidden');
        }
    }
    if ($('#create_payment_form')){

        if (true === $('#create_payment_form').parsley().isValid()) {
            $('.bs-callout-info').removeClass('hidden');
            $('.bs-callout-warning').addClass('hidden');
        }
        else {
            $('.bs-callout-info').addClass('hidden');
            $('.bs-callout-warning').removeClass('hidden');
        }
    }
    if ($('#create_preview_form')){

        if (true === $('#create_preview_form').parsley().isValid()) {
            $('.bs-callout-info').removeClass('hidden');
            $('.bs-callout-warning').addClass('hidden');
        }
        else {
            $('.bs-callout-info').addClass('hidden');
            $('.bs-callout-warning').removeClass('hidden');
        }
    }
};
$("#room_edit_detail_edit_continue_btn").click(function(){
    $("#edit_room_details_form").parsley().validate();
    validateFront();
    if($("#edit_room_details_form").parsley().isValid() ){
        var roomId = $("#edit_room_id").val();
        var values = $("#edit_room_details_form").serializeObject();
        var room_images = $('img[name="room-edit-images"]').map(function() {return $(this).attr('src');}).get();
        var facilities = $('input[name="amenities_room_edit"]:checkbox:checked').map(function() {return this.value;}).get();
        var $this = $(this);
        $this.append('<div id="loading" style="position:absolute; top:5px; left:20px" ></div>');
        $this.addClass('disabled');

        $.ajax({
            type: "POST",
            url: "room.php",
            data: {data:roomId, values:values, action:3, images:room_images, facilities:facilities},
            success: function(data, textStatus, jqXHR){
                $('#loading').remove();
                $this.removeClass('disabled');
                if(data == 1 ){
                    window.location.reload();
                } 
            }
        });
    }  
});
$.fn.serializeObject = function(){
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};
function removeDiv(i){
    $(i).parent().hide();
}
function removeImage(o,i,j){
    if (confirm('Are you sure ?')) {
        $.ajax({
            type: "POST",
            url: "room.php",
            data: {data:j, order:i, action:2},
            success: function(data, textStatus, jqXHR){
                if(data==1){
                    alert("Амжилттай");
                    $(o).parent().hide();
                }else{
                    alert("Амжилтгүй дахин оролдоно уу");
                }
            }
        }); 

    }
}
function edit_room(j){
    $.ajax({
        type: "POST",
    url: "room.php",
    data: {data:j, action:1},
    success: function(data, textStatus, jqXHR){
        var room = $.parseJSON( data );
        $("#edit_room_id").val(room['id']);
        $("#room_detail_edit_roomtype_id").val(room['type']);
        $("#room_detail_edit_roomtype_custom").val(room['type']);
        $("#room_detail_edit_room_number").val(room['num_rooms']);
        $("#room_detail_edit_room_size").val(room['size']);
        $("#room_detail_edit_room_desc").text(room['desc']);

        for(var i=0; i<room['fac'].length; i++){
            var element ='#edit_'+room['fac'][i];
            $(element).iCheck('check');
        }
        $("#room_detail_edit_bedtype_id_SINGLE_1").val(room['bed_size']);
        $("#room_detail_edit_bed_number_SINGLE_1").val(room['beds']);
        $("#room_detail_edit_num_guests").val(room['num_of_guest']);
        $("#room_detail_edit_room_price_x_persons").val(room['price']);
        $("#room_detail_edit_room_price_2x_persons").val(room['price2']);

        for(var i=0; i<room['images'].length; i++){
            var url = room['images'][i];
            var div = $('<div id="'+i+'" class="col-md-4">');
            var add = $('<a name="remove" class="btn btn-lg" style="margin-left:70px" onClick="removeImage(this,'+i+',\''+room['id']+'\');"><i class="fa fa-times-circle"></a>');
            var img = $('<img>'); 
            img.attr('src', url);
            img.attr('name', 'room-images');
            img.attr('class', "img-thumbnail");
            img.appendTo(div);
            add.appendTo(div);
            div.appendTo('#edit_imagePreview');
        }

        $('#show-edit').trigger('click');
    }
    }); 
}
function delete_room(j){
    if (confirm('Are you sure ?')) {
        $.ajax({
            type: "POST",
            url: "room.php",
            data: {data:j, action:4},
            success: function(data, textStatus, jqXHR){
                if(data == 1 ){
                    window.location.reload();
                } 
            }
        }); 
    }
}
$(document).ready(function(){ 

    $('input[name="room_detail_close_room_start"]').datepicker('setDate', 'today');
    $('input[name="room_detail_close_room_end"]').datepicker('setDate', '+5d');

    check_checked();
    initMap();
    $("#general").hover(function(){
        initMap();
    });
    $("#general").click(function(){
        initMap();
    });
    $('.nav li').not('.active').addClass('disabled');
    $('.nav li').not('.active').find('a').removeAttr("data-toggle");

    var section =$("#sectionId").val();

    for(var i=0; i<=section; i++){
        if (i==0){
            $('#general').removeClass('disabled');
            $('#general').find('a').attr("data-toggle","tab");
        }
        if(i == 1){
            $('#detailed').removeClass('disabled');
            $('#detailed').find('a').attr("data-toggle","tab");
        }
        if(i == 2){
            $('#room').removeClass('disabled');
            $('#room').find('a').attr("data-toggle","tab");
        }
        if(i == 3){
            $('#images').removeClass('disabled');
            $('#images').find('a').attr("data-toggle","tab");
        }
        if(i == 4){
            $('#payment').removeClass('disabled');
            $('#payment').find('a').attr("data-toggle","tab");
        }
        if(i == 5){
            $('#agreement').removeClass('disabled');
            $('#agreement').find('a').attr("data-toggle","tab");
        }
        if(i == 6){
            $('#preview').removeClass('disabled');
            $('#preview').find('a').attr("data-toggle","tab");
        }
        if(i == 7){
            $('#preview').removeClass('disabled');
            $('#preview').find('a').attr("data-toggle","tab");
        }
    }
    if(section == 1){
        $('#detailed').addClass('active');
        $('#property_details_tab').addClass('in active');
    }
    else if(section == 2){
        $('#room').addClass('active');
        $('#room_details_tab').addClass('in active');
    }
    else if(section == 3){
        $('#images').addClass('active');
        $('#property_photos_tab').addClass('in active');
    }
    else if(section == 4){
        $('#payment').addClass('active');
        $('#payment_tab').addClass('in active');
    }
    else if(section == 5){
        $('#agreement').addClass('active');
        $('#agreement_tab').addClass('in active');
    }
    else if(section == 6 || section == 7 ){
        $('#preview').addClass('active');
        $('#preview_tab').addClass('in active');
    }
    else{
        $('#general').addClass('active');
        $('#basic_info_tab').addClass('in active');
    }

    window.Parsley.on('parsley:field:validate', function () {
        validateFront();
        validateFront4();
    });
    var validateFront4 = function () {
        if (true === $('#create_amenities_form').parsley().isValid()) {
            $('.bs-callout-info').removeClass('hidden');
            $('.bs-callout-warning').addClass('hidden');
        }
        else {
            $('.bs-callout-info').addClass('hidden');
            $('.bs-callout-warning').removeClass('hidden');
        }      
    };
    $("#uploadFile").on("change", function(){
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

        $.each( files, function( key, value ) {
            if (/^image/.test( files[key].type)){ // only image file
                var reader = new FileReader(); // instance of the FileReader
                reader.readAsDataURL(files[key]); // read the local file
                reader.onloadend = function(){ 
                    // set image data as background of div
                    var div = $('<div id="'+key+'" class="col-md-6">');
                    var add = $('<a name="remove" class="btn btn-lg" style="margin-left:40px" onClick="removeDiv(this);"><i class="fa fa-times-circle"></a>');
                    var img = $('<img>'); //Equivalent: $(document.createElement('img'))
                    img.attr('src', this.result);
                    img.attr('name', 'room-images');
                    img.attr('class', "img-thumbnail");
                    img.appendTo(div);
                    add.appendTo(div);
                    div.appendTo('#imagePreview');
                }
            }else{
                alert("Зураг биш байна. Тохиромжтой зураг оруулна уу");
            }
        });
    });
    $("#edit_uploadFile").on("change", function(){
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

        $.each( files, function( key, value ) {
            if (/^image/.test( files[key].type)){ // only image file
                var reader = new FileReader(); // instance of the FileReader
                reader.readAsDataURL(files[key]); // read the local file
                reader.onloadend = function(){ 
                    // set image data as background of div
                    var div = $('<div id="'+key+'" class="col-md-6">');
                    var add = $('<a name="remove" class="btn btn-lg" style="margin-left:40px" onClick="removeDiv(this);"><i class="fa fa-times-circle"></a>');
                    var img = $('<img>'); //Equivalent: $(document.createElement('img'))
                    img.attr('src', this.result);
                    img.attr('name', 'room-edit-images');
                    img.attr('class', "img-thumbnail");
                    img.appendTo(div);
                    add.appendTo(div);
                    div.appendTo('#edit_new_imagePreview');
                }
            }else{
                alert("Зураг биш байна. Тохиромжтой зураг оруулна уу");
            }
        });
    });
    $("#hotel_uploadFile").on("change", function(){
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

        $.each( files, function( key, value ) {
            if (/^image/.test( files[key].type)){ // only image file
                var reader = new FileReader(); // instance of the FileReader
                reader.readAsDataURL(files[key]); // read the local file
                reader.onloadend = function(){ 
                    // set image data as background of div
                    var div = $('<div id="'+key+'" class="col-md-6">');
                    var add = $('<a name="remove" class="btn btn-lg" style="margin-left:40px" onClick="removeDiv(this);"><i class="fa fa-times-circle"></a>');
                    var img = $('<img>'); //Equivalent: $(document.createElement('img'))
                    img.attr('src', this.result);
                    img.attr('name', 'hotel-images');
                    img.attr('class', "img-thumbnail");
                    img.appendTo(div);
                    add.appendTo(div);
                    div.appendTo('#hotel_imagePreview');
                }
            }else{
                alert("Зураг биш байна. Тохиромжтой зураг оруулна уу");
            }
        });
    });
    $("#cover_uploadFile").on("change", function(){
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

        $.each( files, function( key, value ) {
            if (/^image/.test( files[key].type)){ // only image file
                var reader = new FileReader(); // instance of the FileReader
                reader.readAsDataURL(files[key]); // read the local file
                reader.onloadend = function(){ 
                    $("#cover_imagePreview").empty();
                    // set image data as background of div
                    var div = $('<div id="'+key+'" class="col-md-12">');
                    var add = $('<a name="remove" class="btn btn-lg" style="margin-left:40px" onClick="removeDiv(this);"><i class="fa fa-times-circle"></a>');
                    var img = $('<img>'); //Equivalent: $(document.createElement('img'))
                    img.attr('src', this.result);
                    img.attr('name', 'cover-images');
                    img.attr('class', "img-thumbnail");
                    img.appendTo(div);
                    add.appendTo(div);
                    div.appendTo('#cover_imagePreview');
                }
            }else{
                alert("Зураг биш байна. Тохиромжтой зураг оруулна уу");
            }
        });
    });
    var hotelId = $("#hotelId").val();
    $("#basic_info_continue_btn").click(function(){
        $("#create_basic_info_form").parsley().validate();
        validateFront();
        if($("#create_basic_info_form").parsley().isValid()){ 
            var values = $("#create_basic_info_form").serializeObject();
            var $this = $(this);
            var $this = $(this);
            $this.append('<div id="loading" style="position:absolute; top:5px; left:20px" ></div>');
            $this.addClass('disabled');
            $.ajax({
                type: "POST",
                url: "create.php",
                data: {data:values, section:1},
                success: function(data, textStatus, jqXHR){
                    //alert(data);
                    if(data == 1 ){
                        $('#navLeftMenu li.active').next('li').removeClass('disabled');
                        $('#navLeftMenu li.active').next('li').find('a').attr("data-toggle","tab");
                        $('#navLeftMenu li.active').next('li').find('a').tab('show');    
                    } 
                }
            });
        }
    });
    $("#property_details_continue_btn").click(function(){

        $("#create_property_details_form").parsley().validate();
        validateFront();
        if($("#create_property_details_form").parsley().isValid() ){
            var values = $("#create_property_details_form").serializeObject();
            var activities = $('input[name="activities"]:checkbox:checked').map(function() {return this.value;}).get();
            var food_drink = $('input[name="food_drink"]:checkbox:checked').map(function() {return this.value;}).get();
            var pool_spa = $('input[name="pool_spa"]:checkbox:checked').map(function() {return this.value;}).get();
            var transportation = $('input[name="transportation"]:checkbox:checked').map(function() {return this.value;}).get();
            var front_desk = $('input[name="front_desk"]:checkbox:checked').map(function() {return this.value;}).get();
            var common_area = $('input[name="common_area"]:checkbox:checked').map(function() {return this.value;}).get();
            var entertainment = $('input[name="entertainment"]:checkbox:checked').map(function() {return this.value;}).get();
            var cleaning = $('input[name="cleaning"]:checkbox:checked').map(function() {return this.value;}).get();
            var business_fac = $('input[name="business_fac"]:checkbox:checked').map(function() {return this.value;}).get();
            var shops = $('input[name="shops"]:checkbox:checked').map(function() {return this.value;}).get();
            var others = $('input[name="others"]:checkbox:checked').map(function() {return this.value;}).get();
            var $this = $(this);
            var $this = $(this);
            $this.append('<div id="loading" style="position:absolute; top:5px; left:20px" ></div>');
            $this.addClass('disabled');

            $.ajax({
                type: "POST",
                url: "create.php",
                data: {data:values, section:2, activities:activities, food_drink: food_drink, pool_spa:pool_spa,transportation:transportation,front_desk:front_desk, common_area:common_area, entertainment:entertainment, cleaning:cleaning, business_fac:business_fac, shops:shops, others:others},
                success: function(data, textStatus, jqXHR){
                    //alert(data);
                    if(data == 1 ){
                        /*enable next tab*/

                        $('#navLeftMenu li.active').next('li').removeClass('disabled');
                        $('#navLeftMenu li.active').next('li').find('a').attr("data-toggle","tab");
                        $('#navLeftMenu li.active').next('li').find('a').tab('show');    
                    } 
                }
            });
        }   
    });
    $("#room_details_continue_btn").click(function(){

        $("#create_room_details_form").parsley().validate();
        validateFront();

        if($("#create_room_details_form").parsley().isValid() ){
            var values = $("#create_room_details_form").serializeObject();
            var room_images = $('img[name="room-images"]').map(function() {return $(this).attr('src');}).get();
            var facilities = $('input[name="amenities_room"]:checkbox:checked').map(function() {return this.value;}).get();
            $("#room_details_continue_btn").attr('disable',true);
            var $this = $(this);
            $this.append('<div id="loading" style="position:absolute; top:5px; left:20px" ></div>');
            $this.addClass('disabled');
            $.ajax({
                type: "POST",
                url: "create.php",
                data: {data:values, section:3, images:room_images, facilities:facilities},
                success: function(data, textStatus, jqXHR){
                    $('#loading').remove();
                    $this.removeClass('disabled');

                    if(data == 1 ){
                        window.location.reload();
                    } 
                }
            });
        }  
    });
    $("#room_continue_btn").click(function(){
        var $this = $(this);
        $this.append('<div id="loading" style="position:absolute; top:5px; left:20px" ></div>');
        $this.addClass('disabled');
        $.ajax({
            type: "POST",
            url: "create.php",
            data: {data:1, section:4},
            success: function(data, textStatus, jqXHR){

                if(data == 1 ){
                    /*enable next tab*/
                    $('#navLeftMenu li.active').next('li').removeClass('disabled');
                    $('#navLeftMenu li.active').next('li').find('a').attr("data-toggle","tab");
                    $('#navLeftMenu li.active').next('li').find('a').tab('show');    
                } 
            }
        });      
    });
    $("#photo_continue_btn").click(function(){
        $("#create_photo_upload_form").parsley().validate();
        $("#cover_photo_upload_form").parsley().validate();
        validateFront();
        if($("#create_photo_upload_form").parsley().isValid() && $("#cover_photo_upload_form").parsley().isValid() ){
            var hotel_images = $('img[name="hotel-images"]').map(function() {return $(this).attr('src');}).get();
            var cover_images = $('img[name="cover-images"]').map(function() {return $(this).attr('src');}).get();
            var $this = $(this);

            $this.append('<div id="loading" style="position:absolute; top:5px; left:20px" ></div>');
            $this.addClass('disabled');

            $.ajax({
                type: "POST",
                url: "create.php",
                data: {data:1, hotel_images:hotel_images, cover_images:cover_images, section:5},
                success: function(data, textStatus, jqXHR){
                    $('#loading').remove();
                    $this.removeClass('disabled');
                    if(data == 1 ){
                        $('#navLeftMenu li.active').next('li').removeClass('disabled');
                        $('#navLeftMenu li.active').next('li').find('a').attr("data-toggle","tab");
                        $('#navLeftMenu li.active').next('li').find('a').tab('show'); 
                    } 
                }
            }); 
        }
    });
    $("#payment_continue_btn").click(function(){
        var $this = $(this);
        $.ajax({
            type: "POST",
            url: "create.php",
            data: {data:1, section:9},
            success: function(data, textStatus, jqXHR){
                if(data == 1 ){
                    $('#navLeftMenu li.active').next('li').removeClass('disabled');
                    $('#navLeftMenu li.active').next('li').find('a').attr("data-toggle","tab");
                    $('#navLeftMenu li.active').next('li').find('a').tab('show'); 
                } 
            }
        }); 
    });
    $("#agreement_continue_btn").click(function(){
        $("#create_agreement_form").parsley().validate();
        validateFront();
        if($("#create_agreement_form").parsley().isValid()){
            var $this = $(this);
            $this.append('<div id="loading" style="position:absolute; top:5px; left:20px" ></div>');
            $this.addClass('disabled');
            $.ajax({
                type: "POST",
                url: "create.php",
                data: {data:1, section:6},
                success: function(data, textStatus, jqXHR){
                    if(data == 1 ){
                        $('#navLeftMenu li.active').next('li').removeClass('disabled');
                        $('#navLeftMenu li.active').next('li').find('a').attr("data-toggle","tab");
                        $('#navLeftMenu li.active').next('li').find('a').tab('show'); 
                    } 
                }
            });
        }
    });
    $('a[name="activate_hotel_btn"]').click(function(){ 
        if (confirm('Are you sure ?')) {
            var $this = $(this);
            $this.append('<div id="loading" style="position:absolute; top:5px; left:20px" ></div>');
            $this.addClass('disabled');

            $.ajax({
                type: "POST",
                url: "create.php",
                data: {data:1, section:7},
                success: function(data, textStatus, jqXHR){
                    if(data == 1 ){
                        window.location.replace("index.php"); 
                    } 
                }
            });
        }
    });
    $("#room_edit_close_btn").click(function(){
        var start = new Date($("#room_detail_close_room_start").val());
        var end = new Date($("#room_detail_close_room_end").val());
        console.log(start);
        console.log(end);
        var d = new Date();
    });
});
