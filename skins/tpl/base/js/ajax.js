$(function () {
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

    //
    $('#game').autocomplete({
        serviceUrl: document.location.href,
        zIndex: 99999, // z-index списка
        type: 'POST',
        params: {
            'ajax-query': 'true',
            'type-class': 'model',
            'method': 'GetGame',
            'limit': '10'
        },
        dataType: 'html',
        deferRequestBy: 200
    });

    //var gameAdded = "notAdd";
    //Получение уровня сложности выбранной игры
    function getGameLevel() {
        var game = $("#game").val();
        if(game!=""){
            $.ajax({
                type: 'POST',
                url: document.location.href,
                dataType: 'html',
                data: {'ajax-query': 'true', 'type-class': 'model', 'method': 'GetGameLevel', 'game': game},
                success: function (data) {
                    var level = $.parseJSON(data);
                    /*var checkGame = level.pop();
                    if(checkGame === "true"){
                        var gameAdded = "add";
                        $('.tooltip#game').addClass('error').html('Вы уже добавили эту игру.');
                        notFormSend = true;
                    }else{
                        $('.tooltip#game').removeClass('error');
                    }*/
                    if(level[1].user_rating == false){

                        //$(".val").val(level[1].rating / level[1].suffrage_count);
                        //$(".votes").val(level[1].suffrage_count);
                        var rating = parseFloat(level[1].rating) / parseInt(level[1].suffrage_count);
                        var votes = parseInt(level[1].suffrage_count);
                        $("#game-rating-parent").html(
                            '<div class="rating">' +
                                '<input type="hidden" class="val" value="'+ rating +'"/>' +
                                '<input type="hidden" class="votes" value="'+ votes +'"/>' +
                            '</div>' +
                            '<div style="float: right; margin: -52px -235px 0px 0px;" class="b-validation">' +
                                '<div class="tooltip" id="game-rating" style="margin-left: 28px;"></div>' +
                            '</div>'
                        );
                        gameRatingView = false;
                        ratingValue = $('div.rating').rating({
                            fx: 'float',
                            image: '/skins/img/stars-rating.png',
                            loader: '/skins/img/ajax-loader-rating.gif',
                            width: 32,
                            stars: 5,
                            minimal: 0.1
                        });


                    } else {
                        var rating = parseInt(level[1].rating) / parseInt(level[1].suffrage_count);
                        var votes = parseInt(level[1].suffrage_count);
                        gameRatingView = true;
                        $("#game-rating-parent").html(
                            '<div class="rating">' +
                                '<input type="hidden" class="val" value="'+ rating +'"/>' +
                                '<input type="hidden" class="votes" value="'+ votes +'"/>' +
                            '</div>' +
                                '<div style="float: right; margin: -52px -235px 0px 0px;" class="b-validation">' +
                                '<div class="tooltip" id="game-rating" style="margin-left: 28px;"></div>' +
                            '</div>'
                        );

                        ratingValue = $('div.rating').rating({
                            fx: 'float',
                            image: '/skins/img/stars-rating.png',
                            loader: '/skins/img/ajax-loader-rating.gif',
                            width: 32,
                            stars: 5,
                            readOnly: true,
                            minimal: 0.1
                        })
                    }

                    if(Object.prototype.toString.call( level ) === '[object Array]') {
                        if (level[0].length == 0) {
                            var selectHtml = '<select id="game-level" name ="game-level" class="styled" style="width: 200px; height: 15px;">';
                            selectHtml += "<option selected='selected' value='false'>Сначала выберите игру</option>";
                            selectHtml += '</select>';
                            $("#game-level-parent").html(selectHtml);
                            //$('select.styled').customSelect();
                        } else {
                            var selectHtml = '<select id="game-level" name ="game-level" class="styled" style="width: 200px; height: 15px;">';
                            for (var i in level[0]) {
                                if (i == 0) {
                                    var value = level[0][i].split('$');
                                    selectHtml += "<option selected='selected' value='" + value[1] + "$" + value[2] + "'>" + value[0] + "</option>";
                                } else {
                                    var value = level[0][i].split('$');
                                    selectHtml += "<option value='" + value[1] + "$" + value[2] + "'>" + value[0] + "</option>";
                                }
                                //$('select.styled').customSelect();
                            }
                            selectHtml += '</select>';
                            $("#game-level-parent").html(selectHtml);
                            //$('select.styled').customSelect();
                        }
                     }
                }
            });
        }
    }

    //document.getElementById("game").onblur = getGameLevel;
    //document.getElementById("game").onkeydown = getGameLevel;
    $("#game").keyup(function(e) {
        getGameLevel();
    });
    $(".autocomplete-suggestions").bind("click.namespace", function () {
        getGameLevel();
    });
    $(".autocomplete-suggestions").trigger('click.namespace');
    $(".autocomplete-suggestions").bind("keyup.namespace", function () {
        getGameLevel();
    });
    $(".autocomplete-suggestions").trigger('keyup.namespace');

    // Добавление пройденной игры в БД
    var gameRatingView = false;
    function addCompletedGames() {
        if(ratingValue !== undefined){
            var gameRating = (ratingValue[0].textContent.match(/[0-9]{1,2}$/) === null) ? false : parseFloat(ratingValue[0].textContent.match(/[0-9]\.[0-9]$/)[0]);
        }
        var game = $("#game").val();
        var gameLevel = $("#game-level").val();
        var gameDescription = $.trim($("#game-description").val());
        var gameStart = $("#game-start-date").val();
        var gameEnd = $("#game-end-date").val();
        var questQount = parseInt($.trim($("#quest-count").val()));
        var visibleQuestQount = $("#quest-count").css("visibility");
        var idGamePassing = parseInt($("#game-passing").val());
        var notFormSendGameStart;
        var notFormSendGameEnd;
        var notFormSendGameDescription;
        var notFormGameQuest;
        var notFormGameRating;
        //debugger;

        //if (gameAdded === "add") {
          //  $('.tooltip#game').addClass('error').html('Вы уже добавили эту игру.');
        //} //else if(gameAdded === "notAdd"){
            //$('.tooltip#game').removeClass('error');
        //}
        //if (game == "") {
          //  $('.tooltip#game').addClass('error').html('Заполните поле');
          //  notFormSend = true;
        //} //else{
           // $('.tooltip#game').removeClass('error');
        //}

        if (gameDescription == "") {
            $('#description').addClass('error').html('Заполните поле');
            notFormSendGameDescription = true;
        } else {
            $('#description').removeClass('error');
            notFormSendGameDescription = false;
        }

        if (gameStart === "дд-мм-гггг") {
            $('#game-start').addClass('error').html('Заполните поле');
            notFormSendGameStart = true;
        } else {
            $('#game-start').removeClass('error');
            notFormSendGameStart = false;
        }

        if (gameEnd === "дд-мм-гггг") {
            $('#game-end').addClass('error').html('Заполните поле');
            notFormSendGameEnd = true;
        } else {
            $('#game-end').removeClass('error');
            notFormSendGameEnd = false;
        }
        if (isNaN(questQount) && visibleQuestQount == "visible") {
            $('#game-quest').addClass('error').html('Заполните поле');
            notFormGameQuest = true;
        } else {
            $('#game-quest').removeClass('error');
            notFormGameQuest = false;
        }
        if (gameRatingView === false) {
            if(gameRating === false){
                $('#game-rating').addClass('error').html('Проголосуйте');
                notFormGameRating = true;
            }
        } else {
            $('#game-rating').removeClass('error');
            notFormGameRating = false;
        }

        if (notFormSendGameDescription  || notFormSendGameStart || notFormSendGameEnd || notFormGameQuest || notFormGameRating)
            return false;
        $.ajax({
            type: 'POST',
            url: document.location.href,
            dataType: 'html',
            data: {'ajax-query': 'true', 'type-class': 'model', 'method': 'AddCompletedGame', 'game': game, 'game-level': gameLevel, 'game-description': gameDescription, 'game-start-date': gameStart, 'game-end-date': gameEnd, 'quest-qount': questQount, 'game-passing': idGamePassing, 'game-rating': gameRating},
            beforeSend: function () {
                $('#send').before('<img id="ajax-img-loader" src="/skins/img/ajax/loader-page.gif">');
            },
            success: function (data) {
                if (data == "addGame") {
                    $('.tooltip#game').removeClass('error')
                    location.reload();
                } else if (data == "isGame") {
                    $('.tooltip#game').addClass('error').html('Вы уже добавили эту игру!')
                    return false;
                } else if (data == "notGame") {
                    $('.tooltip#game').addClass('error').html('Такой игры нет в БД.')
                    return false;
                }
            }
        });
    }

    //document.getElementById("send-completed-game").onclick = addCompletedGames;
    $("#send-completed-game").click(function(){addCompletedGames()});

    /*
    // Изменение пройденной игры в БД
    function updateAddedGame() {
        var gameId = $("#game-id").val();
        var levelId = $("#level-id").val();
        var gameDescription = $.trim($("#game-description").val());
        var gameStart = $("#game-start-date").val();
        var gameEnd = $("#game-end-date").val();
        var questQount = parseInt($.trim($("#quest-count").val()));
        var visibleQuestQount = $("#quest-count").css("visibility");
        var idGamePassing = parseInt($("#game-passing").val());
        var notFormSendGameStart;
        var notFormSendGameEnd;
        var notFormSendGameDescription;
        var notFormGameQuest;

        if (gameDescription == "") {
            $('#description').addClass('error').html('Заполните поле');
            notFormSendGameDescription = true;
        } else {
            $('#description').removeClass('error');
            notFormSendGameDescription = false;
        }

        if (gameStart === "дд-мм-гггг") {
            $('#game-start').addClass('error').html('Заполните поле');
            notFormSendGameStart = true;
        } else {
            $('#game-start').removeClass('error');
            notFormSendGameStart = false;
        }

        if (gameEnd === "дд-мм-гггг") {
            $('#game-end').addClass('error').html('Заполните поле');
            notFormSendGameEnd = true;
        } else {
            $('#game-end').removeClass('error');
            notFormSendGameEnd = false;
        }
        if (isNaN(questQount) && visibleQuestQount == "visible") {
            $('#game-quest').addClass('error').html('Заполните поле');
            notFormGameQuest = true;
        } else {
            $('#game-quest').removeClass('error');
            notFormGameQuest = false;
        }

        if (notFormSendGameDescription  || notFormSendGameStart || notFormSendGameEnd || notFormGameQuest)
            return false;
        $.ajax({
            type: 'POST',
            url: document.location.href,
            dataType: 'html',
            data: {'ajax-query': 'true', 'type-class': 'model', 'method': 'UpdateAddedGame', 'game-id':gameId, 'level-id': levelId, 'game-description': gameDescription, 'game-start-date': gameStart, 'game-end-date': gameEnd, 'quest-qount': questQount, 'game-passing': idGamePassing},
            beforeSend: function () {
                $('#send').before('<img id="ajax-img-loader" src="/skins/img/ajax/loader-page.gif">');
            },
            success: function (data) {
                if (data == "GameUpdated") {
                    $(".game-edit-form").html("<h2>Данные успешно изменены.</h2>");
                }
            }
        });
    }

    //document.getElementById("update-completed-game").onclick = updateAddedGame;
    $("#update-completed-game").onclick(updateAddedGame());*/

    // ВАЛИДАЦИЯ
    // $('.btn-login, input, textarea, div').click(function(){
    $('.btn-login').click(function () {
        if (!_validation()) {
            return false;
        }
    })

    //Функция установки метки об отсутствии даты начала прохождения игры
    $(".disable-date").click(function () {
        var idName = "#game-" + $(this).val();
        var input = $(idName).val();
        (input == "Не помню") ? $(idName).val("дд-мм-гггг").removeAttr("disabled") : $(idName).val("Не помню").attr("disabled", "disabled");
    })

    //Функция скрывает и показывает инпут с для ввода количества пройденный квестов
    $("#not-quest-count").click(function () {
        var visibleQuestQount = $("#quest-count").css("visibility");
        ( visibleQuestQount == "visible" ) ? $("#quest-count").css("visibility", "hidden") : $("#quest-count").css("visibility", "visible");
    })

    var ratingValue;
    ratingValue = $('div.rating').rating({
        fx: 'float',
        image: '/skins/img/stars-rating.png',
        loader: '/skins/img/ajax-loader-rating.gif',
        width: 32,
        stars: 5,
        readOnly: true,
        minimal: 0.1
    })

});

/* Javascript Calendar
 (c) kdg http://HTMLWEB.RU/java/example/calendar_kdg.php */

var _Calendar = function () {
    var _Calendar = {
        now: null,
        sccd: null,
        sccm: null,
        sccy: null,
        ccm: null,
        ccy: null,
        updobj: null,
        mn: new Array('Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентрябрь', 'Октябрь', 'Ноябрь', 'Декабрь'),
        mnn: new Array('31', '28', '31', '30', '31', '30', '31', '31', '30', '31', '30', '31'),
        mnl: new Array('31', '29', '31', '30', '31', '30', '31', '31', '30', '31', '30', '31'),
        calvalarr: new Array(42),

        $: function (objID) {
            if (document.getElementById) {
                return document.getElementById(objID);
            }
            else if (document.all) {
                return document.all[objID];
            }
            else if (document.layers) {
                return document.layers[objID];
            }
        },

        checkClick: function (e) {
            e ? evt = e : evt = event;
            CSE = evt.target ? evt.target : evt.srcElement;
            if (_Calendar.$('fc'))
                if (!_Calendar.isChild(CSE, _Calendar.$('fc')))
                    _Calendar.$('fc').style.display = 'none';
        },

        isChild: function (s, d) {
            while (s) {
                if (s == d)
                    return true;
                s = s.parentNode;
            }
            return false;
        },

        Left: function (obj) {
            var curleft = 0;
            if (obj.offsetParent) {
                while (obj.offsetParent) {
                    curleft += obj.offsetLeft
                    obj = obj.offsetParent;
                }
            }
            else if (obj.x)
                curleft += obj.x;
            return curleft;
        },

        Top: function (obj) {
            var curtop = 0;
            if (obj.offsetParent) {
                while (obj.offsetParent) {
                    curtop += obj.offsetTop
                    obj = obj.offsetParent;
                }
            }
            else if (obj.y)
                curtop += obj.y;
            return curtop;
        },


        lcs: function (ielem) {
            _Calendar.updobj = ielem;
            _Calendar.$('fc').style.left = _Calendar.Left(ielem);
            _Calendar.$('fc').style.top = _Calendar.Top(ielem) + ielem.offsetHeight;
            _Calendar.$('fc').style.display = '';

            // First check date is valid
            curdt = ielem.value;
            curdtarr = curdt.split('-');
            isdt = true;
            for (var k = 0; k < curdtarr.length; k++) {
                if (isNaN(curdtarr[k]))
                    isdt = false;
            }
            if (isdt & (curdtarr.length == 3)) {
                _Calendar.ccm = curdtarr[1] - 1;
                _Calendar.ccy = curdtarr[2];
                _Calendar.prepcalendar(curdtarr[0], curdtarr[1] - 1, curdtarr[2]);
            }

        },

        evtTgt: function (e) {
            var el;
            if (e.target)el = e.target;
            else if (e.srcElement)el = e.srcElement;
            if (el.nodeType == 3)el = el.parentNode; // defeat Safari bug
            return el;
        },
        EvtObj: function (e) {
            if (!e)e = window.event;
            return e;
        },

        cs_over: function (e) {
            _Calendar.evtTgt(_Calendar.EvtObj(e)).style.background = '#FFEBCC';
        },

        cs_out: function (e) {
            _Calendar.evtTgt(_Calendar.EvtObj(e)).style.background = '#FFFFFF';
        },

        cs_click: function (e) {
            _Calendar.updobj.value = _Calendar.calvalarr[_Calendar.evtTgt(_Calendar.EvtObj(e)).id.substring(2, _Calendar.evtTgt(_Calendar.EvtObj(e)).id.length)];
            _Calendar.$('fc').style.display = 'none';
        },

        f_cps: function (obj) {
            obj.style.background = '#FFFFFF';
            obj.style.font = '10px Arial';
            obj.style.color = '#333333';
            obj.style.textAlign = 'center';
            obj.style.textDecoration = 'none';
            obj.style.border = '1px solid #606060';
            obj.style.cursor = 'pointer';
        },

        prepcalendar: function (hd, cm, cy) {
            _Calendar.now = new Date();
            sd = _Calendar.now.getDate();
            md = Math.max(cy, _Calendar.now.getFullYear());
            td = new Date();
            td.setDate(1);
            td.setFullYear(cy);
            td.setMonth(cm);
            cd = td.getDay(); // день недели
            if (cd == 0)cd = 6; else cd--;

            vd = '';
            for (var m = 0; m < 12; m++) vd = vd + '<option value="' + m + '"' + (m == cm ? ' selected' : '') + '>' + _Calendar.mn[m] + '</option>'; // цикл по месяцам

            d = '';
            for (var y = cy - 40; y <= md; y++)   d = d + '<option value="' + y + '"' + (y == cy ? ' selected' : '') + '>' + y + '</option>'; // цикл по годам
            _Calendar.$('mns').innerHTML = ' <select onChange="_Calendar.cmonth(this);">' + vd + '</select><select onChange="_Calendar.cyear(this);">' + d + '</select>'; // текущий месяц и год

            marr = ((cy % 4) == 0) ? _Calendar.mnl : _Calendar.mnn;

            for (var d = 1; d <= 42; d++)// цикл по всем ячейкам таблицы
            {
                d = parseInt(d);
                vd = _Calendar.$('cv' + d);
                _Calendar.f_cps(vd);
                if ((d >= (cd - (-1))) && (d <= cd - (-marr[cm]))) {
                    dd = new Date(d - cd, cm, cy);
                    if (d == 36)_Calendar.$("last_table_tr").style.display = "";
                    vd.onmouseover = _Calendar.cs_over;
                    vd.onmouseout = _Calendar.cs_out;
                    vd.onclick = _Calendar.cs_click;

                    if (_Calendar.sccm == cm && _Calendar.sccd == (d - cd) && _Calendar.sccy == cy)
                        vd.style.color = '#FF9900'; // сегодня
                    /*else if(dd.getDay()==6||dd.getDay()==0)
                     vd.style.color='#FF0000'; // выходной*/

                    vd.innerHTML = d - cd;

                    _Calendar.calvalarr[d] = _Calendar.addnull(d - cd, cm - (-1), cy);
                }
                else {
                    if (d == 36) {
                        _Calendar.$("last_table_tr").style.display = "none";
                        break;
                    }
                    vd.innerHTML = '&nbsp;';
                    vd.onmouseover = null;
                    vd.onmouseout = null;
                    vd.onclick = null;
                    vd.style.cursor = 'default';
                }
            }
        },

        caddm: function () {
            marr = ((_Calendar.ccy % 4) == 0) ? _Calendar.mnl : _Calendar.mnn;

            _Calendar.ccm += 1;
            if (_Calendar.ccm >= 12) {
                _Calendar.ccm = 0;
                _Calendar.ccy++;
            }
            _Calendar.prepcalendar('', _Calendar.ccm, _Calendar.ccy);
        },

        csubm: function () {
            marr = ((_Calendar.ccy % 4) == 0) ? _Calendar.mnl : _Calendar.mnn;

            _Calendar.ccm -= 1;
            if (_Calendar.ccm < 0) {
                _Calendar.ccm = 11;
                _Calendar.ccy--;
            }
            _Calendar.prepcalendar('', _Calendar.ccm, _Calendar.ccy);
        },

        cmonth: function (t) {
            _Calendar.ccm = t.options[t.selectedIndex].value;
            _Calendar.prepcalendar('', _Calendar.ccm, _Calendar.ccy);
        },

        cyear: function (t) {
            _Calendar.ccy = t.options[t.selectedIndex].value;
            _Calendar.prepcalendar('', _Calendar.ccm, _Calendar.ccy);
        },

        today: function () {
            _Calendar.updobj.value = _Calendar.addnull(_Calendar.now.getDate(), _Calendar.now.getMonth() + 1, _Calendar.now.getFullYear());
            _Calendar.$('fc').style.display = 'none';
            _Calendar.prepcalendar('', _Calendar.sccm, _Calendar.sccy);
        },

        addnull: function (d, m, y) {
            var d0 = '', m0 = '';
            if (d < 10)d0 = '0';
            if (m < 10)m0 = '0';

            return '' + d0 + d + '-' + m0 + m + '-' + y;
        }
    }

    _Calendar.now = n = new Date;
    _Calendar.sccd = n.getDate();
    _Calendar.sccm = n.getMonth();
    _Calendar.sccy = n.getFullYear();
    _Calendar.ccm = n.getMonth();
    _Calendar.ccy = n.getFullYear();


    var table;
    var userAgent = window.navigator.userAgent;
    if (userAgent.indexOf('Chrome') >= 0) {
        var table = '<table id="fc" style="position:fixed;top:296px;left:822px; z-index:99999; border-collapse:collapse; background:#FFFFFF; border:  2px solid #1abc9c; display:none; -moz-user-select:none; -khtml-user-select:none; user-select:none;" cellpadding=2>';
    }
    if (userAgent.indexOf('Opera') >= 0) {
        var table = '<table id="fc" style="position:fixed;top:290px;left:800px; z-index:99999; border-collapse:collapse; background:#FFFFFF; border:  2px solid #1abc9c; display:none; -moz-user-select:none; -khtml-user-select:none; user-select:none;" cellpadding=2>';
    }
    if (userAgent.indexOf('Firefox') >= 0) {
        var table = '<table id="fc" style="position:fixed;top:270px;left:813px; z-index:99999; border-collapse:collapse; background:#FFFFFF; border:  2px solid #1abc9c; display:none; -moz-user-select:none; -khtml-user-select:none; user-select:none;" cellpadding=2>';
    }
    if (userAgent.indexOf('MSIE') >= 0) {
        var table = '<table id="fc" style="position:fixed;top:270px;left:820px; z-index:99999; border-collapse:collapse; background:#FFFFFF; border:  2px solid #1abc9c; display:none; -moz-user-select:none; -khtml-user-select:none; user-select:none;" cellpadding=2>';
    }
    document.write(table);
    document.write('<tr style="font:bold 13px Arial"><td style="cursor:pointer;font-size:15px" onclick="_Calendar.csubm()">&laquo;</td><td colspan="5" id="mns" align="center"></td><td align="right" style="cursor:pointer;font-size:15px" onclick="_Calendar.caddm()">&raquo;</td></tr>');
    document.write('<tr style="background:#1abc9c;font:12px Arial;color:#FFFFFF"><td align=center>Пн</td><td align=center>Вт</td><td align=center>Ср</td><td align=center>Чт</td><td align=center>Пт</td><td align=center>Сб</td><td align=center>Вс</td></tr>');
    for (var kk = 1; kk <= 6; kk++) {
        //document.write('<tr>');
        if (kk == 6)
            document.write('<tr id="last_table_tr">')
        else
            document.write('<tr>');
        for (var tt = 1; tt <= 7; tt++) {
            num = 7 * (kk - 1) - (-tt);
            document.write('<td id="cv' + num + '" style="width:18px;height:18px">&nbsp;</td>');
        }
        document.write('</tr>');
    }
    document.write('<tr><td colspan="7" align="center" style="cursor:pointer;font:13px Arial;background:#34495e; color: #ffffff" onclick="_Calendar.today()">Сегодня: ' + _Calendar.addnull(_Calendar.sccd, _Calendar.sccm + 1, _Calendar.sccy) + '</td></tr>');
    document.write('</table>');

    document.all ? document.attachEvent('onclick', _Calendar.checkClick) : document.addEventListener('click', _Calendar.checkClick, false);

    _Calendar.prepcalendar('', _Calendar.ccm, _Calendar.ccy);

    return _Calendar;
}();
