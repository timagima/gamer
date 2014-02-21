<?php
use classes\render;
use classes\url;
?>
<script type="text/javascript" src="/skins/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="/skins/js/tinymce/jquery.tinymce.min.js"></script>
<style>
    .header-games input{width: 945px;}
    #announce-games textarea{width: 774px;}
    .search-index input {width: 948px;}
    .input-link{width: 470px}
    .input-link input{width: 400px}
</style>

<h2><a style="text-decoration: none; color: #507fb6" href="<?= Url::Action("index", "administration.games") ?>"><?=$data['game']->name;?></a>
    -> <?=$data['main-page']->id > 0 ? "Редактирование главной страницы " : "Создание главной страницы" ?></h2>
<form action="<?= Url::Action("main-page-edit", "administration.games") ?>" id="main-page-edit" method="POST">
    <?=Render::Hidden($data['main-page']->id, "id")?>

    <?=Render::Hidden($data['game']->id, "id-game")?>
    <table>
        <tr>
            <td colspan="2">
                <div class="field fill header-games">
                    <?=Render::LabelEdit($data['main-page']->game_mode, "game_mode", "Режим игры", true)?>
                </div>
            </td>
        </tr>

        <tr>
            <td>
                <div class="field fill">
                    <?=Render::LabelEdit(date("d.m.Y", $data['main-page']->date_release_world), "date_release_world", "Дата выхода в мире", true)?>
                </div>
            </td>
            <td>
                <div class="field fill">
                    <?=Render::LabelEdit(date("d.m.Y", $data['main-page']->date_release_russia), "date_release_russia", "Дата выхода в россии", true)?>
                </div>
            </td>
        </tr>
        <tr>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->publisher, "publisher", "Издатель", true)?>
                </div>
            </td>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->publisher_link, "publisher_link", "Ссылка", true)?>
                </div>
            </td>
        </tr>
        <tr>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->publisher_russia, "publisher_russia", "Издатель в россии", true)?>
                </div>
            </td>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->publisher_russia_link, "publisher_russia_link", "Ссылка", true)?>
                </div>
            </td>
        </tr>
        <tr>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->developer, "developer", "Разработчик", true)?>
                </div>
            </td>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->developer_link, "developer_link", "Ссылка", true)?>
                </div>
            </td>
        </tr>
        <tr>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->official_site, "official_site", "Официальный либо другой сайт", true)?>
                </div>
            </td>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->official_site_link, "official_site_link", "Ссылка", true)?>
                </div>
            </td>
        </tr>
        <tr>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->game_engine, "game_engine", "Игровой движок", true)?>
                </div>
            </td>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->video_img, "video_img", "Изображение для видео", true)?>
                </div>
            </td>
        </tr>
        <tr>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->distribution, "distribution", "Распростронение", true)?>
                </div>
            </td>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->video_link, "video_link", "Видеоролик", true)?>
                </div>
            </td>
        </tr>
    </table>
    <h4>Системные требования</h4>
    <table>
        <tr>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->sr_os, "sr_os", "ОС", true)?>
                </div>
            </td>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->sr_cpu, "sr_cpu", "CPU", true)?>
                </div>
            </td>
        </tr>
        <tr>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->sr_ram, "sr_ram", "ОЗУ", true)?>
                </div>
            </td>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->sr_video, "sr_video", "Видеокарта", true)?>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->sr_hdd, "sr_hdd", "HDD", true)?>
                </div>
            </td>
        </tr>
    </table>
    <br>
    <div class="field">
        <?=Render::LabelTextArea($data['main-page']->text, "text", "")?>
    </div>

    <div class="field fill search-index" style="width: 100%; margin-right: 2%;">
        <?=Render::LabelEdit($data['main-page']->title, "title", "Заголовок страницы")?>
    </div>
    <div class="field fill search-index" style="width: 100%; margin-right: 2%;">
        <?=Render::LabelEdit($data['main-page']->description, "description", "Описание страницы")?>
    </div>
    <div class="field fill search-index" style="width: 32%">
        <?=Render::LabelEdit($data['main-page']->keywords, "keywords", "Ключевые слова")?>
    </div>

    <div id="rubrics">


        <?foreach($data['rubrics'] as $r){ ?>

            <div class="rubric" style="margin-bottom: 200px;">
                <div class="edit-image" style="width: 200px;">
                    <img src="<?=$r['rubric_img_s']?>">
                    <input type="hidden" name="saved-img[]" value="<?=$r['id']."$".$r['rubric_img_s']?>">
                </div>
                <div id="img-upload-btn-<?=$r['id']?>" class="container upload" style="display: inline-block; margin-right: 80px;">
                    <span class="btn">Изображение</span>
                    <input id="img-files-<?=$r['id']?>" type="file" name="img-files[]" multiple />
                </div>
                <div class="field" style="display: inline-block;">
                    <?=Render::Hidden($r['id'], "id-rubrics[]")?>
                    <?=Render::LabelEdit($r['rubric'], "rubrics[]", "Рубрика игры", false); ?>
                </div>
                <div class="field" style="display: inline-block;">
                    <a href="javascript:void(0)" class="remove-rubric" id="<?=$r['id']?>">Удалить</a>
                </div>
            </div>

            <? } ?>

    </div>
    <div class="field" style="padding: 10px 10px 10px 0px">
        <input type="button" id="add-rubric" value="Добавить ещё рубрику">
    </div>

    <div id="img-upload-btn" class="container upload">
        <span class="btn">Изображение</span>
        <input id="img-files" type="file" name="img-files[]" multiple />
    </div>

    <div style="height: 50px; width: 100%">
        <input type="submit" value="Сохранить" class="right">
    </div>


</form>
<? include $_SERVER["DOCUMENT_ROOT"]. "/skins/tpl/block/main-modal.block.tpl.php"; ?>
<style>
    .edit-image{float: left; margin: 10px;}
</style>

<script type="text/javascript">
    $(document).ready(function(){
        initMultiUploader(config);

        $("body").on("click", ".remove-rubric", function(){
            //debugger;
            var id = $(this).attr('id');
            //var id = $(this).closest("div.rubric").find("input[name=id-rubrics]").val();
            if(id!== undefined)
                $('form').append("<input type='hidden' name='deleted-rubrics[]' value='"+id+"'>");
            $(this).closest("div.rubric").remove();
        });

        $("#add-rubric").click(function(){
            //var element = $(".add-rubric")[1];
            var html = '<div class="rubric">' +
                            '<div class="field" style="">' +
                                '<label>Рубрика игры</label><br>' +
                                '<input type="text" value="" name="new-rubrics[]">' +
                            '</div>' +
                            '<div class="field" style="float: right;"><a href="javascript:void(0)" class="remove-rubric">Удалить</a></div>' +
                        '</div>';
            $("#rubrics").append(html);
            //$(cloneEl).find("input[type=text]").val("");
        });
    })
    // todo: сделать порог входящих файлов
    // todo: сделать мультизагрузку файлов
    var config = {
        form: "main-page-edit",
        visualProgress: "modal",
        img: true,
        method: "MainPageGame",
        multi: false,
        limit: 1,
        uploadUrl: document.location.href,

    };

    $(function () {
        tinymce.init({
            selector: "#text",
            language : "ru",
            plugins: [
                "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking jbimages",
                "table contextmenu directionality emoticons template textcolor paste textcolor"
            ],
            toolbar1: "newdocument | bold italic underline strikethrough | jbimages | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
            toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | inserttime preview | forecolor backcolor",
            toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking template pagebreak restoredraft",
            menubar: false,

            toolbar_items_size: 'small',
            relative_urls: false,
            style_formats: [
                {title: 'Bold text', inline: 'b'},
                {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                {title: 'Example 1', inline: 'span', classes: 'example1'},
                {title: 'Example 2', inline: 'span', classes: 'example2'},
                {title: 'Table styles'},
                {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
            ],

            templates: [
                {title: 'Test template 1', content: 'Test 1'},
                {title: 'Test template 2', content: 'Test 2'}
            ]
        });
    });

</script>