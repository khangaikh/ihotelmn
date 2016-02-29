"use strict";
$('footer-year').append('2016');
$('ul.slimmenu').slimmenu({
    resizeWidth: '992',
    collapserTitle: 'Main Menu',
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
            url: 'http://gd.geobytes.com/AutoCompleteCity?callback=?&q=' + q,
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


$("#price-slider").ionRangeSlider({
    min: 130,
    max: 575,
    type: 'double',
    prefix: "$",
    // maxPostfix: "+",
    prettify: false,
    hasGrid: true
});

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

$(document).ready(function(){
    $('.ajax').click(function(){
        $(this).after('<div id="loading"></div>');
        $(this).addClass('disabled');
    });
});
function set_total(d){
    var total=0;
    $('span[name=subtotal]').each(function(){
        var sub = $(this).text();
        total = total + parseInt(sub);
    });
    total = parseInt(d) * total;
    $("#total").text("US$ "+total);
}

$(document).ready(function() {
    $.ajax({
        type: "get",
        url:
        "https://api.tripadvisor.com/api/partner/2.0/map/47.9167,106.9167?key=4cc4b5b4bf074bce87fecc651006190e&q="+$('#hotelname').text(),
        dataType: "json",
        success: function(data){ 
            if(data.data.length != 0){
                if(data.data[0].rating!=null){
                    var str = "https://"
                    var arr = data.data[0].api_detail_url.split('//');
                    var new_url = str+arr[1];
                    $.ajax({
                        type: "get",
                        url: new_url,
                        dataType: "json",
                        success: function(data){ 
                            $(".rating_image").attr('src', data.rating_image_url);
                            $(".tr_reviews").text(data.rating);
                            var max = 200;
                            $("#raiting_ex_w").attr('style','width:'+(data.review_rating_count[1]*100/max)+ '%;');
                            $("#raiting_ex").text(data.review_rating_count[1]);
                            $("#raiting_vg_w").attr('style','width:'+(data.review_rating_count[2]*100/max)+ '%;');
                            $("#raiting_vg").text(data.review_rating_count[2]);
                            $("#raiting_ar_w").attr('style','width:'+(data.review_rating_count[3]*100/max)+ '%;');
                            $("#raiting_ar").text(data.review_rating_count[3]);
                            $("#raiting_pr_w").attr('style','width:'+(data.review_rating_count[4]*100/max)+ '%;');
                            $("#raiting_pr").text(data.review_rating_count[4]);
                            $("#raiting_tr_w").attr('style','width:'+(data.review_rating_count[5]*100/max)+ '%;');
                            $("#raiting_tr").text(data.review_rating_count[5]);
                            $("#write_review").attr('href',data.write_review);
                            $("#rt_location").html('<li><img src="'+data.subratings[0].rating_image_url+'" alt="" /></li>');
                            $("#rt_sleep").html('<li><img src="'+data.subratings[1].rating_image_url+'" alt="" /></li>');
                            $("#rt_rooms").html('<li><img src="'+data.subratings[2].rating_image_url+'" alt="" /></li>');
                            $("#rt_service").html('<li><img src="'+data.subratings[3].rating_image_url+'" alt="" /></li>');
                            $("#rt_clearness").html('<li><img src="'+data.subratings[5].rating_image_url+'" alt="" /></li>');
                            for(var j = 0; j<3; j++){
                                $('#reviewslist').append('<li><div class="row"><div class="col-md-2"><div class="booking-item-review-person"><a class="booking-item-review-person-avatar round" href="#"><img src="https://static.tacdn.com/img2/generic/site/no_user_photo-v1.gif" alt="Image Alternative text" title="Good job"></a><p class="booking-item-review-person-name"><a href="#">'+data.reviews[j].user['username']+'</a></p></div></div><div class="col-md-10"><div class="booking-item-review-content"><h5>"'+data.reviews[j].title+'"</h5><ul class="icon-group booking-item-rating-stars"><img style="width:50px;" src="'+data.reviews[j].rating_image_url+'" alt="" /><small style="margin-left: 3px;">'+data.reviews[j].published_date+'</small></ul><p>'+data.reviews[j].text+'<a href="'+data.reviews[j].url+'">More</a></p></div></div></div></div></li>');
                            }
                        }
                    });
                }else{
                    $(".booking-item-rating").hide();
                    //                    $("#rating_div").hide();
                    $(".mb20").hide();
                } 
            }else{
                $(".booking-item-rating").hide();
                $("#rating_div").hide();
                $(".mb20").hide();
            } 
        }
    });
    var start =$("#start_3").val();
    var end =$("#end_3").val();
    var formattedDate = new Date(start);
    var formattedDate1 = new Date(end);

    $("#start").datepicker({"autoclose": true}).on("change", function() {
        $("#end").focus();
    }).datepicker('setDate', formattedDate);
    $("#start").datepicker({ dateFormat: 'dd-mm-yy' });
    $("#end").datepicker({"autoclose": true}).on("change", function() {
        $("#num_people").focus();
    }).datepicker('setDate', formattedDate1).datepicker({ dateFormat: 'dd-mm-yy' });
    $("span[name=order]").click(function( event ) {

        var room_ids = ['1'];
        var room_qtys = ['1'];
        var room_subs = ['1'];
        $('ul[name=order_room]').each(function(){
            var roomid = $(this).attr('roomid');
            room_ids.push(roomid);
        });
        $('span[name=qtys]').each(function(){
            var qty = parseInt($(this).text());
            room_qtys.push(qty);
        });

        $('span[name=subtotal]').each(function(){
            var sub = $(this).text();
            room_subs.push(sub);
        });
        var start = $("#start").val();
        var end = $("#end").val();
        var starta = new Date(start);
        var enda   = new Date(end);
        var diff = new Date(enda - starta);
        // get days
        var days = diff/1000/60/60/24;

        var hotel = $("#hotel_3").val();
        var room_str = room_ids.toString();
        var qty_str = room_qtys.toString();
        var sub_str = room_subs.toString();
        console.log(room_str);
        console.log(qty_str);
        console.log(sub_str);
        $.post("payment.php", {
            start: start,
            end: end,
            hotel: hotel,
            rooms : room_str,
            qtys: qty_str,
            subs:sub_str,
            days:days
        },
        function (result) {
            if(result==1){
                window.location.replace("index.php?asem_payment");  
            }
            else{
                alert("Please check your connection");
            }
        });

    });
    $('select[name=qty]').each(function(){
        $(this).change(function(){
            var start =$("#start").val();
            var end =$("#end").val();
            var starta = new Date(start);
            var enda   = new Date(end);
            var diff = new Date(enda - starta);
            // get days
            var days = diff/1000/60/60/24;

            if($(this).val()>0){
                var roomid = $(this).attr('roomid');
                $('ul[roomid='+roomid+']').remove();
                $("#payment_info").show();


                $("#checkin").text(start);
                $("#checkout").text(end);
                $("#days").text(days);

                var room_name = $(this).attr('room');
                var price = $(this).attr('price');
                var subtotal = $(this).val()* price;

                $("#payment_rooms").append('<ul name="order_room" roomid="'+roomid+'" class="booking-item-payment-price"><li><p class="booking-item-payment-price-title">'+room_name+'</p><p class="booking-item-payment-price-title" style="margin-left:10px"><span name="qtys">'+$(this).val()+'</span><small>/rooms</small></p><p class="booking-item-payment-price-amount">$<span name="subtotal">'+subtotal+'</span><small>/per day</small></p></li></ul>'
                        );
                set_total(days);
            }else{
                var roomid = $(this).attr('roomid');
                $('ul[name='+roomid+']').each(function(){
                    $(this).remove();
                });
                set_total(days);
            }
        });
    });

    $("#search_a").click(function( event ) {
        $('select[name=qty]').each(function(){
            $(this).val("0");
        });
        $("#payment_info").hide();
        //ajax lisr available rooms  
    });
    /*$("a[name=order]").click(function( event ) {
      var curId = $(this).attr('id');
      var start = $("#start_3").val();
      var end = $("#end_3").val();
      var location = $("#location_3").val();
      var hotel = $("#hotel_3").val();
      var country = $("#country").val();
      var url = "index.php?payment=" + curId +"&depart=" + start + "&end=" + end + "&location=" +location+"&hotel="+hotel; 
      window.location.replace(url);
      });*/
}); 
