<script type="text/javascript">
    $(document).ready(function(){
        var current = 3;
        $('#select').find('a').on('click',function(){
            var direction = $(this).attr('id');
            if(direction === 'next-btn')
                ++current;
            else
                --current;
            var link = '';
            switch(current){
                case 2: link = '?page=next-tournament-promo';break;
                case 3: link = '?page=winner-promo';break;
                default: link = '/about/promo';}
            if(current == 4){
                current = '?page=winner-promo';
            }else{
                current = link;

                history.pushState(current,null,current);
            }
            $.ajax({
                type: 'GET',
                url: document.location.href,
                data: {"page":current},
                success: function(data){
                    $('#main').html(data);
                }
            });

        });
        window.onpopstate = function(e){
            var str = JSON.stringify(history);
            var n1 = str.indexOf(":");
            var n2 = str.lastIndexOf("n");
            var newStr = str.substring(n1+2,n2-5);
            $.ajax({
                type: 'GET',
                url: document.location.href,
                data: {"page": newStr},
                success: function(data){
                    $('#main').html(data);
                }
            })
        }
    })
</script>
<style>
    #main{width:960px;margin:0 auto;}
    #slider{margin-top:40px;position:relative;background:url(/skins/img/shadow.png) right 1px top 160px no-repeat;overflow:hidden;}
    #internal-slider{width:930px;margin:auto;}
    #social-buttons{position:absolute;right:38px;top:30px;}
    #social-buttons a{margin-left:10px;}
    #select{position:absolute;right:1px;top:173px;}
    #prev-btn{display:block;width:60px;height:59px;background:url(/skins/img/prev.png)no-repeat;float:left;cursor:pointer;}
    #next-btn{display:block;width:57px;height:59px;background:url(/skins/img/next.png)no-repeat;margin-left:59px;cursor:pointer;}
    .profile-winner{margin-top:50px;overflow:hidden;padding:0 15px 0 15px;text-align: center;}
    .profile-winner p{font-size:11px;margin:0 0 2px 0;line-height: 1.5;}
    .profile-winner p + p{margin-top:20px;}
    .profile-winner p a{font-size:16px;color:#0bad98;}
    .left-profile, .center-profile, .right-profile{width:277px;border:1px solid #807e7e;border-radius:3px;}
    .center-profile{margin:0 325px 0 329px;}
    .right-profile{float:right;}
    .left-profile{float:left;}
    .content-profile{padding:5px 15px 15px;}
    .content-profile p + p{margin:0;}
    .content-profile p a{font-size:11px;}
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
                <a id="prev-btn"></a>
                <a id="next-btn"></a>
            </div>
            <img src="/skins/img/slider3.jpg" alt="slider"/>
        </div>

    </div>
    <div class="profile-winner">
        <div class="left-profile">
            <img src="/skins/img/profile1.jpg" />
                <p><a href="#">Имя Фамилия</a></p>
                    <span>НИК</span>
                <div class="content-profile">
                <p>24 года, г. Пермь. Победил в турнире по игре.
                Название игры. Получил приз 1000 р.</p>
                <p>Вот, что говорит победитель <a href="#">Ссылка на видео</a></p>
                </div>
        </div>

        <div class="right-profile">
            <img src="/skins/img/profile1.jpg" />
                <p><a href="#">Имя Фамилия</a></p>
                    <span>НИК</span>
                <div class="content-profile">
                <p>24 года, г. Пермь. Победил в турнире по игре.
                Название игры. Получил приз 1000 р.</p>
                <p>Вот, что говорит победитель <a href="#">Ссылка на видео</a></p>
                </div>
        </div>
        <div class="center-profile">
            <img src="/skins/img/profile2.jpg" />
                <p><a href="#">Имя Фамилия</a></p>
                    <span>НИК</span>
                <div class="content-profile">
                <p>24 года, г. Пермь. Победил в турнире по игре.
                Название игры. Получил приз 1000 р.</p>
            <p>Вот, что говорит победитель <a href="#">Ссылка на видео</a></p>
                </div>
        </div>
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