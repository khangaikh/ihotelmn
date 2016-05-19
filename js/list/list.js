var map = new google.maps.Map(document.getElementById('maps_hotel'), {
    zoom: 13,
    center: new google.maps.LatLng(47.912318, 106.913816),
    mapTypeId: google.maps.MapTypeId.ROADMAP
});
var infoBubble; var infoBubble2; var countinfo = 0; var currency = ""; var from = ""; var marker;

function initMap(resulst) {
    map = new google.maps.Map(document.getElementById('maps_hotel'), {
        zoom: 13,
        center: new google.maps.LatLng(47.912318, 106.913816),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    for(var i = 0; i<results.length; i++){

        marker = new google.maps.Marker({
            position: {lat: results[i].lat, lng: results[i].lng},
        });

        if(asem){
            currency = "$ "+results[i].avg;
            from = "from";
        }else{
            currency = results[i].avg+" ₮";
            from = "эхлэх";
        }

        infoBubble = new InfoBubble({
            content: '<h5 id="'+results[i].id+'" onclick="help(this.id);"style="text-align: center; cursor: pointer; margin-bottom: 0px; color: #FFF;font-weight: bold;">'+
            currency+'</h5>',
                   arrowSize: 8,
                   maxWidth: 200,
                   minWidth: 100,
                   minHeight: 35,
                   backgroundColor: '#FF0C0C',
                   hideCloseButton: true,
                   arrowPosition: 50,
                   padding: 4,
                   borderRadius: 8,
                   arrowStyle: 0,
                   map: map,
                   shadowStyle: 1
        });
        infoBubble.open(map, marker);
    }
}
function help(tid){
    var contentString;
    $.ajax({
        type: "POST",
        url: "index.php",
        data: {mapGeoId:tid},
        success: function(data, textStatus, jqXHR)
    {
        if (countinfo >= 1) {
            if (infoBubble2.isOpen()) {
                infoBubble2.close();
            }
        }
        countinfo++;
        var hotel = $.parseJSON(data);
        marker = new google.maps.Marker({
            position: {lat: hotel['latitude'], lng: hotel['longitude']},
        });


        var stars ='<div style="font-size: 13px;margin:0;line-height: 20px;"'+
        'class="booking-item-rating mrg-left"><ul class="icon-group booking-item-rating-stars">'; 
    for(var i = 0; i<hotel['stars']; i++){ 
        stars+='<li><i class="fa fa-star"></i></li>';
    }
    stars+='</ul></div>';
    contentString='<a href="index.php?detail='+hotel['id']+'&depart='+$("#start_1").val()+'&end='+$("#end_1").val()+
        '&guests='+$("#guests").val()+'&rooms='+$("#rooms").val()+'"><div id="content">'+
        '<img style="width: 276px; max-height: 180px;overflow: hidden;"src="'+hotel['cover_image']+'"><h1></h1>'+
        '<h4 style="margin: 0;">'+hotel['name']+'<br/>'+stars+'<span style="margin: 0; float:right" class="booking-item-price"><span style="margin: 0; font-size:13px" class="booking-item-price">'+from+'&nbsp;&nbsp;&nbsp;</span>'+currency+'</span>'+
        '</h4></div></a>';

    infoBubble2 = new InfoBubble({
        maxWidth: 300,
                minWidth: 300,
                minHeight: 290,
                hideCloseButton: true,
                overflow: 'hidden',
                content: contentString
    });
    infoBubble2.open(map, marker);
    google.maps.event.addListener(map,'click', function() {
        infoBubble2.close();
    });
    }
    });
}
function details(i){
    event.preventDefault();
    var curId = $(i).attr('id');
    var start = $("#start_1").val();
    var end = $("#end_1").val();
    var location = $("#location_1").val();
    var guests = $("#guests").val();
    var rooms = $("#rooms").val();
    var url = "index.php?detail=" + curId +"&depart=" + start + "&end=" + end + "&guests=" +guests+ "&rooms=" +rooms; 
    window.location.replace(url);
}
$(document).ready(function() {
    var availableTags = ['q','x'];
    $.ajax({
        type: "POST",
        url: "search.php",
        data: {autocomplete:1, city:'Ulaanbaatar', type: "Hotel"},
        success: function(data, textStatus, jqXHR)
    {
        availableTags = $.parseJSON(data);

        $( "#tags" ).autocomplete({
            source: availableTags
        });
    }
    });
    $('input.date-pick, .input-daterange, .date-pick-inline').datepicker({
        todayHighlight: true
    });
    $('input.date-pick, .input-daterange input[name="start_1"]').datepicker();
    $('.input-daterange input[name="end_1"]').datepicker();
    $('input.time-pick').timepicker({
        minuteStep: 15,
        showInpunts: false
    })
    $(document).on("click",'span[name=detail]', function( event ) {
        event.preventDefault();
        var curId = $(this).attr('id');
        var start = $("#start_1").val();
        var end = $("#end_1").val();
        var location = $("#location_1").val();
        var guests = $("#guests").val();
        var rooms = $("#rooms").val();
        if($(this).attr('asem') == 0){
            var url = "index.php?detail=" + curId +"&depart=" + start + "&end=" + end + "&guests=" +guests+ "&rooms="+rooms;
        }
        else{
            var url = "index.php?detail=" + curId +"&depart=" + start + "&end=" + end + "&guests=" +guests+ "&rooms="+rooms;
        }
    window.location.replace(url);
    });
    $(document).on("click",'a[name=detail]', function( event ) {
        event.preventDefault();
        var detialId = $(this).attr('id');
        var curId = detialId;
        var start = $("#start_1").val();
        var end = $("#end_1").val();
        var location = $("#location_1").val();
        var guests = $("#guests").val();
        var rooms = $("#rooms").val();
        if($(this).attr('asem') == 0){
            var url = "index.php?detail=" + curId +"&depart=" + start + "&end=" + end + "&guests=" +guests+ "&rooms="+rooms;
        }
        else{
            var url = "index.php?detail=" + curId +"&depart=" + start + "&end=" + end + "&guests=" +guests+ "&rooms="+rooms;
        }
    window.location.replace(url);
    });
    $('input.date-pick-years').datepicker({
        startView: 2
    });
    $("input").on('ifChanged', function(event){
        $("#tags").val('');
        if(this.id !='asem_box'){
            if(this.id == "tour_box"){ 
                $('#full-loader').remove();
                $('#hotels').append('<div id="full-loader"><div id="loading"></div></div>');
                if(this.checked) {
                    tour = true;
                    asem = false;
                    $("input[name='asem_box']").attr("checked", false);
                    $("input[name='asem_box']").parent().attr("class", "i-check");
                    $("#stars_filter").hide();
                    $("#price_filter").hide();
                    tour_checked();
                }
                else{
                    tour = false;
                    $("#stars_filter").show();
                    $("#price_filter").show();

                    checkedValues1 = $(' input[name="stars"]:checkbox:checked').map(function() {return this.value;}).get();
                    checkedValues2 = $(' input[name="others"]:checkbox:checked').map(function() {return this.value;}).get();
                    checkedValues3 = $(' input[name="entertainment"]:checkbox:checked').map(function() {return this.value;}).get();
                    checkedValues4 = $(' input[name="food_drink"]:checkbox:checked').map(function() {return this.value;}).get();
                    checkedValues5 = $(' input[name="pool_spa"]:checkbox:checked').map(function() {return this.value;}).get();
                    checkedValues6 = $(' input[name="transport"]:checkbox:checked').map(function() {return this.value;}).get();
                    checkedValues7 = $(' input[name="miscellaneous"]:checkbox:checked').map(function() {return this.value;}).get();

                    values1 = checkedValues1.toString();values2 = checkedValues2.toString();values3 = checkedValues3.toString();
                    values4 = checkedValues4.toString();values5 = checkedValues5.toString();values6 = checkedValues6.toString();
                    values7 = checkedValues7.toString();

                    var location = 'Ulaanbaatar, Mongolia'.split(",");
                    var type = "Hotel";

                    if(checkedValues1.length !=0 || checkedValues2.length !=0 
                            || checkedValues3.length !=0 || checkedValues4.length !=0 
                            || checkedValues5.length !=0 || checkedValues6.length !=0 || checkedValues7.length !=0){
                                checked(values1, values2, values3, values4, values5, values6, values7, location[0], type);
                            }else{
                                not_checked(type);
                            }
                }
            }
            else{

                var checkedValues1 = $(' input[name="stars"]:checkbox:checked').map(function() {return this.value;}).get();
                checkedValues2 = $(' input[name="others"]:checkbox:checked').map(function() {return this.value;}).get();
                checkedValues3 = $(' input[name="entertainment"]:checkbox:checked').map(function() {return this.value;}).get();
                checkedValues4 = $(' input[name="food_drink"]:checkbox:checked').map(function() {return this.value;}).get();
                checkedValues5 = $(' input[name="pool_spa"]:checkbox:checked').map(function() {return this.value;}).get();
                checkedValues6 = $(' input[name="transport"]:checkbox:checked').map(function() {return this.value;}).get();
                checkedValues7 = $(' input[name="miscellaneous"]:checkbox:checked').map(function() {return this.value;}).get();

                values1 = checkedValues1.toString();values2 = checkedValues2.toString();values3 = checkedValues3.toString();
                values4 = checkedValues4.toString();values5 = checkedValues5.toString();values6 = checkedValues6.toString();
                values7 = checkedValues7.toString();

                var location = 'Ulaanbaatar, Mongolia'.split(",");
                var type = "Hotel";
                if(tour){
                    type = "Resort";
                } 

                $('#full-loader').remove();
                $('#hotels').append('<div id="full-loader"><div id="loading"></div></div>');

                if(checkedValues1.length !=0 || checkedValues2.length !=0 
                        || checkedValues3.length !=0 || checkedValues4.length !=0 
                        || checkedValues5.length !=0 || checkedValues6.length !=0 || checkedValues7.length !=0){
                            checked(values1, values2, values3, values4, values5, values6, values7, location[0], type);
                        }else{
                            not_checked(type);
                        }
            }
        }
        else{
            $('#full-loader').remove();
            $('#hotels').append('<div id="full-loader"><div id="loading"></div></div>');
            if(this.checked) {
                asem = true;
                tour = false;
                $("#stars_filter").show();
                $("input[name='tour_box']").attr("checked", false);
                $("input[name='tour_box']").parent().attr("class", "i-check");
                asem_checked();

            }else{
                asem = false;

                checkedValues1 = $(' input[name="stars"]:checkbox:checked').map(function() {return this.value;}).get();
                checkedValues2 = $(' input[name="others"]:checkbox:checked').map(function() {return this.value;}).get();
                checkedValues3 = $(' input[name="entertainment"]:checkbox:checked').map(function() {return this.value;}).get();
                checkedValues4 = $(' input[name="food_drink"]:checkbox:checked').map(function() {return this.value;}).get();
                checkedValues5 = $(' input[name="pool_spa"]:checkbox:checked').map(function() {return this.value;}).get();
                checkedValues6 = $(' input[name="transport"]:checkbox:checked').map(function() {return this.value;}).get();
                checkedValues7 = $(' input[name="miscellaneous"]:checkbox:checked').map(function() {return this.value;}).get();

                values1 = checkedValues1.toString();values2 = checkedValues2.toString();values3 = checkedValues3.toString();
                values4 = checkedValues4.toString();values5 = checkedValues5.toString();values6 = checkedValues6.toString();
                values7 = checkedValues7.toString();

                var location = 'Ulaanbaatar, Mongolia'.split(",");
                var type = "Hotel";

                if(checkedValues1.length !=0 || checkedValues2.length !=0 
                        || checkedValues3.length !=0 || checkedValues4.length !=0 
                        || checkedValues5.length !=0 || checkedValues6.length !=0 || checkedValues7.length !=0){
                            checked(values1, values2, values3, values4, values5, values6, values7, location[0], type);
                        }else{
                            not_checked(type);
                        }
            }
        }
    });
});

$("#hotel_map").click(function( event ) {
    $("#hotels").hide();
    $("#maps_hotel").show();
    initMap(results);
});

$("#hotel_list").click(function( event ) {
    $("#maps_hotel").hide();
    $("#hotels").show();
});

$("#search_a").click(function( event ) {
    var check = true;
    var start = $("#start").val();
    var end = $("#end").val();
    var location = $("#location").val();
    var persons = $("#num_people").val();

    if(location == ""){
        check =false;
    }
    if(check){
        var url = "index.php?start=" + start + "&end=" + end + "&location=" +location+"&persons="+persons;  
        window.location.replace(url);    
    }else{
        $("#alert_1").toggle();
        $("#location").css("border-color", "red");
    }   
});
$("#sorting a").click(function( event ) {
    $('#full-loader').remove();
    $('#hotels').append('<div id="full-loader"><div id="loading"></div></div>');
    var sort = $(this).attr('sort');

    checkedValues1 = $(' input[name="stars"]:checkbox:checked').map(function() {return this.value;}).get();
    checkedValues2 = $(' input[name="others"]:checkbox:checked').map(function() {return this.value;}).get();
    checkedValues3 = $(' input[name="entertainment"]:checkbox:checked').map(function() {return this.value;}).get();
    checkedValues4 = $(' input[name="food_drink"]:checkbox:checked').map(function() {return this.value;}).get();
    checkedValues5 = $(' input[name="pool_spa"]:checkbox:checked').map(function() {return this.value;}).get();
    checkedValues6 = $(' input[name="transport"]:checkbox:checked').map(function() {return this.value;}).get();
    checkedValues7 = $(' input[name="miscellaneous"]:checkbox:checked').map(function() {return this.value;}).get();

    values1 = checkedValues1.toString();values2 = checkedValues2.toString();values3 = checkedValues3.toString();
    values4 = checkedValues4.toString();values5 = checkedValues5.toString();values6 = checkedValues6.toString();
    values7 = checkedValues7.toString();

    var location = 'Ulaanbaatar, Mongolia'.split(",");

    var data;
    if(asem){
        data = {data:sort, filter1:values1, filter2:values2, entertainment:values3, food_drink:values4, pool_spa:values5, transport:values6,
            miscellaneous:values7, city:location[0], asem:1, type: "Hotel"};
    }else{
        data = {data:sort, filter1:values1, filter2:values2, entertainment:values3, food_drink:values4, pool_spa:values5, transport:values6,
            miscellaneous:values7, city:location[0], type: "Hotel"};
    }
    $.ajax({
        type: "POST",
        url: "sort.php",
        data: data,
        success: function(data, textStatus, jqXHR)
    {
        $("#hotels").empty();
        var events = data['events'];
        var pages = data['pages'];
        var sort = data['sort'];
        $("#sorting").val(sort);
        results=[];
        $.each(events, function(idx, obj) {
            var index = idx+1;
            var string = "";
            results.push({lat: obj.latitude, lng:obj.longitude, id: ''+obj.id, avg: ''+obj.rate});
            for ($i=0; $i<obj.stars; $i++){
                string += '<i class="fa fa-star"></i></li>';
            }
            if(asem){ currency = "$ "+obj.rate; }else{ currency = obj.rate+" ₮";}
            if(obj.sold_out == 1){
                innerHtml ='<span class="btn btn-primary small-left sold" id="'+obj.id+'">Sold out</span>';
            }else{
                innerHtml ='<span class="btn btn-primary small-left" id="'+obj.id+'">Өрөө сонгох</span>';
            }
            $("#hotels").append('<li><a asem="0" name="detail" class="booking-item" id="'+obj.id+'"><div class="row"><div class="col-md-3"><div class="booking-item-img-wrap"><img src="'+obj.cover+'" alt="Image Alternative text" title="LHOTEL PORTO BAY SAO PAULO suite lhotel living room" /><div class="booking-item-img-num"><i class="fa fa-picture-o"></i>29</div></div></div><div class="col-md-6"> <div class="flex"><div><h5 class="booking-item-title">'+obj.name+'</h5></div><div><div class="booking-item-rating mrg-left"><ul class="icon-group booking-item-rating-stars">'+string+'</ul></div></div></div><small class="booking-item-address"><i class="fa fa-map-marker"></i>'+ obj.address +'</small><br><br><small class="booking-item-address">'+obj.short_desc+'</small></div><div class="col-md-3"><span class="booking-item-price-from small-hidden" style="margin-bottom:8px">эхлэх үнэ</span><span class="booking-item-price">'+currency+'</span><span>/өдөр</span>'+innerHtml+'</div></div></a></li>');
        });
        initMap(results);
        //alert(data);

    }
    });
});
$('#hotels').on('click', 'span[name=detail]',function(event) {
    event.preventDefault();
    var curId = $(this).attr('id');
    var start = $("#start_1").val();
    var end = $("#end_1").val();
    var location = $("#location_1").val();
    var guests = $("#guests").val();
    var rooms = $("#rooms").val();
    var url = "index.php?detail=" + curId +"&depart=" + start + "&end=" + end + "&guests=" +guests+ "&rooms=" +rooms; 
    window.location.replace(url);
});
$('#hotels').on('click', 'a[name=detail]',function(event) {
    event.preventDefault();
    var aseme = $(this).attr("asem");
    var curId = $(this).attr('id');
    var start = $("#start_1").val();
    var end = $("#end_1").val();
    var location = $("#location_1").val();
    var guests = $("#guests").val();
    var rooms = $("#rooms").val();
    var url = "index.php?detail=" + curId +"&depart=" + start + "&end=" + end + "&guests=" +guests+ "&rooms=" +rooms; 
    window.location.replace(url);
});
function not_checked(type){
    var location = 'Ulaanbaatar, Mongolia'.split(",");
    var data;
    if(asem){
        data={not_checked:1, city:location[0], asem:1, type: type};
    }else{
        data={not_checked:1, city:location[0], type: type};
    }
    $.ajax({
        type: "POST",
        url: "search.php",
        data: data,
        success: function(data, textStatus, jqXHR)
    {
        $("#hotels").empty();

        var events = data['events'];
        var pages = data['pages'];
        results = [];
        availableTags = [];
        $.each(events, function(idx, obj) {
            var index = idx+1;
            var string = "";
            availableTags.push(obj.name);  
            results.push({lat: obj.latitude, lng:obj.longitude, id: ''+obj.id, avg: ''+obj.rate});
            for ($i=0; $i<obj.stars; $i++){
                string += '<i class="fa fa-star"></i></li>';
            }
            if(asem){
                currency = "$ "+obj.rate;
                from = "from";
            }else{
                currency = obj.rate+" ₮";
                from = "эхлэх";
            }
            if(obj.sold_out == 1){
                innerHtml ='<span class="btn btn-primary small-left sold" id="'+obj.id+'">Sold out</span>';
            }else{
                innerHtml ='<span class="btn btn-primary small-left" id="'+obj.id+'">Өрөө сонгох</span>';
            }
            $("#hotels").append('<li><a asem="0" name="detail" class="booking-item" id="'+obj.id+'"><div class="row"><div class="col-md-3"><div class="booking-item-img-wrap"><img src="'+obj.cover+'" alt="Image Alternative text" title="LHOTEL PORTO BAY SAO PAULO suite lhotel living room" /><div class="booking-item-img-num"><i class="fa fa-picture-o"></i>29</div></div></div><div class="col-md-6"> <div class="flex"><div><h5 class="booking-item-title">'+obj.name+'</h5></div><div><div class="booking-item-rating mrg-left"><ul class="icon-group booking-item-rating-stars">'+string+'</ul></div></div></div><small class="booking-item-address"><i class="fa fa-map-marker"></i>'+ obj.address +'</small><br><br><small class="booking-item-address">'+obj.short_desc+'</small></div><div class="col-md-3"><span class="booking-item-price-from small-hidden" style="margin-bottom:8px">эхлэх үнэ</span><span class="booking-item-price">'+currency+'</span><span>/өдөр</span>'+innerHtml+'</div></div></a></li>');

        });
        $( "#tags" ).autocomplete({
            source: availableTags
        });
        initMap(results);
    }
    });
}
function checked(values1, values2, values3, values4, values5, values6, values7, location, type){
    var data;
    if(asem){
        data={data:values1, filter:values2, entertainment:values3, food_drink:values4, pool_spa:values5, transport:values6, miscellaneous:values7, city:location, asem:1, type: type};
    }else{
        data={data:values1, filter:values2, entertainment:values3, food_drink:values4, pool_spa:values5, transport:values6, miscellaneous:values7, city:location, type: type};
    }
    $("#tags").val('');
    $.ajax({
        type: "POST",
        url: "search.php",
        data: data,
        success: function(data, textStatus, jqXHR)
    {
        $("#hotels").empty();
        var events = data['events'];
        var pages = data['pages'];
        results=[];
        availableTags = [];
        $.each(events, function(idx, obj) {
            var index = idx+1;
            var string = "";
            availableTags.push(obj.name);  
            results.push({lat: obj.latitude, lng:obj.longitude, id: ''+obj.id, avg: ''+obj.rate});
            for ($i=0; $i<obj.stars; $i++){
                string += '<i class="fa fa-star"></i></li>';
            }
            if(asem){
                currency = "$ "+obj.rate;
                from = "from";
            }else{
                currency = obj.rate+" ₮";
                from = "эхлэх";
            }
            if(obj.sold_out == 1){
                innerHtml ='<span class="btn btn-primary small-left sold" id="'+obj.id+'">Sold out</span>';
            }else{
                innerHtml ='<span class="btn btn-primary small-left" id="'+obj.id+'">Өрөө сонгох</span>';
            }
            $("#hotels").append('<li><a asem="0" name="detail" class="booking-item" id="'+obj.id+'"><div class="row"><div class="col-md-3"><div class="booking-item-img-wrap"><img src="'+obj.cover+'" alt="Image Alternative text" title="LHOTEL PORTO BAY SAO PAULO suite lhotel living room" /><div class="booking-item-img-num"><i class="fa fa-picture-o"></i>29</div></div></div><div class="col-md-6"> <div class="flex"><div><h5 class="booking-item-title">'+obj.name+'</h5></div><div><div class="booking-item-rating mrg-left"><ul class="icon-group booking-item-rating-stars">'+string+'</ul></div></div></div><small class="booking-item-address"><i class="fa fa-map-marker"></i>'+ obj.address +'</small><br><br><small class="booking-item-address">'+obj.short_desc+'</small></div><div class="col-md-3"><span class="booking-item-price-from small-hidden" style="margin-bottom:8px">эхлэх үнэ</span><span class="booking-item-price">'+currency+'</span><span>/өдөр</span>'+innerHtml+'</div></div></a></li>');

        });
        $( "#tags" ).autocomplete({
            source: availableTags
        });
        initMap(results);
    }
    });
}
function asem_checked(){
    $("#tags").val('');

    checkedValues1 = $(' input[name="stars"]:checkbox:checked').map(function() {return this.value;}).get();
    checkedValues2 = $(' input[name="others"]:checkbox:checked').map(function() {return this.value;}).get();
    checkedValues3 = $(' input[name="entertainment"]:checkbox:checked').map(function() {return this.value;}).get();
    checkedValues4 = $(' input[name="food_drink"]:checkbox:checked').map(function() {return this.value;}).get();
    checkedValues5 = $(' input[name="pool_spa"]:checkbox:checked').map(function() {return this.value;}).get();
    checkedValues6 = $(' input[name="transport"]:checkbox:checked').map(function() {return this.value;}).get();
    checkedValues7 = $(' input[name="miscellaneous"]:checkbox:checked').map(function() {return this.value;}).get();

    values1 = checkedValues1.toString();values2 = checkedValues2.toString();values3 = checkedValues3.toString();
    values4 = checkedValues4.toString();values5 = checkedValues5.toString();values6 = checkedValues6.toString();
    values7 = checkedValues7.toString();

    var location = 'Ulaanbaatar, Mongolia'.split(",");

    if(checkedValues1.length !=0 || checkedValues2.length !=0  || checkedValues3.length !=0 || checkedValues4.length !=0 
            || checkedValues5.length !=0 || checkedValues6.length !=0 || checkedValues7.length !=0){

                $.ajax({
                    type: "POST",
                    url: "search.php",
                    data: {data:values1, filter:values2, entertainment:values3, food_drink:values4, pool_spa:values5, transport:values6, miscellaneous:values7,
                        city:location[0], asem:1, type: "Hotel"},
                    success: function(data, textStatus, jqXHR)
                {
                    $("#hotels").empty();

                    var events = data['events'];
                    var pages = data['pages'];
                    results=[];
                    availableTags = [];
                    $.each(events, function(idx, obj) {
                        var index = idx+1;
                        var string = "";
                        availableTags.push(obj.name);  
                        results.push({lat: obj.latitude, lng:obj.longitude, id: ''+obj.id, avg: ''+obj.rate});
                        for ($i=0; $i<obj.stars; $i++){
                            string += '<i class="fa fa-star"></i></li>';
                        }
                        if(asem){ currency = "$ "+obj.rate; }else{ currency = obj.rate+" ₮";}
                        if(obj.sold_out == 1){
                            innerHtml ='<span class="btn btn-primary small-left sold" id="'+obj.id+'">Sold out</span>';
                        }else{
                            innerHtml ='<span class="btn btn-primary small-left" id="'+obj.id+'">Өрөө сонгох</span>';
                        }
                        $("#hotels").append('<li><a asem="0" name="detail" class="booking-item" id="'+obj.id+'"><div class="row"><div class="col-md-3"><div class="booking-item-img-wrap"><img src="'+obj.cover+'" alt="Image Alternative text" title="LHOTEL PORTO BAY SAO PAULO suite lhotel living room" /><div class="booking-item-img-num"><i class="fa fa-picture-o"></i>29</div></div></div><div class="col-md-6"> <div class="flex"><div><h5 class="booking-item-title">'+obj.name+'</h5></div><div><div class="booking-item-rating mrg-left"><ul class="icon-group booking-item-rating-stars">'+string+'</ul></div></div></div><small class="booking-item-address"><i class="fa fa-map-marker"></i>'+ obj.address +'</small><br><br><small class="booking-item-address">'+obj.short_desc+'</small></div><div class="col-md-3"><span class="booking-item-price-from small-hidden" style="margin-bottom:8px">эхлэх үнэ</span><span class="booking-item-price">'+currency+'</span><span>/өдөр</span>'+innerHtml+'</div></div></a></li>');

                    });
                    $( "#tags" ).autocomplete({
                        source: availableTags
                    });
                    initMap(results);
                }
                });
            }else{
                $.ajax({
                    type: "POST",
                    url: "search.php",
                    data: {changed_asem:1, city:location[0], asem:1, type: "Hotel"},
                    success: function(data, textStatus, jqXHR)
                {
                    $("#hotels").empty();

                    var events = data['events'];
                    var pages = data['pages'];
                    results=[];
                    availableTags = [];
                    $.each(events, function(idx, obj) {
                        var index = idx+1;
                        var string = "";
                        availableTags.push(obj.name);  
                        results.push({lat: obj.latitude, lng:obj.longitude, id: ''+obj.id, avg: ''+obj.rate});
                        for ($i=0; $i<obj.stars; $i++){
                            string += '<i class="fa fa-star"></i></li>';
                        }
                        if(asem){ currency = "$ "+obj.rate; }else{ currency = obj.rate+" ₮";}
                        if(obj.sold_out == 1){
                            innerHtml ='<span class="btn btn-primary small-left sold" id="'+obj.id+'">Sold out</span>';
                        }else{
                            innerHtml ='<span class="btn btn-primary small-left" id="'+obj.id+'">Өрөө сонгох</span>';
                        }
                        $("#hotels").append('<li><a asem="0" name="detail" class="booking-item" id="'+obj.id+'"><div class="row"><div class="col-md-3"><div class="booking-item-img-wrap"><img src="'+obj.cover+'" alt="Image Alternative text" title="LHOTEL PORTO BAY SAO PAULO suite lhotel living room" /><div class="booking-item-img-num"><i class="fa fa-picture-o"></i>29</div></div></div><div class="col-md-6"> <div class="flex"><div><h5 class="booking-item-title">'+obj.name+'</h5></div><div><div class="booking-item-rating mrg-left"><ul class="icon-group booking-item-rating-stars">'+string+'</ul></div></div></div><small class="booking-item-address"><i class="fa fa-map-marker"></i>'+ obj.address +'</small><br><br><small class="booking-item-address">'+obj.short_desc+'</small></div><div class="col-md-3"><span class="booking-item-price-from small-hidden" style="margin-bottom:8px">эхлэх үнэ</span><span class="booking-item-price">'+currency+'</span><span>/өдөр</span>'+innerHtml+'</div></div></a></li>');

                    });
                    $( "#tags" ).autocomplete({
                        source: availableTags
                    });
                    initMap(results);
                }
                });
            }
}
function tour_checked(){
    $("#tags").val('');
    var location = 'Ulaanbaatar, Mongolia'.split(",");
    $.ajax({
        type: "POST",
        url: "search.php",
        data: {not_checked:1, city:location[0], type: "Resort"},
        success: function(data, textStatus, jqXHR)
    {
        $("#hotels").empty();

        var events = data['events'];
        var pages = data['pages'];
        results=[];
        availableTags = [];
        $.each(events, function(idx, obj) {
            var index = idx+1;
            var string = "";
            availableTags.push(obj.name);  
            results.push({lat: obj.latitude, lng:obj.longitude, id: ''+obj.id, avg: ''+obj.rate});
            for ($i=0; $i<obj.stars; $i++){
                string += '<i class="fa fa-star"></i></li>';
            }
            if(asem){ currency = "$ "+obj.rate; }else{ currency = obj.rate+" ₮";}
            if(obj.sold_out == 1){
                innerHtml ='<span class="btn btn-primary small-left sold" id="'+obj.id+'">Sold out</span>';
            }else{
                innerHtml ='<span class="btn btn-primary small-left" id="'+obj.id+'">Өрөө сонгох</span>';
            }
            $("#hotels").append('<li><a asem="0" name="detail" class="booking-item" id="'+obj.id+'"><div class="row"><div class="col-md-3"><div class="booking-item-img-wrap"><img src="'+obj.cover+'" alt="Image Alternative text" title="LHOTEL PORTO BAY SAO PAULO suite lhotel living room" /><div class="booking-item-img-num"><i class="fa fa-picture-o"></i>29</div></div></div><div class="col-md-6"> <div class="flex"><div><h5 class="booking-item-title">'+obj.name+'</h5></div><div><div class="booking-item-rating mrg-left"><ul class="icon-group booking-item-rating-stars">'+string+'</ul></div></div></div><small class="booking-item-address"><i class="fa fa-map-marker"></i>'+ obj.address +'</small><br><br><small class="booking-item-address">'+obj.short_desc+'</small></div><div class="col-md-3"><span class="booking-item-price-from small-hidden" style="margin-bottom:8px">эхлэх үнэ</span><span class="booking-item-price">'+currency+'</span><span>/өдөр</span>'+innerHtml+'</div></div></a></li>');

        });
        $( "#tags" ).autocomplete({
            source: availableTags
        });
        initMap(results);
    }
    });
}
$( "#tags" ).keyup(function(event) {
    var data;
    var keycode = (event.keyCode ? event.keyCode : event.which);
    var text =$('#tags').val();

    if(keycode == 8 && (text.length == 0) && keyup == true){

        var type = "Hotel";
        if(tour){ type = "Resort";}
        checkedValues1 = $(' input[name="stars"]:checkbox:checked').map(function() {return this.value;}).get();
        checkedValues2 = $(' input[name="others"]:checkbox:checked').map(function() {return this.value;}).get();
        checkedValues3 = $(' input[name="entertainment"]:checkbox:checked').map(function() {return this.value;}).get();
        checkedValues4 = $(' input[name="food_drink"]:checkbox:checked').map(function() {return this.value;}).get();
        checkedValues5 = $(' input[name="pool_spa"]:checkbox:checked').map(function() {return this.value;}).get();
        checkedValues6 = $(' input[name="transport"]:checkbox:checked').map(function() {return this.value;}).get();
        checkedValues7 = $(' input[name="miscellaneous"]:checkbox:checked').map(function() {return this.value;}).get();

        values1 = checkedValues1.toString();values2 = checkedValues2.toString();values3 = checkedValues3.toString();
        values4 = checkedValues4.toString();values5 = checkedValues5.toString();values6 = checkedValues6.toString();
        values7 = checkedValues7.toString();

        var location = 'Ulaanbaatar, Mongolia'.split(",");

        if(checkedValues1.length !=0 || checkedValues2.length !=0  || checkedValues3.length !=0 || checkedValues4.length !=0 
                || checkedValues5.length !=0 || checkedValues6.length !=0 || checkedValues7.length !=0){
                    checked(values1, values2, values3, values4, values5, values6, values7, location[0], type);
                }else{
                    not_checked(type);
                }

        keyup = false;
    }
    else{
        if(keycode == '13'){
            if(tour){
                data = {search: $("#tags").val(), city:'Ulaanbaatar', type: "Resort"};
            }
            else{
                data = {search: $("#tags").val(), city:'Ulaanbaatar', type: "Hotel"}; 
            }
            keyup = true;
            $('.spinner').remove();
            $(this).after('<div class="spinner"></div>');
            $.ajax({
                type: "POST",
                url: "search.php",
                data: data,
                success: function(data, textStatus, jqXHR)
            {
                $('.spinner').remove();
                $("#hotels").empty();

                var events = data['events'];
                var pages = data['pages'];

                results=[];
                $.each(events, function(idx, obj) {
                    var index = idx+1;
                    var string = "";
                    results.push({lat: obj.latitude, lng:obj.longitude, id: ''+obj.id, avg: ''+obj.rate});
                    for ($i=0; $i<obj.stars; $i++){
                        string += '<i class="fa fa-star"></i></li>';
                    }
                    if(asem){ currency = "$ "+obj.rate; }else{ currency = obj.rate+" ₮";}
                    if(obj.sold_out == 1){
                        innerHtml ='<span class="btn btn-primary small-left sold" id="'+obj.id+'">Sold out</span>';
                    }else{
                        innerHtml ='<span class="btn btn-primary small-left" id="'+obj.id+'">Өрөө сонгох</span>';
                    }
                    $("#hotels").append('<li><a asem="0" name="detail" class="booking-item" id="'+obj.id+'"><div class="row"><div class="col-md-3"><div class="booking-item-img-wrap"><img src="'+obj.cover+'" alt="Image Alternative text" title="LHOTEL PORTO BAY SAO PAULO suite lhotel living room" /><div class="booking-item-img-num"><i class="fa fa-picture-o"></i>29</div></div></div><div class="col-md-6"> <div class="flex"><div><h5 class="booking-item-title">'+obj.name+'</h5></div><div><div class="booking-item-rating mrg-left"><ul class="icon-group booking-item-rating-stars">'+string+'</ul></div></div></div><small class="booking-item-address"><i class="fa fa-map-marker"></i>'+ obj.address +'</small><br><br><small class="booking-item-address">'+obj.short_desc+'</small></div><div class="col-md-3"><span class="booking-item-price-from small-hidden" style="margin-bottom:8px">эхлэх үнэ</span><span class="booking-item-price">'+currency+'</span><span>/өдөр</span>'+innerHtml+'</div></div></a></li>');

                });
            }
            });
            initMap(results);
        }
    }
});
$("#location").geocomplete();

$("#price-slider").ionRangeSlider({
    min: min,
    max: max,
    type: 'double',
    postfix: "₮",
    prettify: false,
    hasGrid: true,
    onFinish: function (data) {
        var location = 'Ulaanbaatar, Mongolia'.split(",");
        var data;
        if(asem){
            data={price:1, city:location[0], asem:1, type: "Hotel", from: data.fromNumber, to: data.toNumber};
        }else{
            data={price:1, city:location[0], type: "Hotel", from: data.fromNumber, to: data.toNumber};
        }

        $('#full-loader').remove();
        $('#hotels').append('<div id="full-loader"><div id="loading"></div></div>');

        $.ajax({
            type: "POST",
            url: "search.php",
            data: data,
            success: function(data, textStatus, jqXHR)
        {
            $("#hotels").empty();

            var events = data['events'];
            var pages = data['pages'];
            results = [];
            availableTags = [];
            $.each(events, function(idx, obj) {
                var index = idx+1;
                var string = "";
                availableTags.push(obj.name);  
                results.push({lat: obj.latitude, lng:obj.longitude, id: ''+obj.id, avg: ''+obj.rate});
                for ($i=0; $i<obj.stars; $i++){
                    string += '<i class="fa fa-star"></i></li>';
                }
                if(asem){ currency = "$ "+obj.rate; }else{ currency = obj.rate+" ₮";}
                if(obj.sold_out == 1){
                    innerHtml ='<span class="btn btn-primary small-left sold" id="'+obj.id+'">Sold out</span>';
                }else{
                    innerHtml ='<span class="btn btn-primary small-left" id="'+obj.id+'">Өрөө сонгох</span>';
                }
                $("#hotels").append('<li><a asem="0" name="detail" class="booking-item" id="'+obj.id+'"><div class="row"><div class="col-md-3"><div class="booking-item-img-wrap"><img src="'+obj.cover+'" alt="Image Alternative text" title="LHOTEL PORTO BAY SAO PAULO suite lhotel living room" /><div class="booking-item-img-num"><i class="fa fa-picture-o"></i>29</div></div></div><div class="col-md-6"> <div class="flex"><div><h5 class="booking-item-title">'+obj.name+'</h5></div><div><div class="booking-item-rating mrg-left"><ul class="icon-group booking-item-rating-stars">'+string+'</ul></div></div></div><small class="booking-item-address"><i class="fa fa-map-marker"></i>'+ obj.address +'</small><br><br><small class="booking-item-address">'+obj.short_desc+'</small></div><div class="col-md-3"><span class="booking-item-price-from small-hidden" style="margin-bottom:8px">эхлэх үнэ</span><span class="booking-item-price">'+currency+'</span><span>/өдөр</span>'+innerHtml+'</div></div></a></li>');

            });
            $( "#tags" ).autocomplete({
                source: availableTags
            });
            initMap(results);
        }
        });
    }
});
$('#hotels a[name="detail"]').click(function(){
    $('#full-loader').remove();
    $(this).append('<div id="full-loader"><div id="loading"></div></div>');
});

$(window).scroll(function()
        {
            if($(window).scrollTop() == $(document).height() - $(window).height())
{
    if(checkSkip){
        $('div#loadmoreajaxloader').show();
        checkSkip = false;
        loadSkip++;
        checkedValues1 = $(' input[name="stars"]:checkbox:checked').map(function() {return this.value;}).get();
        checkedValues2 = $(' input[name="others"]:checkbox:checked').map(function() {return this.value;}).get();
        checkedValues3 = $(' input[name="entertainment"]:checkbox:checked').map(function() {return this.value;}).get();
        checkedValues4 = $(' input[name="food_drink"]:checkbox:checked').map(function() {return this.value;}).get();
        checkedValues5 = $(' input[name="pool_spa"]:checkbox:checked').map(function() {return this.value;}).get();
        checkedValues6 = $(' input[name="transport"]:checkbox:checked').map(function() {return this.value;}).get();
        checkedValues7 = $(' input[name="miscellaneous"]:checkbox:checked').map(function() {return this.value;}).get();

        values1 = checkedValues1.toString();values2 = checkedValues2.toString();values3 = checkedValues3.toString();
        values4 = checkedValues4.toString();values5 = checkedValues5.toString();values6 = checkedValues6.toString();
        values7 = checkedValues7.toString();

        var location = 'Ulaanbaatar, Mongolia'.split(",");

        var data;
        if(asem){
            data = {skip:loadSkip, filter1:values1, filter2:values2, entertainment:values3, food_drink:values4, pool_spa:values5, transport:values6,
                miscellaneous:values7, city:location[0], asem:1, type: "Hotel"};
        }else{
            data = {skip:loadSkip, filter1:values1, filter2:values2, entertainment:values3, food_drink:values4, pool_spa:values5, transport:values6,
                miscellaneous:values7, city:location[0], type: "Hotel"};
        }
        $.ajax({
            type: "POST",
            url: "loadmore.php",
            data: data,
            success: function(data, textStatus, jqXHR)
        {
            checkSkip = true;
            $('div#loadmoreajaxloader').hide();
            var events = data['events'];
            var pages = data['pages'];

            if(events.length == 0){
                loadSkip--;
            }
            $.each(events, function(idx, obj) {
                var index = idx+1;
                var string = "";
                results.push({lat: obj.latitude, lng:obj.longitude, id: ''+obj.id, avg: ''+obj.rate});
                for ($i=0; $i<obj.stars; $i++){
                    string += '<i class="fa fa-star"></i></li>';
                }
                if(asem){ currency = "$ "+obj.rate; }else{ currency = obj.rate+" ₮";}
                if(obj.sold_out == 1){
                    innerHtml ='<span class="btn btn-primary small-left sold" id="'+obj.id+'">Sold out</span>';
                }else{
                    innerHtml ='<span class="btn btn-primary small-left" id="'+obj.id+'">Өрөө сонгох</span>';
                }
                $("#hotels").append('<li><a asem="0" name="detail" class="booking-item" id="'+obj.id+'"><div class="row"><div class="col-md-3"><div class="booking-item-img-wrap"><img src="'+obj.cover+'" alt="Image Alternative text" title="LHOTEL PORTO BAY SAO PAULO suite lhotel living room" /><div class="booking-item-img-num"><i class="fa fa-picture-o"></i>29</div></div></div><div class="col-md-6"> <div class="flex"><div><h5 class="booking-item-title">'+obj.name+'</h5></div><div><div class="booking-item-rating mrg-left"><ul class="icon-group booking-item-rating-stars">'+string+'</ul></div></div></div><small class="booking-item-address"><i class="fa fa-map-marker"></i>'+ obj.address +'</small><br><br><small class="booking-item-address">'+obj.short_desc+'</small></div><div class="col-md-3"><span class="booking-item-price-from small-hidden" style="margin-bottom:8px">эхлэх үнэ</span><span class="booking-item-price">'+currency+'</span><span>/өдөр</span>'+innerHtml+'</div></div></a></li>');
            });
            initMap(results);
            //alert(data);

        }
        });
    }
}
});
