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
    .edit-image{position: relative;}
    #error-img{padding: 20px; float: right}
    #delete-images{background-image:url(/skins/img/interface/delete-image-hover.png); display: none; opacity: 0.6; width: 15px; height: 15px; position: absolute;  cursor: pointer;}
</style>

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
                        <div id="icon-upload-btn" class="container upload">
                            <span class="btn">Иконка</span>
                            <input id="source_img_s" type="file" name="source_img_s"/>

                        </div>
                    <?}else{?>
                        <div style="padding: 10px">
                            <div class="edit-image">
                                <img src="/storage<?=$data['game']->source_img_s;?>">
                            </div>
                        </div>
                    <?}?>
                    <?
                    if(empty($data['game']->source_img_b)){
                        ?>
                        <br><br>
                        <div id="img-upload-btn" class="container upload">
                            <span class="btn">Изображение</span>
                            <input id="source_img_b" type="file" name="source_img_b" />
                        </div>
                    <?}else{?>
                        <div style="padding: 10px">
                            <div class="edit-image">
                                <img src="/storage<?=$data['game']->source_img_b;?>">
                            </div></div>

                    <?}?>
                    <div id="info" style="padding: 10px;"></div>
                </div>
            </td>
        </tr>
    </table>
    <div style="height: 50px; width: 100%">
        <input type="submit" value="Сохранить" class="right">
    </div>
</form>
<? include $_SERVER["DOCUMENT_ROOT"]. "/skins/tpl/block/main-modal.block.tpl.php"; ?>
<script type="text/javascript">
    var config = {
        form: "edit-main-game",
        dragArea: "dragAndDropFiles",
        visualProgress: "modal",
        img: true,
        uploadUrl: document.location.href,
    }

    $(document).ready(function(){
        initMultiUploader(config);
    });
</script>

