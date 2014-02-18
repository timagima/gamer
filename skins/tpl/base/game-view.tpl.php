<script type="text/javascript" src="/skins/js/validation.js"></script>
<style type="text/css">
    .likes{
        cursor: pointer;
        font-weight: bold;
    }
    .like:hover {
        color: green;
    }
    .dislike:hover{
        color: red;
    }
    .liked{
        color: green;
        cursor: default;
    }
    .disliked{
        cursor: default;
        color: red;
    }
    .avatar-comment{width: 75px; height: 75px; border-radius: 5px;}
    .table-comment td{}
    .info-comment{width: 130px; padding-right: 20px;}
    .info-comment b{position: relative; left: 10px;}
    .info-comment span{position: relative; left: 10px; color: #7f8c8d; font-size: 12px;}
    .text-comment{padding-right: 20px; max-width: 675px;}
    .menu-comment{border: 1px solid #e9e9e9; padding: 7px; font-size: 12px; position: absolute; right: 19px; background-color: #ffffff; width: 100px; z-index: 100}
    .icon-menu-comment{position: relative; width: 19px; height: 15px; cursor: pointer; background-image: url("/skins/img/interface/icon-menu-comment.png"); background-repeat: no-repeat}
    .menu-comment div{cursor: pointer;}
    .menu-comment-answer{background-image: url("/skins/img/interface/menu-comment.png"); background-repeat: no-repeat; background-position: 0px 5px; padding-left: 15px;}
    .menu-comment-quote{background-image: url("/skins/img/interface/menu-comment.png"); background-repeat: no-repeat; background-position: 0px -18px; padding-left: 15px;}
    .menu-comment-like{background-image: url("/skins/img/interface/menu-comment.png"); background-repeat: no-repeat; background-position: 0px 5px; padding-left: 15px;}
    .menu-comment-dislike{background-image: url("/skins/img/interface/menu-comment.png"); background-repeat: no-repeat; background-position: 0px 5px; padding-left: 15px;}
    .menu-comment-remove{background-image: url("/skins/img/interface/menu-comment.png"); background-repeat: no-repeat; background-position: 0px -61px; padding-left: 15px;}
    .menu-comment-spam{background-image: url("/skins/img/interface/menu-comment.png"); background-repeat: no-repeat; background-position: 0px -83px; padding-left: 15px;}
    #send-comment{margin-left: 10px; background: #5cade2 !important; margin-right: 10px;}
    #text-comment{width: 99%; resize: none;  margin-top: 15px; z-index: 9}
    #action-answer{background-color: gray; margin-left: 160px; padding: 8px; opacity: 0.5;}
</style>
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
                                    <?php
                                    $color = ($data['ucg-likes']['likes']>0)?"green":"";
                                    $color = ($data['ucg-likes']['likes']<0)?"red":$color;
                                    ?>

                                    <p class="likes<?=( $data['user-likes']!==false ) ? ' voted' : ''?>" id="2-<?=$data['id_ucg']?>">
                                        <span class="rating" style="color:<?=$color?>;">
                                            <?=($data['ucg-likes']['likes']>0 || $data['ucg-likes']['likes']<0) ? $data['ucg-likes']['likes'] : "0"?>
                                        </span>
                                        <?php if($data['id_user']!==$_SESSION['user-data']['id']){?>
                                        <span class="like<?=( $data['user-likes']['likes']==="1" ) ? ' liked' : ''?>">Like</span>
                                        <span class="dislike<?=( $data['user-likes']['dislikes']==="1" ) ? ' disliked' : ''?>">Dislike</span>
                                        <?php } ?>
                                    </p>


                                </div>
                                <!-- Подключение комментариев -->
                                <div id="<?=$data['id_ucg']?>-3-GetUserLikesCommentsUCG">
                                    <div class="br-points"></div>
                                    <h3>Ответы</h3>
                                    <div class="content-comment"></div>
                                    <br class="clear">

                                    <?php if($_SESSION['auth'] == 1){?>
                                        <a href="javascript:void(0)" id="send-comment" class="left btn">Отправить</a>
                                        <div id="action-answer" class="hide right"></div>
                                        <textarea id="text-comment"></textarea>
                                    <?}else{
                                        echo "<h3>Комментарии могут оставлять только зарегистрированные пользователи</h3>";
                                    }?>
                                </div>
                                <!-- Конец комментариев -->

                            </div>

                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        initLikes();
    });
</script>