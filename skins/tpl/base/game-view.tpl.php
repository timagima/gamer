<script type="text/javascript" src="/skins/js/validation.js"></script>
<div id="featured-section" style="position: relative; z-index: 10;" class="FL">
    <table>
        <tr>
            <td style="vertical-align: top;">
                <div id="content" style="width: 668px;">

                    <div id="content-profile">

                        <div class='left'>

                            <div class="game-content">
                                <h2><?=$data['game']?></h2>
                                <div class="game-poster">
                                    <img src="/storage<?= $data['source_img_b'] ?>" style="float:left; margin:0 10px 10px 0"/>
                                    <h4>Жанр: <?= $data['genre'] ?></h4>
                                    <h4>Описание игры:</h4>
                                    <p>
                                        <?= $data['about_game'] ?>
                                    </p>
                                </div>

                                <div class="game-info" style="clear:both;">
                                    <p>Начало прохождения:<?= date("d.m.Y",$data['start_date']) ?></p>
                                    <p>Конец прохождения:<?= date("d.m.Y",$data['end_date']) ?></p>
                                    <p>Уровень сложности:<?= $data['level'] ?> -> <?= $data['level_description'] ?></p>
                                    <p>Качество прохождения: <?= $data['type_complete_game'] ?></p>
                                    <p>Количество квестов: <?= $data['num_quest'] ?></p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>