<?php
use classes\render as Render;
use classes\url as Url;
?>
<style>
    #header-games input{width: 774px;}
    #announce-games textarea{width: 774px;}
    .search-index input {width: 948px;}
</style>

<h1><a href="<?= Url::Action("index", "administration.games") ?>">The Elder Scrolls V: Skyrim</a>
    -> <?=$data["id"] > 0 ? "Редактирование" : "Создание Квеста" ?></h1>
<form action="<?= Url::Action("edit", "administration.games") ?>" method="POST">
    <?=Render::Hidden($data["id"], "id")?>
    <?=Render::Hidden($_GET['game'], "game_id")?>
    <table>
        <tr>
            <td>
                <div class="field">
                    <?=Render::LabelDatePicker(date("d.m.Y", strtotime($data["date"])), "date", "Дата", true)?>
                </div>
            </td>
            <td>
                <div class="field fill" id="header-games">
                    <?=Render::LabelEdit($data['header'], "header", "Заголовок", true)?>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="field" style="float: left; padding: 10px">
                        <div id="main-photo-upload-btn" class="container upload">
                            <span class="btn">Главное фото</span>
                            <input id="file" type="file" name="file[]"/>
                        </div>
                        <div id="main-photo-delete-btn" class="container upload hide">
                            <span class="btn" onclick="deleteMainPhoto()">Удалить фото</span>
                        </div>
                        <div id="info" style="padding: 10px;"></div>

                </div>
            </td>
            <td>
                <div class="field" id="announce-games">
                    <?=Render::LabelTextArea($data['short'], "short", "Анонс", true)?>
                </div>
            </td>
        </tr>
        </table>
    <br>
    <div class="field">
        <?=Render::LabelTextArea($data['text'], "text", "")?>
    </div>

    <div class="field fill search-index" style="width: 100%; margin-right: 2%;">
        <?=Render::LabelEdit($data['title'], "title", "Заголовок страницы")?>
    </div>
    <div class="field fill search-index" style="width: 100%; margin-right: 2%;">
        <?=Render::LabelEdit($data['description'], "description", "Описание страницы")?>
    </div>
    <div class="field fill search-index" style="width: 32%">
        <?=Render::LabelEdit($data['keywords'], "keywords", "Ключевые слова")?>
    </div>
    <br>

    <div class="field">
            <div class="container upload">
                <span class="btn" style="width: 220px;">Коллекция изображений</span>
                <input id="file-others" type="file" style="width: 220px;" multiple="multiple" name="file[]"/>
            </div>
            <div id="info-others" style="padding: 10px;"></div>
    </div>


    <div style="height: 50px; width: 100%">
        <input type="submit" value="Сохранить" class="right">
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function () {
        $('#file').bind('change', function () {
            execUpload(false, 'file', 'info');
        })
        $('#file-others').bind('change', function () {
            execUpload(true, 'file-others', 'info-others');
        })
        function progressHandlingFunction(e) {
            if (e.lengthComputable) {
                var percentComplete = parseInt((e.loaded / e.total) * 100);
                $('.progress_bar').animate({width: percentComplete + "%"}, 10);
            }
        }
        function execUpload(param, id, result){
            var data = new FormData();
            var error = '';
            jQuery.each($('#'+id)[0].files, function (i, file) {
                data.append('file-' + i, file);
            });

            if (error != '') {
                $('#'+result).html(error);
            } else {
                if(param == true)
                    $.ajax({url: "/administration/games/upload", type: 'POST', data: {"multi-load":true}})
                $.ajax({
                    url: "/administration/games/upload",
                    type: 'POST',
                    xhr: function () {
                        var myXhr = $.ajaxSettings.xhr();
                        $("#"+result).before('<div class="progress_container"><div class="progress_bar tip"></div></div>');
                        $(".progress_container").css("margin","10px 0");
                        if (myXhr.upload) {
                            myXhr.upload.addEventListener('progress', progressHandlingFunction, false);
                        }
                        return myXhr;

                    },
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {},
                    success: function (data) {
                        $(".progress_container").remove();
                        var resJson = $.parseJSON(data);
                        if(typeof resJson.error == 'undefined')
                        {
                            if(param != true){
                                add_image_to_editor(resJson.filename);
                                $("form").append("<input type='hidden' id='img-main' name='img-main' value='"+data+"'>");
                                $("#main-photo-upload-btn").hide();
                                $("#main-photo-delete-btn").show();
                            } else {
                                var resHtml = '';
                                for(var i = 0; i < resJson.big.length; i++){
                                    resHtml += "<div><img style='width:75px;' src='/"+resJson.small[i]+"'>" +
                                        "<span style='margin-left: 25px;'>/"+resJson.big[i]+"<span></div><br>";
                                }
                                $("#"+result).html(resHtml);

                            }
                        }
                        else
                            $("#"+result).html("<b style='color: red'>"+resJson.error+"</b>");
                    },
                    error: errorHandler = function () {
                        $(".progress_container").remove();
                        $('#'+result).html('Ошибка загрузки файлов');
                    }
                });

            }

        }
        function add_image_to_editor(image){
            var content = "<img class=\"left\" id=\"main_image\" src=\"" + image + "\">";
            $(tinyMCE.activeEditor.getBody()).prepend(content);

        }
    });
    function deleteMainPhoto(){
        $("#main-photo-upload-btn").show();
        $("#main-photo-delete-btn").hide();
        $("#img-main").remove();
        $(tinyMCE.activeEditor.getBody()).find("#main_image").remove();
    }

</script>