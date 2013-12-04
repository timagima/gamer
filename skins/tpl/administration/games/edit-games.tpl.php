<?php
use classes\render;
use classes\url;
?>
<script type="text/javascript" src="/skins/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="/skins/js/tinymce/jquery.tinymce.min.js"></script>
<script type="text/javascript" src="/skins/js/multiupload.js"></script>
<style>
    #header-games input{width: 774px;}
    #announce-games textarea{width: 774px;}
    .search-index input {width: 948px;}
    #delete-images{background-image:url(/skins/img/interface/delete-image-hover.png); display: none; opacity: 0.6; width: 15px; height: 15px; position: absolute;  cursor: pointer;}
</style>
<script>
    $(document).ready(function(){
        $('.edit-image').mouseover(function(){
            if($("#delete-images").length == 0){
                var obj = $(this);
                var $delButton = $('<div/>', {id: "delete-images"}).click(function() {
                    deleteImages(obj);
                });
                var widthIconDel = $(this).find('img').width()-15;
                $('.edit-image').css({"width":$(this).find('img').width()+"px"});
                $(this).find('img').before($delButton);
                $(this).find("#delete-images").fadeIn(200).css({'left': widthIconDel+'px'});
            }
            $('#delete-images').mouseover(function(){
                $('#delete-images').stop().animate({opacity: 1}, 200);
            }).mouseleave(function(){
                    $('#delete-images').stop().animate({opacity: 0.6}, 200);

            })

        }).mouseleave(function(){
                /*var def = $.Deferred()
                $(this).find("#delete-images").fadeOut(200, def.resolve); comments_tournament
                def.done(function () {*/
                    $("#delete-images").remove();
                //});


        })
        function deleteImages(link){

            // здесь реализовать ajax запрос для удаление фотографии из каталога и базы, но только при сохранении.
            // Т.е. необходимо будет собирать данные в JSON в каком то скрытом поле

            $(link).remove();
        }
    })

</script>

<h1><a href="<?= Url::Action("index", "administration.games") ?>"><?=$data['game']->name; ?></a>
    <?=$data['game']->id > 0 ? " -> Редактирование" : "Создание" ?></h1>
<form action="<?= Url::Action("edit-main-game", "administration.games") ?>" method="POST" enctype="multipart/form-data" id="edit-main-game">
    <?=Render::Hidden($data['game']->id, "id")?>
    <?=Render::Hidden($_GET['id'], "game_id")?>
    <table>
        <tr>

            <td>
                <div class="field fill" id="header-games">
                    <?=Render::LabelEdit($data['game']->name, "name", "Название игры", true)?>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="field" id="genre">
                    <select name="genre_id">
                        <?
                        foreach($data['genre'] as $r)
                        {
                            $selected = ($data['game']->genre_id == $r->id) ? "selected" : "";
                            echo "<option value='".$r->id."' ".$selected.">".$r->name."</option>";
                        }
                        ?>
                    </select>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="field" style="float: left; padding: 10px">
                    <?
                    if(empty($data['game']->source_img_s)){
                    ?>
                    <div id="main-photo-upload-btn" class="container upload">
                        <span class="btn">Иконка</span>
                        <input id="file" type="file" name="file[]" multiple/>
                    </div>
                    <?}else{?>
                    <div style="padding: 10px">
                        <div class="edit-image" style="/*padding: 10px;*/ position: relative">
                            <img src="/storage<?=$data['game']->source_img_s;?>">
                        </div>
                    </div>
                    <?}?>
                    <?
                    if(empty($data['game']->source_img_b)){
                        ?>
                        <br><br><div id="main-photo-upload-btn" class="container upload">
                            <span class="btn">Изображение</span>
                            <input id="file" type="file" name="file[]" multiple />
                        </div>
                    <?}else{?>
                        <div style="padding: 10px">
                            <div class="edit-image" style="/*padding: 10px;*/ position: relative">
                                <img src="/storage<?=$data['game']->source_img_b;?>">
                        </div></div>
                    <?}?>
                    <div id="dragAndDropFiles" class="uploadArea">
                        <h1>Перетащите сюда изображения</h1>
                    </div>
                    <div id="main-photo-delete-btn" class="container upload hide">
                        <span class="btn" onclick="deleteMainPhoto()">Удалить фото</span>
                    </div>
                    <div id="info" style="padding: 10px;"></div>
                    <div class="progressBar">
                        <div class="status"></div>
                    </div>
                </div>
            </td>
        </tr>

    </table>
    <div class="box-modal" id="upload-process-ajax-modal" style="width: 190px">

    </div>
    <div style="height: 50px; width: 100%">
        <input type="submit" value="Сохранить" class="right">
    </div>
</form>
<script type="text/javascript">
    var config = {
        /*support : "image/jpg,image/png,image/bmp,image/jpeg,image/gif",*/
        form: "edit-main-game",
        dragArea: "dragAndDropFiles",
        uploadUrl: document.location.href
    }
    $(document).ready(function(){
        initMultiUploader(config);
    });
</script>
