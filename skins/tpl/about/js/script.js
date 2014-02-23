var sliderNext = {
    init: function(){
        $("body").on("click", ".slider-action", function(){
            var page = $(this).attr("page");
            sliderNext.sendAjax(page);
            history.pushState(null, null, "?page=" + page);
        })
        sliderNext.backNextPage();
    },
    sendAjax: function(page){
        $.ajax({
            type: 'POST',
            url: document.location.href,
            data: {"page": page},
            success: function(data){
                $('#main').html(data).hide().fadeIn(250);
            }
        });
    },
    backNextPage: function(){
        window.onpopstate = function(){
            debugger;
            var arrPath = history.location.href.split("=");
            sliderNext.sendAjax(arrPath[1]);
        }
    }

}
/*
var links = ['Легендарные Off-line турниры','Ближайшие Off-line турниры','Победители Off-line турниров'];
*/
$(document).ready(function(){
    sliderNext.init();
});
