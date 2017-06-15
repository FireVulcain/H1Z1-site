$(document).ready(function(){
    $("#load_more_button").on('click',function () {
        $.ajax({
            type: "GET",
            url : "get_data.php",
            data: {
                'offset' : 0,
                'limit' : 1
            },
            success: function(data){
                $('.trois_articles').append(data);
            }
        });
});
});