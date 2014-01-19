
<style>
    #main{width:960px;margin:0 auto;}
    #slider{margin-top:40px;position:relative;background:url(/skins/img/shadow.png) right 1px top 160px no-repeat;overflow:hidden;}
    #internal-slider{width:930px;margin:auto;}
    #social-buttons{position:absolute;right:38px;top:30px;}
    #social-buttons a{margin-left:10px;}
    #select{position:absolute;right:1px;top:173px;}
    #prev-btn{display:block;width:60px;height:59px;background:url(/skins/img/prev.png)no-repeat;float:left;}
    #next-btn{display:block;width:57px;height:59px;background:url(/skins/img/next.png)no-repeat;margin-left:59px;}
    #bottom-select{width:99px;margin:25px auto;}
    #left-dot, #center-dot, #right-dot{display:block;width:19px;height:19px;float:left;margin:0 7px;}
    #left-dot{background:url(/skins/img/active-dot.png)no-repeat;}
    #center-dot{background:url(/skins/img/inactive-dot.png)no-repeat;}
    #right-dot{background:url(/skins/img/inactive-dot.png)no-repeat;}
    .top-content{margin-top:35px;overflow:hidden;}
    .left-content{padding-left:25px;float:left;width:650px;}
    .right-content{margin-left:675px;padding:10px 0 0 40px;}
    .left-content h2{color:#f93c18;font-size:35px;line-height:1.2;font-weight:600;margin:0;}
    .left-content p{font-size:21px;line-height:1.5;color:#3a4e70;margin-bottom:0; }
    .left-content a{display:block;backgroud:#2483cb;width:99px;text-decoration:none;color:#fff;
        font-size:18px;padding:8px 23px 8px 33px;border-radius:3px;float:right;background:#2483cb;}
    .middle-content{margin-top:70px;overflow:hidden;}
    .left-middle{float:left;padding-left:25px;}
    .left-middle > span{display:block;color:#b8bec7;margin-bottom:10px;}
    .left-middle p{display:inline;color:#1fbba6;font-size:35px;margin-left:15px;}
    .right-middle{float:right;padding:30px 25px 0 0;}
    .right-middle a{display:block;text-decoration:none;color:#fff;font-size:25px;
        padding:12px 30px;border-radius:3px;background:#e86950;}
    .center-middle{margin-left:340px;}
    .bottom-content{margin:50px 150px 40px 80px;}
    .bottom-content p{color:#384c6e;font-size:12px;padding:}
    .bottom-content a{color:#68bca7;}

</style>

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
                <a href="#" id="prev-btn"></a>
                <a href="#" id="next-btn"></a>
            </div>
            <img src="/skins/img/slider1.jpg" alt="slider"/>
        </div>
        <div id="bottom-select">
            <a href="#" id="left-dot"></a>
            <a href="#" id="center-dot"></a>
            <a href="#" id="right-dot"></a>
        </div>
    </div>
    <div class="top-content">
        <div class="left-content">
            <h2>Максимальный призовой фонд<br> за минимальную стоимость участия</h2>
            <p>Текст о том, как проводятся турниры по легендарным играм, какая стоимость участия
                и правила турниров. Текст о том, как проводятся турниры по легендарным играм, какая стоимость участия
                и правила турниров.</p>
            <a href="#">Подробнее</a>
        </div>
        <div class="right-content">
            <img src="/skins/img/sticker.jpg" alt="Только лучшее"/>
        </div>
    </div>
    <form action="foo.php" method="post">
        <input type="text" name="username" /><br />
        <input type="text" name="email" /><br />
        <input type="submit" name="submit" value="Отправить" />
    </form>
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