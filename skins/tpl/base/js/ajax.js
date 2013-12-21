$(document).ready(function(){
    birthday();
    $("#edit-age-month").change(function(){birthdayActualDate();});
    $("#edit-age-year").change(function(){birthdayActualDate();});
})

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
    function GetGameLevel(){
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

    document.getElementById("game").onblur = GetGameLevel;



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


function editUserData(){
    var firstName  = $("#edit-first-name").val();
    var lastName   = $("#edit-last-name").val();
    var patronymic = $("#edit-patronymic").val();
    var sex        = $("input[name=edit-sex]:checked").val();
    var aboutMe    = $("#edit-about-me").val();
    var day        = $("#edit-age-day").val();
    var month      = $("#edit-age-month").val();
    var year       = $("#edit-age-year").val();
    day   = day.length == 1 ? "0"+  day : day;
    month = month.length == 1 ? "0"+  month : month;
    var birthday = day + "." + month + "."+ year;

    $.ajax({
        type: 'POST',
        url: document.location.href,
        dataType: 'html',
        data: {'ajax-query': 'true', 'type-class': 'model', 'method':'MainEditUserData', 'first-name': firstName, 'last-name': lastName, 'patronymic': patronymic, 'sex': sex, 'about-me': aboutMe, 'birthday': birthday},
        beforeSend: function(){
            $('#send').before('<img id="ajax-img-loader" src="/skins/img/ajax/loader-page.gif">');
        },
        success: function(data){
            $("#ajax-img-loader").remove();
            $('#info-ajax-modal').arcticmodal();
            setTimeout(closeModalAll, 1000);
            location.reload();
        }
    });
}


function birthdayActualDate(){
    var month              = parseInt($("#edit-age-month").val());
    var year               = parseInt($("#edit-age-year").val());
    var day                = parseInt($("#edit-age-day").val());
    var dataDay            = '';
    var integerDayForYear  = (year - 1948) / 4;
    var integerDayForMonth = month / 2;
    if(month >= 8)
        var resDay = ((integerDayForMonth + "").indexOf(".") > 0) ? 30 : 31;
    else if(month == 2)
        var resDay = ((integerDayForYear + "").indexOf(".") > 0) ? 28 : 29;
    else
        var resDay = ((integerDayForMonth + "").indexOf(".") > 0) ? 31 : 30;

    for(i = 1; i <= resDay; i++){
        var selected = (day == i) ? 'selected' : '';
        dataDay += '<option value="'+ i +'" '+ selected +' >' + i + '</option>';
    }
    $("#edit-age-day").html('').append(dataDay);
}

function birthday(){
    var dataYear = '';
    var dataDay = '';
    for(i = 2005; i >= 1948; i--){
        dataYear += '<option value="'+ i +'">' + i + '</option>';
    }

    for(i = 1; i <= 31; i++){
        dataDay += '<option value="'+ i +'">' + i + '</option>';
    }
    $("#edit-age-year").append(dataYear);
    $("#edit-age-day").append(dataDay);
}

function confirmSaveInfo(){
    $("#box-modal-main-save").arcticmodal();
}
