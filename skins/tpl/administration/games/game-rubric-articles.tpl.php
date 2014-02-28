<?php
use classes\render;
use classes\url;

?>


<h1>Статьи рубрики "<?=$data['game-rubric']['rubric']?>" -> "<?=$data['game-rubric']['game']?>"</h1>
<div style="height: 45px">

    <div class="right">
        <a name="create_item" id="create_item" href="<?= Url::Action("create-rubric-article", "administration.games")?>?id=<?=$data['game-rubric']['id_rubric']?>">Добавить</a>
    </div>
</div>

<div id="list">
    <table class="list-table">
        <thead>
        <tr>
            <th>Дата</th>
            <th>Заголовок</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        <? foreach ($data["rows"] as $row) { ?>
            <tr>
                <td><?=Render::FormatDate($row['date'])?></td>
                <td class="text-left"><?=$row['header']?></td>
                <td><a href='<?= Url::Action("EditGameRubricArticle", "administration.games") ?>?id=<?= $row['id'] ?>'>Редактироавть</a>
                </td>
                <td><a data-action="delete"
                       href='<?= Url::Action("delete-rubric-article", "administration.games") ?>?id-article=<?= $row['id'] ?>&id=<?=$data['game-rubric']['id_rubric']?>'>Удалить</a></td>
            </tr>
        <? }?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="5" style="text-align: right" id="paging">

            </td>
            <script type="text/html" id="paging_template">
                <a class="pager-item" data-page="{i}" href="<?= Url::Action("index") ?>?page={i}">{title}</a>
            </script>
        </tr>
        </tfoot>
    </table>
</div>

<?php
$path = $_SERVER["DOCUMENT_ROOT"];
include $path . "/skins/tpl/administration/common/paging-list.php"
?>

<script type="text/javascript">
    //$.datepicker.setDefaults($.datepicker.regional['ru']);

    function update_table(data) {
        debugger;
        var tbody = "";
        $.each(data["rows"], function (key, value) {
            tbody += ("<tr><td>" + $.datepicker.formatDate('dd.mm.yy', new Date(value['date'])) +
                "</td><td class='text-left'>" + value['header'] + "</td><td><a href='<?=Url::Action("edit")?>?id=" + value['id'] + "'>Редактировать</a></td>" +
                "<td><a data-action='delete' href='<?=Url::Action("delete")?>/?id=" + value['id'] + "'>Удалить</a></td></tr>");
        });
        $("#list table tbody").html(tbody);
        create_paging(data["current_page"], data["count"]);
    }

    /*create_paging(1, <?=$data["count"]?>); */

</script>