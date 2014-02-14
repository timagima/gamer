$(document).ready(function(){
    var current = 1;
    var links = [
        ['/about/promo','?page=next-tournament-promo','?page=winner-promo'],
        ['Легендарные Off-line турниры','Ближайшие Off-line турниры','Победители Off-line турниров']
    ];
    function recLinks (array){
        var pageLink = '';
        var pageTitle = '';
        for(var i = 0; i < array.length; i++){
            for(var j = 0; j < array[i].length; j++){
                if(current-1 == j && i == 0)
                    pageLink = array[i][j];
                if(current-1 == j && i == 1)
                    pageTitle = array[i][j];
            }
        }
        history.pushState(null,null,pageLink);
        $('title').text(pageTitle);
    }

    function sendAjax(current){
        $.ajax({
            type: 'POST',
            url: document.location.href,
            data: {"id" : current},
            success: function(data){
                $('#main').html(data);}
        });
    }
    if(current == 1 ){
        $('#prev-btn').css('backgroundPosition', '0 100%');
    }
    if(current == 2){
        $('#prev-btn').css('backgroundPosition', '0 0');
        $('#next-btn').css('backgroundPosition','100% 100%');
    }
    if(current == 3){
        $('#next-btn').css('backgroundPosition','100% 0');
        $('#prev-btn').css('backgroundPosition', '0 0');
    }

    $('#select').find('a').on('click',function(){
        var direction = $(this).attr('id');
        if(direction == 'next-btn') ++current;
        else --current;
        recLinks (links);
        sendAjax(current);
    });

    window.onpopstate= function(){
        if(history.location.pathname == '/about/promo'&&document.location.search == '')
            --current;
        else if(history.location.search == '?page=next-tournament-promo'&&current !=1)
            --current;
        else
            ++current;
        sendAjax(current);
    };
});
