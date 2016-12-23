$(function(){
    $('#example1').editableRecord({
        idName: 'Id',
        saveUrl:'./example.json',
        deleteUrl: './example.json',
        detailButtonClicked: function(){
            alert("clicked");
        }
    });
    $('#example2').editableRecord({
        idName: 'Id',
        saveUrl:'./example.json',
        deleteUrl: './example.json',
        detailButtonClicked: function(){
            alert("clicked");
        }
    });

});