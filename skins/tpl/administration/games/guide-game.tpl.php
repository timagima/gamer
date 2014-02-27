<div>
    <h2><?=$data['game']->name;?></h2>
    <a href="/administration/games/main-page/?id=<?=$data['game']->id;?>">Редактирование главной страницы</a><br>
    <?php
    foreach($data['rubrics'] as $rubric){ ?>
    <a href="/administration/games/edit-game-rubric/?id=<?=$rubric['id']?>">Редактирование рубрики "<?=$rubric['rubric']?>"</a><br>
    <?php } ?>
<div>