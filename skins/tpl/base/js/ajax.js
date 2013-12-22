$(function(){

    $('.hints').poshytip({
        className: 'tip-black',
        showOn: 'focus',
        alignTo: 'target',
        alignX: 'right',
        alignY: 'center',
        offsetX: 1,
        offsetY: 5,
        showTimeout: 100
    });

    $('#city').autocomplete({
        serviceUrl: document.location.href,
        zIndex: 99999, // z-index списка
        type: 'POST',
        params: {
            'ajax-query': 'true',
            'type-class':'model',
            'method': 'GetCities',
            'limit': '10'
        },
        dataType: 'html',
        deferRequestBy: 200
    });

    //
    $('#game').autocomplete({
        serviceUrl: document.location.href,
        zIndex: 99999, // z-index списка
        type: 'POST',
        params: {
            'ajax-query': 'true',
            'type-class':'model',
            'method': 'GetGame',
            'limit': '10'
        },
        dataType: 'html',
        deferRequestBy: 200
    });

    //Получение уровня сложности выбранной игры
    function getGameLevel(){
        var game = document.getElementById("game").value;
        $.ajax({
            type: 'POST',
            url: document.location.href,
            dataType: 'html',
            data: {'ajax-query': 'true', 'type-class': 'model', 'method':'GetGameLevel', 'game': game},
            success: function(data){
                var level = $.parseJSON(data);
                var selectHtml = "";
                for(var i in level){
                    if(i==0){
                        var value = level[i].split('$');
                        selectHtml += "<option selected='selected' value='" + value[1] + "'>" + value[0] + "</option>";
                    }else{
                        var value = level[i].split('$');
                        selectHtml += "<option value='" + value[1] + "'>" + value[0] + "</option>";
                    }
                }
                $("#game-level").html(selectHtml);
            }
        });

    }

    document.getElementById("game").onblur = getGameLevel;



    // Добавление пройденной игры в БД
    function addCompletedGames(){
        var game            = $("#game").val();
        var gameLevel       = $("#game-level").val();
        var gameDescription = $("#game-description").val();
        alert(game+" "+gameLevel+" "+gameDescription);

        $.ajax({
            type: 'POST',
            url: document.location.href,
            dataType: 'html',
            data: {'ajax-query': 'true', 'type-class': 'model', 'method':'AddCompletedGame', 'game': game, 'game-level': gameLevel, 'game-description': gameDescription},
            beforeSend: function(){
                $('#send').before('<img id="ajax-img-loader" src="/skins/img/ajax/loader-page.gif">');
            },
            success: function(data){
                debugger;
                var data = $.parseJSON(data)
                if ( data.game_success == true ) {
                    $('.tooltip#game').removeClass('error')
                    location.reload();
                } else {
                    $('.tooltip#game').addClass('error').html('Вы уже добавили данную игру.')
                    return false;
                }
            }
        });
    }

    document.getElementById("send-completed-game").onclick = addCompletedGames;

    // ВАЛИДАЦИЯ
    // $('.btn-login, input, textarea, div').click(function(){
    $('.btn-login').click(function(){
        if (!_validation()) {
            return false;
        }
    })

});