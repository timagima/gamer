<script type="text/javascript" src="/skins/js/validation.js"></script>
<style type="text/css">
    .b-validation .tooltip {
        display: none;
        z-index:10;
        padding: 5px;
        line-height:16px;

        position:absolute;
        color:#111;
        border:1px solid #DCA;
        background:#FEFFD6;

        border-radius:4px;
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        -moz-box-shadow: 5px 5px 8px #CCC;
        -webkit-box-shadow: 5px 5px 8px #CCC;

        width: 200px;
        z-index: 99;
        color:#FFFFFF;
        min-height: 32px;
        min-width: 200px;
    }

    .b-validation .error {
        display: block;
    }

    .b-validation .tooltip { position: relative; background: #1ABC9C; border: 4px solid #2C3E50; }
    .b-validation .tooltip:after, .b-validation .tooltip:before { right: 100%; top: 50%; border: solid transparent; content: " "; height: 0; width: 0; position: absolute; pointer-events: none; }
    .b-validation .tooltip:after { border-color: rgba(26, 188, 156, 0); border-right-color: #1ABC9C; border-width: 10px; margin-top: -10px; }
    .b-validation .tooltip:before { border-color: rgba(44, 62, 80, 0); border-right-color: #2C3E50; border-width: 16px; margin-top: -16px; }
</style>

<h2>Пройденные игры</h2>



<div id="featured-section" style="position: relative; z-index: 10;" class="FL">
<table>
<tr>
<td style="vertical-align: top;">
    <div id="content" style="width: 668px;">

        <div id="content-profile">

            <div class='left'>
                <?
                if(!empty($this->user['first_name']) && !empty($this->user['last_name']) &&
                    !empty($this->user['patronymic']) && !empty($this->user['birthday']) && !empty($this->user['sex'])){
                    $nameProfile = $this->user['last_name'] . " " . $this->user['first_name'] . " " . $this->user['patronymic'];
                ?>
            <!-- Форма добавления пройденных игр Добавить пройденную игру<br>-->

                    <div>
                        <a data-reveal-id="edit-main-data" href="javascript:showModal('box-modal-data-gamer')" data-animation="fade" style="cursor: pointer; text-decoration: none;  color:#507fb6">Добавить пройденную игру</a>
                    </div>
                    <!-- таблица с пройденными играми пользователя здесь таблица с пройденными играми пользователя<br>-->
                    <?php
                        foreach($data['user-completed-games'] as $arrayGame){
                            echo "<img src='storage".$arrayGame['source_img_s'] . "'/>";
                        }
                    ?>
                    <!-- Модальная форма добавления пройденной игры -->
                    <div class="hide">
                        <div class="box-modal" id="box-modal-data-gamer" style="width: 390px">
                            <div class="header-modal">
                                <b>Добавление пройденной игры</b>
                                <div  class="box-modal_close arcticmodal-close" onclick="closeModalAll()">
                                    <img src="/skins/img/interface/close-modal.png"></div>
                            </div>

                            <div style="padding:15px; padding-bottom: 45px;">
                                <table class="modal-gamer-data-table">
                                    <tr>
                                        <td class="modal-gamer-data-td">Игра:</td>
                                        <td>
                                            <input style="width: 188px" type="text" id="game" class="input-txt-profile" data-type="validation" placeholder="Пройденная игра" >
                                            <div style="float: right; margin: -45px -235px 0px 0px;" class="b-validation">
                                                <div class="tooltip " id="game" style="margin-left: 28px;">
                                                </div>
                                            </div>
                                            <div id="selction-ajax"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="modal-gamer-data-td" >Уровень сложности:</td>
                                        <td>
                                            <select id="game-level" name ="game-level" class="styled" style="width: 180px; height: 15px;">
                                                <!----><option selected='selected' value="null">Выбрать уровень</option>
                                             </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="modal-gamer-data-td" >Отзыв:</td>
                                        <td>
                                            <textarea style="width: 188px" type="text" id="game-description" class="input-txt-profile" data-type="validation" >Опишите свои впечатления об игре.</textarea>
                                        </td>
                                    </tr>
                                </table><br>
                                <div style="float: right"><a class="btn-login" id="send-completed-game">Продолжить</a>
                                    <!--<a class="btn-login" href="javascript:addCompletedGames()">Продолжить</a>-->
                                    <a style="margin-left: 10px; background: #b4b4b4 !important;" href="javascript:closeModalAll()" class="btn-login">Отмена</a></div>
                            </div>
                        </div>
                    </div>


                <?} else { ?>
                    <div class="hide">
                        <div class="box-modal" id="box-modal-main-save" style="width: 500px">
                            <div class="header-modal">
                                <b>Подтверждение сохранения данных аккаунта</b>
                                <div  class="box-modal_close arcticmodal-close" onclick="closeModalAll()">
                                    <img src="/skins/img/interface/close-modal.png"></div>
                            </div>
                            <div style="padding:15px; padding-bottom: 45px;">
                                <img id="captcha-image" src="/skins/img/interface/warning-update.png" class="left">
                                <div style="margin-left: 120px;">
                                    <span>Сохраняя <b>основную</b> информацию о себе, вы больше не сможете её изменить, вы уверены что хотите продолжить?</span>
                                </div><br>
                                <div style="float: right"><a href="javascript:editUserData()" class="btn-login">Продолжить</a>
                                    <a style="margin-left: 10px; background: #b4b4b4 !important;" href="javascript:closeModalAll()" class="btn-login">Отмена</a></div>
                            </div>
                        </div>
                    </div> <!-- Модальная форма подтверждения о внесении данных пользователя единожды -->
                    <b style="font-size: 12px;">
                        Внимание! При заполнении данной формы просьба указывать реальные сведения, в противном случае администрация сайта GS11 имеет право не выплачивать
                        Вам выигрышную сумму. Ваши данные строго конфиденциальны. Более подробную информацию читайте в <a style="color: #507fb6; text-decoration: none" href="/about/offer">правилах сайта.</a>
                    </b>
                    <table>
                        <tr>
                            <td>
                                <h4 style="line-height: 0.8em;" class="page-title FL">Общие данные</h4>
                            </td>
                            <td>
                                <div style="position: relative; width: 395px; left: 5px; height: 4px; background:url(/skins/img/interface/point.png) "></div>
                            </td>
                            <td>
                                <div class="right" style="position: relative; font-size: 12px; bottom: 1px; left: 10px;">
                                    <a data-reveal-id="edit-main-data" data-animation="fade" style="cursor: pointer; color:#507fb6;text-decoration: none"  href="javascript:confirmSaveInfo()">Сохранить</a>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td style="vertical-align: top;">
                                <div class="block-profile-info-wrapper">
                                    <div class="block-profile-info-header left">Фамилия:</div>
                                    <div class="block-profile-info-txt"><input type="text" id="edit-last-name" class="input-txt-profile" value="<?=$this->user['last_name'];?>" ></div>
                                </div>
                                <div class="block-profile-info-wrapper">
                                    <div class="block-profile-info-header left">Имя:</div>
                                    <div class="block-profile-info-txt"><input type="text" id="edit-first-name" class="input-txt-profile" value="<?=$this->user['first_name'];?>"></div>
                                </div>
                                <div class="block-profile-info-wrapper">
                                    <div class="block-profile-info-header left">Отчество:</div>
                                    <div class="block-profile-info-txt"><input type="text" id="edit-patronymic" class="input-txt-profile" value="<?=$this->user['patronymic'];?>"></div>
                                </div>
                                <div class="block-profile-info-wrapper">
                                    <div class="block-profile-info-header left">Возраст</div>
                                    <div class="block-profile-info-txt">
                                        <select id="edit-age-day" class="styled" style="width: 40px;"></select>
                                        <select id="edit-age-month" class="styled" style="width: 100px;">
                                            <option value="1">Январь</option>
                                            <option value="2">Февраль</option>
                                            <option value="3">Март</option>
                                            <option value="4">Апрель</option>
                                            <option value="5">Май</option>
                                            <option value="6">Июнь</option>
                                            <option value="7">Июль</option>
                                            <option value="8">Август</option>
                                            <option value="9">Сентябрь</option>
                                            <option value="10">Октябрь</option>
                                            <option value="11">Ноябрь</option>
                                            <option value="12">Декабрь</option>
                                        </select>
                                        <select id="edit-age-year" class="styled" style="width: 60px;" ></select>
                                    </div>
                                </div>
                                <div class="block-profile-info-wrapper">
                                    <div class="block-profile-info-header left">Пол:</div>
                                    <div class="block-profile-info-txt">
									<label class="radio">
										<input type="radio" name="edit-sex" value="1">мужской
									</label>
									<label class="radio" style="margin-left:25px">
										<input type="radio" name="edit-sex" value="2">женский
									</label>

                                    </div>
                                </div>
                                <div class="block-profile-info-wrapper">
                                    <div class="block-profile-info-header left">Немного о себе:</div>
                                    <div class="block-profile-info-txt">
                                        <textarea type="text" id="edit-about-me" class="area-txt-profile"><?=$this->user['about_me'];?></textarea>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                <? } ?>

            </div>
        </div>
    </div>
</td>

</tr>
</table>


</div>


<script>
    $(document).ready(function(){


        $(".avatar-profile").hover(function(){
            $(".action-photo-profile").slideDown("fast")
        }).mouseleave(function(){
            $(".action-photo-profile").slideUp("fast");
        });

        $('#file').bind('change', function () {

            urlPath = $('#url').val();
            serviceGS11.uploadAvatar(urlPath);
        })
        $('#reload-avatar').bind('click', function(e) {
            $('#file').click();
        });

    })

    function deleteImgAvatar(){
        $.ajax({
            type: 'POST',
            url: "/profile/delete-avatar",
            dataType: 'html',
            beforeSend: function(){
                $('#ajax-modal-result').html('<img id="ajax" src="/skins/img/ajax.gif">');
            },
            success: function(data){
                $("#ajax").remove();
                closeModalAll();
                $('.avatar-profile').remove();
                $('#nick-txt').after('<div class="none-avatar"><img src="/skins/img/m.jpg"/><br /><div class="container upload">' +
                    '<span class="btn" style="width: 200px;">Загрузить фотографию</span>' +
                    '<input id="file" type="file" value="/profile/upload" name="file[]" style="width: 200px;" />' +
                    '<input type="hidden" id="url" value="/profile/upload-avatar"></div><div id="info"></div></div>');
                $('#file').bind('change', function () {
                    urlPath = $('#url').val();
                    serviceGS11.uploadAvatar(urlPath);
                })

            }
        });
    }
</script>


<style type="text/css">
    #reload-avatar{cursor: pointer}
    .action-photo-profile{ background-color: #cac3b7; position: absolute;  width: 282px; bottom: 5px; opacity: 0.5;}
    .action-photo-profile a{color: #828b8c; font-size: 11px; text-decoration: none; margin-left: 10px;}
    .action-photo-profile img{padding-right: 5px; }
    .avatar-profile{position: relative}
    .none-avatar{position: relative; height: 322px; padding-bottom: 5px;}
    .none-avatar img{width: 282px; height: 322px;}
    .none-avatar div{z-index: 10; position: relative; bottom: 60px; left: 40px; color: white; text-decoration: underline;}
    img.avatar-profile{width: 282px !important;}
    span.customSelect {
        font:12px sans-serif;
        background:#fff url(/skins/img/interface/2u7rpec.png) right center no-repeat;
        border:1px solid #ddd;
        color:#555;
        padding:7px 9px;
        -moz-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px 2px;
    }

    .input-txt-profile{width: 256px;}
    .area-txt-profile{width: 256px;}

    span.customSelect.changed {
        background-color: #f0dea4;
    }
        /* начало ранги*/
    .rank-header{margin-top:-1px; width: 200px; border:1px solid #48596A; background-color:#718CA7; padding:5px; color: #fff;}
    .rank-header i{margin-left: 5px; font-size: 14px;}
    .rank-img-div{width: 100%; text-align: center;}
    .rank-img-div img{padding: 25px 0}
        /* конец ранги*/


    .block-profile-info-wrapper{padding: 3px 0px; margin-left: 40px; line-height: 1.1em;}
    .block-profile-info-header{color: #777}
    .block-profile-info-txt{margin-left: 220px; color: #354050; width: 300px;}
    .modal-gamer-data-td{width: 150px;}
    .modal-gamer-data-table select{width: 200px;}

    .info-ajax-modal {  background: #ffffff; font-weight: bold; text-align: center; padding: 20px 30px 20px; -moz-box-shadow: 0 0 80px rgba(0,0,0,.4); -webkit-box-shadow: 0 0 80px rgba(0,0,0,.4); -box-shadow: 0 0 80px rgba(0,0,0,.4); border: 1px solid #8e8e8e; color: #354050; font-size: 15px;}

    .menu_link_prof{color:#404040;}
    .menu_link_prof:hover{color:#48596A;}
    .menu_proff{
        text-align:center;
        width:20px;
        padding:0px;

        border:1px solid #d2d2d2;
    }
    .menu_proff1{
        width:165px;
        padding:5px;

        border:1px solid #d2d2d2;
    }


    .oneShoTmy4{
        width:150px;
        padding:5px;
        border:1px solid #d2d2d2;

    }
    .oneShoTmy5{
        text-align:center;
        width:20px;
        padding:5px;
        border:1px solid #d2d2d2;
    }
    #profile-edit-form td{padding:5px;}
    .balance-num{position: relative; bottom: 9px; font-weight: bold; font-size: 13px; text-align: center;}
    .age-profile{font-family: "age"; font-size: 20px;}
    .age-txt{position: relative; bottom: 25px; right: 1px; font-weight: bold; font-size: 12px; text-align: center;}
</style>