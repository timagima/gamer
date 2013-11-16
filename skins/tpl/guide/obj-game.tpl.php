<style>
    .content-guide-games{position:relative; bottom: 17px;}
    .table-video-screen-game{text-align: left}
    .table-screen img{margin-left: 5px; width: 170px; height: 150px;}
</style>
<link type="text/css" rel="stylesheet" href="/skins/css/video.css"/>
<script src="http://vjs.zencdn.net/c/video.js"></script>
<img class="left" style="padding-right: 25px; " src="/storage<?=$data['obj']->source_img; ?>" alt="<?=$data['obj']->name; ?>" title="<?=$data['obj']->name; ?>" />
<div class="content-guide-games">
    <h3><?=$data['obj']->name; ?></h3>
    Издатель: <a href="<?=$data['obj']->publisher_link; ?>" target="_blank"><?=$data['obj']->publisher; ?></a><br>
    Издатель в россии: <a href="<?=$data['obj']->publisher_russia_link; ?>" target="_blank"><?=$data['obj']->publisher_russia; ?></a><br>
    Разработчик: <a href="<?=$data['obj']->developer_link; ?>" target="_blank"><?=$data['obj']->developer; ?></a><br>
    Сайт: <a href="<?=$data['obj']->official_site_link; ?>" target="_blank"><?=$data['obj']->official_site; ?></a><br>
    Игровой движок: <span><?=$data['obj']->game_engine; ?></span><br>
    Жанр: <span><?=$data['obj']->genre; ?></span><br>
    Режим игры: <span><?=$data['obj']->game_mode; ?></span><br>
    Распростаранение: <span><?=$data['obj']->distribution; ?></span><br>
    Системные требования: <br><span>ОС: <?=$data['obj']->sr_os; ?></span><br>
    <span>ОЗУ: <?=$data['obj']->sr_ram; ?></span><br>
    <span>CPU: <?=$data['obj']->sr_cpu; ?></span><br>
    <span>Видеокарта: <?=$data['obj']->sr_video; ?></span><br>
    <span>HDD: <?=$data['obj']->sr_hdd; ?></span><br>

    <span>
        <?=date("d", $data['obj']->date_release_world);?>
        <?=$this->GetDateRu("month", date("d.m.Y", $data['obj']->date_release_world));?>
        <?=date("Y", $data['obj']->date_release_world);?>
    </span><br>
    Дата выхода в россии:
    <span>
        <?=date("d", $data['obj']->date_release_russia);?>
        <?=$this->GetDateRu("month", date("d.m.Y", $data['obj']->date_release_russia));?>
        <?=date("Y", $data['obj']->date_release_russia);?>
    </span><br><br><br>
    <?=$data['obj']->text; ?>
</div>

<table class="table-video-screen-game">
    <tr>
        <th>
            Видео трейлер
        </th>
        <th>
            Скриншоты
        </th>
    </tr>
    <tr>
        <td style="padding-right: 5px;">
            <div class="span8 demo-video" style="position: relative; top: 22px;">
                <video class="video-js" controls
                       preload="auto" width="420" height="258" poster="/storage/guide-games/1/dark_souls_ptde_poster_video.jpg" data-setup="{}">
                    <source src="<?=$data['obj']->video_link; ?>" type='video/webm'/>
                </video>
            </div>
        </td>
        <td>
            <div class="left table-screen"><img src="http://upload.wikimedia.org/wikipedia/ru/0/05/Dark_Souls_Cover_Art.jpeg" /></div>
            <div class="left table-screen"><img src="http://upload.wikimedia.org/wikipedia/ru/0/05/Dark_Souls_Cover_Art.jpeg" /></div>
            <div class="left table-screen"><img src="http://upload.wikimedia.org/wikipedia/ru/0/05/Dark_Souls_Cover_Art.jpeg" /></div>
            <div class="left table-screen"><img src="http://upload.wikimedia.org/wikipedia/ru/0/05/Dark_Souls_Cover_Art.jpeg" /></div>
            <div class="left table-screen"><img src="http://upload.wikimedia.org/wikipedia/ru/0/05/Dark_Souls_Cover_Art.jpeg" /></div>
            <div class="table-screen" ><img src="http://upload.wikimedia.org/wikipedia/ru/0/05/Dark_Souls_Cover_Art.jpeg" /></div>
        </td>
    </tr>
<table>

