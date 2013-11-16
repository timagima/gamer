<?php
use classes\render;
use classes\url;
?>


<h1>Список игр</h1>
<div style="height: 45px">

    <div class="right">
        <a name="create_item" id="create_item" href="<?= Url::Action("add-main-list-game", "administration.games") ?>?action=add">Добавить</a>
    </div>
</div>

<div id="list">
    <table class="list-table">
        <thead>
        <tr>

            <th>Заголовок</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        <? foreach ($data as $row) { ?>
            <tr>

                <td class="text-left"><?=$row->name; ?></td>
                <td><a href='<?= Url::Action("add-main-list-game", "administration.games") ?>?action=edit&id=<?= $row->id ?>'>Редактироавть</a>
                </td>
                <td><a data-action="delete"
                       href='<?= Url::Action("add-main-list-game", "administration.games") ?>?action=delete&id=<?= $row->id ?>'>Удалить</a></td>
            </tr>
        <? }?>
        </tbody>
    </table>
</div>