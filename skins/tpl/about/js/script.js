var current = 1;
var pageLink = '';
var pageTitle = '';
var links = [
    ['?page=legendary-tournament','?page=next-tournament','?page=winner'],
    ['Легендарные Off-line турниры','Ближайшие Off-line турниры','Победители Off-line турниров']
];
function changeLinks(array){
    for(var i = 0; i < array.length; i++){
        for(var j = 0; j < array[i].length; j++){
            if(current-1 == j && i == 0)
                pageLink = array[i][j];
            if(current-1 == j && i == 1)
                pageTitle = array[i][j];
        }
    }
}
function sendAjax(){
    $.ajax({
        type: 'POST',
        url: document.location.href,
        data: {"id": current},
        success: function(data){
            $('#main').html(data).hide().fadeIn(250);
            changeBtn(current);
        }
    });
}
function changeBtn(current){
    if(current == 1){
        $('#prev-btn').css('backgroundPosition', '0 100%').attr('onclick','');
    }
    if(current == 2){
        $('#prev-btn').css('backgroundPosition', '0 0').attr('onclick','ajaxStart(this)');
        $('#next-btn').css('backgroundPosition','100% 100%').attr('onclick','ajaxStart(this)');
    }
    if(current == 3){
        $('#next-btn').css('backgroundPosition','100% 0').attr('onclick','');
        $('#prev-btn').css('backgroundPosition', '0 0');
    }
}

function ajaxStart(e){
    dirrection = e.getAttribute('id');
    if(dirrection == 'next-btn')
        ++current;
    else
        --current;
    changeLinks(links);
    sendAjax(current);
    $('title').text(pageTitle);
    history.pushState(null,null,pageLink);
}
$(document).ready(function(){
    var state = history.location;
    changeBtn(current);
    window.onpopstate = function(){
        if(state.pathname == '/about/promo' && state.search == '?page=legendary-tournament')
            --current;
        else if(state.search == '?page=next-tournament' && current !=1)
            --current;
        else
            ++current;
        sendAjax(current);
    }
});
