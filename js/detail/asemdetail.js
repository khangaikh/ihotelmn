$(document).on("ifChanged",'input[name=pickup]', function( event ) {
    var s = 0;
    var checkedValues1 = $(' input[name="pickup"]:checkbox:checked').map(function() {return this.value;}).get();
    if(checkedValues1.length ==0){
        $("#total").text("US$ "+total);
    } 
    var thisId = this;
    $('input[name="pickup"]').each(function(){
        if(this == thisId){
            if ($(this).is(":checked")) {
                
                if($(this).val()== "budget"){
                    total=total+20;
                }
                if($(this).val()== "vip"){
                    total=total+75;
                }
                $("#total").text("US$ "+total);
            }else{
                if($(this).val()== "budget"){
                    total=total-20;
                }
                if($(this).val()== "vip"){
                    total=total-75;
                }

                $("#total").text("US$ "+total);
            }
        }
        else{
            $(this).attr("checked", false);
            $(this).parent().attr("class", "i-check");
        }
    });
});

$(document).on("ifChanged",'input[name=sim]', function( event ) {
    var s = 0;
    var checkedValues1 = $(' input[name="sim"]:checkbox:checked').map(function() {return this.value;}).get();
    if(checkedValues1.length ==0){
        $("#total").text("US$ "+total);
    } 
    var thisId = this;
    $('input[name="sim"]').each(function(){
        if(this == thisId){
            if ($(this).is(":checked")) {
                
                if($(this).val()== "sim1"){
                    total=total+20;
                }
                if($(this).val()== "sim2"){
                    total=total+30;
                }
                if($(this).val()== "sim3"){
                    total=total+50;
                }
                
                $("#total").text("US$ "+total);
            }else{
                if($(this).val()== "sim1"){
                    total=total-20;
                }
                if($(this).val()== "sim2"){
                    total=total-30;
                }
                if($(this).val()== "sim3"){
                    total=total-50;
                }
                
                $("#total").text("US$ "+total);
            }
        }
        else{
            $(this).attr("checked", false);
            $(this).parent().attr("class", "i-check");
        }
    });
});

$(document).ready(function() {
    $('a[data-toggle="tooltip"]').tooltip({
        animated: 'fade',
        placement: 'bottom',
        container:'body',   
        html: true
    });
    initMap();
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
            var str = "https://"
        var arr = data.write_review.split('//');
    var new_url = str+arr[1];
    console.log();
    $("#write_review").attr('src',new_url);
    $("#rt_location").html('<li><img src="'+data.subratings[0].rating_image_url+'" alt="" /></li>');
    $("#rt_sleep").html('<li><img src="'+data.subratings[1].rating_image_url+'" alt="" /></li>');
    $("#rt_rooms").html('<li><img src="'+data.subratings[2].rating_image_url+'" alt="" /></li>');
    $("#rt_service").html('<li><img src="'+data.subratings[3].rating_image_url+'" alt="" /></li>');
    $("#rt_clearness").html('<li><img src="'+data.subratings[5].rating_image_url+'" alt="" /></li>');
    var htmlreview="";
    for(var j = 0; j<3; j++){
        htmlreview +='<li><div class="row"><div class="col-md-2"><div class="booking-item-review-person"><a class="booking-item-review-person-avatar round" href="#"><img src="https://static.tacdn.com/img2/generic/site/no_user_photo-v1.gif" alt="Image Alternative text" title="Good job"></a><p class="booking-item-review-person-name"><a href="#">'+data.reviews[j].user['username']+'</a></p></div></div><div class="col-md-10"><div class="booking-item-review-content"><h5>"'+data.reviews[j].title+'"</h5><ul class="icon-group booking-item-rating-stars"><img style="width:50px;" src="'+data.reviews[j].rating_image_url+'" alt="" /><small style="margin-left: 3px;">'+data.reviews[j].published_date+'</small></ul><p>'+data.reviews[j].text+'<a href="'+data.reviews[j].url+'">More</a></p></div></div></div></div></li>';
    }
    $('#reviewslist').html(htmlreview);
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

    $("span[name=ordernow]").click(function( event ) {
        var room_ids = [];
        var room_qtys = [];
        var room_subs = [];
        var pickup = "empty";
        var sim = "empty";

        $('ul[name=order_room]').each(function(){
            var roomid = $(this).attr('roomid');
            room_ids.push(roomid);
        });
        $('input[name="pickup"]').each(function(){
            if ($(this).is(":checked")) {
                pickup = $(this).val();
            }
        });

        $('input[name="sim"]').each(function(){
            if ($(this).is(":checked")) {
                sim = $(this).val();
            }
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

        $.post("payment.php", {
            start: start,
            end: end,
            hotel: hotel,
            rooms : room_str,
            qtys: qty_str,
            subs:sub_str,
            pickup: pickup,
            sim: sim,
            days:days
        },
        function (result) {
            console.log(result);
            if(result==1){
                window.location.replace("index.php?asem_payment");  
            }
            else{
                alert(result);
            }
        });

    });
    $(document).on('change','select[name=qty]',function(e){
        var start =$("#start").val();
        var end =$("#end").val();
        var starta = new Date(start);
        var enda   = new Date(end);
        var diff = new Date(enda - starta);

        // get days
        var days = diff/1000/60/60/24;

        if($(this).val()>0){
            var roomid = $(this).attr('roomid');
            var persons = $(this).attr('persons');

            $('ul[roomid='+roomid+']').remove();
            $("#payment_info").show();

            var found = roomid.indexOf("--");

            if(found!=-1){
                var arr = roomid.split('--');
                roomid = arr[0];
            }


            $("#checkin").text(start);
            $("#checkout").text(end);
            $("#days").text(days);

            var room_name = $(this).attr('room');
            var price = $(this).attr('price');
            var subtotal = $(this).val()* price;

            $("#payment_rooms").append('<ul name="order_room" roomid="'+roomid+'" class="booking-item-payment-price"><li><p class="booking-item-payment-price-title">'+room_name+'('+persons+')</p><p class="booking-item-payment-price-title" style="margin-left:10px"><span name="qtys">'+$(this).val()+'</span><small>/rooms</small></p><p class="booking-item-payment-price-amount">$<span name="subtotal">'+subtotal+'</span><small>/per day</small></p></li></ul>'
                );
            set_total(days);
        }else{
            var roomid = $(this).attr('roomid');
            $('ul[roomid='+roomid+']').each(function(){
                $(this).remove();
            });
            set_total(days);
        }    
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
