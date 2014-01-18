<script type="text/javascript" src="/skins/js/validation.js"></script>
<style type="text/css">
    .b-validation .tooltip {
        display: block;
        visibility: hidden;
        z-index: 10;
        padding: 5px;
        line-height: 25px;

        position: absolute;
        color: #111;
        border: 1px solid #DCA;
        background: #FEFFD6;

        border-radius: 4px;
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        -moz-box-shadow: 5px 5px 8px #CCC;
        -webkit-box-shadow: 5px 5px 8px #CCC;

        width: 200px;
        z-index: 99;
        color: #FFFFFF;
        min-height: 25px;
        min-width: 200px;
    }

    .b-validation .error {
        visibility: visible;
    }

    .b-validation .tooltip {
        position: relative;
        background: #1ABC9C;
        border: 4px solid #2C3E50;
    }

    .b-validation .tooltip:after, .b-validation .tooltip:before {
        right: 100%;
        top: 50%;
        border: solid transparent;
        content: " ";
        height: 0;
        width: 0;
        position: absolute;
        pointer-events: none;
    }

    .b-validation .tooltip:after {
        border-color: rgba(26, 188, 156, 0);
        border-right-color: #1ABC9C;
        border-width: 10px;
        margin-top: -10px;
    }

    .b-validation .tooltip:before {
        border-color: rgba(44, 62, 80, 0);
        border-right-color: #2C3E50;
        border-width: 16px;
        margin-top: -16px;
    }
</style>


<h2>Пройденные игры</h2>
<div id="featured-section" style="position: relative; z-index: 10;" class="FL">
    <table>
        <tr>
            <td style="vertical-align: top;">
                <div id="content" style="width: 668px;">

                    <div id="content-profile">

                        <div class='left'>

                            <!-- Форма добавления пройденных игр Добавить пройденную игру<br>-->
                            <div>
                                <a data-reveal-id="edit-main-data" href="javascript:showModal('box-modal-data-gamer')"
                                   data-animation="fade" style="cursor: pointer; text-decoration: none;  color:#507fb6">Добавить
                                    пройденную игру</a>
                            </div>
                            <!-- таблица с пройденными играми пользователя здесь таблица с пройденными играми пользователя<br>-->
                            <?php
                            foreach ($data['user-completed-games'] as $arrayGame) {?>
                                <div class="game-wrap" style="border: 1px solid #1abc9c; margin: 10px; padding: 10px">
                                    <div class="game-head">
                                        <h4><img src="/storage<?= $arrayGame['source_img_s'] ?>"><?= $arrayGame['game'] ?> жанр: <?= $arrayGame['genre'] ?> posted: <?= date("d.m.Y", $arrayGame['post_date']) ?><h4>
                                    </div>
                                    <div class="game-desc">
                                        <p>
                                            <?= $arrayGame['about_game'] ?>
                                        </p>
                                    </div>
                                    <div class="game-v-ch" style="font: italic bold small serif">
                                        <a href='<?= "/base/view/$arrayGame[id]" ?>'>Читать подробнее</a>
                                        <a href='<?= "/base/edit/$arrayGame[id]" ?>' style="float: right">Редактировать</a>
                                    </div>
                                </div>
                            <?php }
                            ?>

                            <!-- Модальная форма добавления пройденной игры -->
                            <div class="hide">
                                <div class="box-modal" id="box-modal-data-gamer" style="width: 500px">
                                    <div class="header-modal">
                                        <b>Добавление пройденной игры</b>

                                        <div class="box-modal_close arcticmodal-close" onclick="closeModalAll()">
                                            <img src="/skins/img/interface/close-modal.png">
                                        </div>
                                    </div>
                                    <div style="padding:15px; padding-bottom: 45px;">
                                        <table class="modal-gamer-data-table">
                                            <tr>
                                                <td class="modal-gamer-data-td">Игра:</td>
                                                <td>
                                                    <input style="width: 188px" type="text" id="game" class="input-txt-profile" data-type="validation" placeholder="Пройденная игра">
                                                    <div style="float: right; margin: -2px -235px 0px 0px;" class="b-validation">
                                                        <div class="tooltip" id="game" style="margin-left: 28px;"></div>
                                                    </div>
                                                    <div id="selction-ajax"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="modal-gamer-data-td">Уровень сложности:</td>
                                                <td id="game-level-parent">
                                                    <select id="game-level" name="game-level" class="styled" style="width: 200px; height: 15px;">
                                                        <option selected='selected' value="null">Выбрать уровень</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="modal-gamer-data-td">Качество прохождения:</td>
                                                <td>
                                                    <select style="width: 200px; height: 15px;" id="game-passing">
                                                        <?php
                                                        foreach($data["type-complete-game"] as $type){
                                                            echo "<option value='$type[id]'>$type[name]</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="modal-gamer-data-td">Количество квестов:</td>
                                                <td>
                                                    <input id="quest-count" type="text" style="width:50px; margin-right: 40px"/>
                                                    <label class="checkbox"><input type="checkbox" id="not-quest-count"/> Не помню </label>
                                                    <div style="float: right; margin: 0px -235px 0px 0px;" class="b-validation">
                                                        <div class="tooltip" id="game-quest" style="margin-left: 28px;"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="modal-gamer-data-td">Начал играть:</td>
                                                <td>
                                                    <input id="game-start-date" type="text" value="дд-мм-гггг" onfocus="this.select();_Calendar.lcs(this)"
                                                           onclick="event.cancelBubble=true;this.select();_Calendar.lcs(this)" style="width: 90px" readonly="readonly"/>
                                                    <label class="checkbox">
                                                        <input type="checkbox" id="game-not-start-date" value="start-date" class="disable-date"/>Не помню
                                                    </label>
                                                    <div style="float: right; margin: 0px -235px 0px 0px;" class="b-validation">
                                                        <div class="tooltip" id="game-start" style="margin-left: 28px;"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="modal-gamer-data-td">Закончил играть:</td>
                                                <td>
                                                    <input id="game-end-date" type="text" value="дд-мм-гггг" onfocus="this.select();_Calendar.lcs(this)"
                                                           onclick="event.cancelBubble=true;this.select();_Calendar.lcs(this)" style="width: 90px" readonly="readonly"/>
                                                    <label class="checkbox">
                                                        <input type="checkbox" id="game-not-end-date" class="disable-date" value="end-date"/>Не помню
                                                    </label>
                                                    <div style="float: right; margin: 0px -235px 0px 0px;" class="b-validation">
                                                        <div class="tooltip" id="game-end" style="margin-left: 28px;"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="modal-gamer-data-td">Проголосуйте:</td>
                                                <td id="game-rating-parent">
                                                    <div class="rating">
                                                        <input type="hidden" class="val" value="0"/>
                                                        <input type="hidden" class="votes" value="0"/>
                                                    </div>
                                                    <div style="float: right; margin: -52px -235px 0px 0px;" class="b-validation">
                                                        <div class="tooltip" id="game-rating" style="margin-left: 28px;"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="modal-gamer-data-td">Отзыв:</td>
                                                <td>
                                                    <textarea style="width: 188px" type="text" id="game-description" class="input-txt-profile" data-type="validation">Опишите свои впечатления об игре.</textarea>
                                                    <div style="float: right; margin: 5px -235px 0px 0px;" class="b-validation">
                                                        <div class="tooltip" id="description" style="margin-left: 28px;"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                        <br>

                                        <div style="float: right"><a href="javascript: void(0)" class="btn-login" id="send-completed-game">Продолжить</a>
                                            <a style="margin-left: 10px; background: #b4b4b4 !important;" href="javascript:closeModalAll()" class="btn-login">Отмена</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>

<style type="text/css">
    #reload-avatar {
        cursor: pointer
    }

    .action-photo-profile {
        background-color: #cac3b7;
        position: absolute;
        width: 282px;
        bottom: 5px;
        opacity: 0.5;
    }

    .action-photo-profile a {
        color: #828b8c;
        font-size: 11px;
        text-decoration: none;
        margin-left: 10px;
    }

    .action-photo-profile img {
        padding-right: 5px;
    }

    .avatar-profile {
        position: relative
    }

    .none-avatar {
        position: relative;
        height: 322px;
        padding-bottom: 5px;
    }

    .none-avatar img {
        width: 282px;
        height: 322px;
    }

    .none-avatar div {
        z-index: 10;
        position: relative;
        bottom: 60px;
        left: 40px;
        color: white;
        text-decoration: underline;
    }

    img.avatar-profile {
        width: 282px !important;
    }

    span.customSelect {
        font: 12px sans-serif;
        background: #fff url(/skins/img/interface/2u7rpec.png) right center no-repeat;
        border: 1px solid #ddd;
        color: #555;
        padding: 7px 9px;
        -moz-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px 2px;
    }

    .input-txt-profile {
        width: 256px;
    }

    .area-txt-profile {
        width: 256px;
    }

    span.customSelect.changed {
        background-color: #f0dea4;
    }

        /* начало ранги*/
    .rank-header {
        margin-top: -1px;
        width: 200px;
        border: 1px solid #48596A;
        background-color: #718CA7;
        padding: 5px;
        color: #fff;
    }

    .rank-header i {
        margin-left: 5px;
        font-size: 14px;
    }

    .rank-img-div {
        width: 100%;
        text-align: center;
    }

    .rank-img-div img {
        padding: 25px 0
    }

        /* конец ранги*/

    .block-profile-info-wrapper {
        padding: 3px 0px;
        margin-left: 40px;
        line-height: 1.1em;
    }

    .block-profile-info-header {
        color: #777
    }

    .block-profile-info-txt {
        margin-left: 220px;
        color: #354050;
        width: 300px;
    }

    .modal-gamer-data-td {
        width: 150px;
    }

    .modal-gamer-data-table select {
        width: 200px;
    }

    .info-ajax-modal {
        background: #ffffff;
        font-weight: bold;
        text-align: center;
        padding: 20px 30px 20px;
        -moz-box-shadow: 0 0 80px rgba(0, 0, 0, .4);
        -webkit-box-shadow: 0 0 80px rgba(0, 0, 0, .4);
        -box-shadow: 0 0 80px rgba(0, 0, 0, .4);
        border: 1px solid #8e8e8e;
        color: #354050;
        font-size: 15px;
    }

    .menu_link_prof {
        color: #404040;
    }

    .menu_link_prof:hover {
        color: #48596A;
    }

    .menu_proff {
        text-align: center;
        width: 20px;
        padding: 0px;

        border: 1px solid #d2d2d2;
    }

    .menu_proff1 {
        width: 165px;
        padding: 5px;

        border: 1px solid #d2d2d2;
    }

    .oneShoTmy4 {
        width: 150px;
        padding: 5px;
        border: 1px solid #d2d2d2;

    }

    .oneShoTmy5 {
        text-align: center;
        width: 20px;
        padding: 5px;
        border: 1px solid #d2d2d2;
    }

    #profile-edit-form td {
        padding: 5px;
    }

    .balance-num {
        position: relative;
        bottom: 9px;
        font-weight: bold;
        font-size: 13px;
        text-align: center;
    }

    .age-profile {
        font-family: "age";
        font-size: 20px;
    }

    .age-txt {
        position: relative;
        bottom: 25px;
        right: 1px;
        font-weight: bold;
        font-size: 12px;
        text-align: center;
    }
</style>