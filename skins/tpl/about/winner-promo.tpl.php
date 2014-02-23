
<div id="main">
   <div id="slider">
        <div id="internal-slider">
            <div id="social-buttons">
                <a href="#"><img src="/skins/img/youtube.png" alt="YouTube" /></a>
                <a href="#"><img src="/skins/img/google-plus.png" alt="Google-plus" /></a>
                <a href="#"><img src="/skins/img/skype.png" alt="Skype" /></a>
                <a href="#"><img src="/skins/img/vk.png" alt="Vkontakte" /></a>
                <a href="#"><img src="/skins/img/fb.png" alt="FaceBook" /></a>
                <a href="#"><img src="/skins/img/tw.png" alt="Twitter" /></a>
            </div>
            <div id="select">
                <a id="prev-btn"></a>
                <a id="next-btn" class="slider-action" page="next-tournament"></a>
            </div>
            <img src="/skins/img/slider3.jpg" alt="slider"/>
        </div>

    </div>

   <div class="profile-winner">
        <?$cssClass = array("left-profile","center-profile","right-profile");
            foreach($data['users_winner'] as $key => $value){?>
            <div class="<?echo $cssClass[$key];?>">
                <img src="<?echo $value['img_avatar'];?>" />
                <p><a href="#"><?echo $value['first_name']," ".$value['last_name']?></a></p>
                <span><?echo $value['nick']?></span>
                <div class="content-profile">
                    <p>24 года, г. Пермь. Победил в турнире по игре.
                        Название игры. Получил приз 1000 р.</p>
                    <p>Вот, что говорит победитель <a href="#">Ссылка на видео</a></p>
                </div>
            </div>
        <? } ?>
   </div>


    <div class="middle-content">
        <div class="left-middle">
            <span>Участников</span>
            <img src="/skins/img/inquiries.png"/>
            <p>+350</p>
        </div>
        <div class="right-middle">
            <a href="#">Принять участие</a>
        </div>
        <div class="center-middle">
            <img src="/skins/img/payment.jpg"/>
        </div>
    </div>
    <div class="bottom-content">
        <p>Все условия и правила ближайшего турнира читайте на нашем сайте <a href="#">http://gs11.ru/tournament</a>.
            Участие в турнирах возможно только в том случае, если вы зарегистрировались на сайте <a href="/">GS11</a>. Это дает вам возможность
            не только участвовать в турнирах, но и вести блоги по пройденным играм, читать новости всей игровой индустрии и многое другое... </p>
    </div>
</div>