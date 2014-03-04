<? $path = $_SERVER["DOCUMENT_ROOT"];?>
<!DOCTYPE html>
<html>
<head>
    <title><?=$headerTxt['title']; ?></title>
    <meta name="keywords" content="<?=$headerTxt['keywords']; ?>" />
    <meta name="description" content="<?=$headerTxt['description']; ?>" />
    <link type="text/css" rel="stylesheet" href="/skins/css/style.css"/>
    <link type="text/css" rel="stylesheet" href="/skins/css/video-js.css"/>

    <script type="text/javascript" src="/skins/js/jquery.min.js"></script>
    <script type="text/javascript" src="/skins/js/script.js"></script>
    <script type="text/javascript" src="/skins/js/script-admin.js"></script>
    <script type="text/javascript" src="/skins/js/arctic-modal.min.js"></script>
    <script type="text/javascript" src="/skins/js/jquery.autocomplete.js"></script>
    <script type="text/javascript" src="/skins/js/jquery.customSelect.js"></script>
    <script type="text/javascript" src="/skins/js/service.js"></script>
    <script type="text/javascript" src="/skins/js/multiupload.js"></script>
    <script type="text/javascript" src="/skins/js/likes.js"></script>
    <script type="text/javascript" src="/skins/js/comments.js"></script>
    <script type="text/javascript" src="/skins/js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="/skins/js/tinymce/jquery.tinymce.min.js"></script>



</head>
<style>
    .footer-txt{margin-left: 18px; color: #878787; font-size: 13px;}
    .footer-txt a{color: #878787; text-decoration: none;}
    #footer{padding-top: 10px;}
    .footer-logo{position: relative; top:3px;}
    .footer-certificate{position: relative; bottom:2px; height: 40px;}
</style>
<script>
$(document).ready(function(){
    $('#remember-check').toggleClick(function(){
        $('#remember-check').html('<img src="/skins/img/interface/check-remember-enabled.png" /> <span style="color: #34c0a3">Запомнить</span><input type="hidden" id="remember" value="true">')
    }, function(){
        $("#remember").remove();
        $('#remember-check').html('<img src="/skins/img/interface/check-remember-disabled.png" /> <span>Запомнить</span>')
    })
})

    </script>
<body>
<div id="header">
    <div class="left"  style="width: 505px;">
        <a href="/">
            <img src="/skins/img/logo.png" alt="Главная страница GS11" title="Главная страница GS11"/>
        </a>
    </div>
</div>
<div class="clear"></div>
<? include $menuView; ?>
<div class="clear"></div>
<? include $contentView; ?>
<div class="clear"></div>
<div id="footer">
    <img src="/skins/img/logo-footer.png" alt="GS11" class="left footer-logo"/>
    <div class="footer-txt left">
        <span>
            <a href="/about">О нас</a> |
            <a href="/about/help">Помощь</a> |
            <a href="/about/games-forever">Игры навсегда</a> |
            <a href="/about/gratitude">Благодарности</a></span><br>
        <span>&copy; Ltd GS11, 2013. Все наши услуги не облагаются НДС.</span>
    </div>
    <a href="/about/certificate">
        <img class="right footer-certificate" src="/skins/img/certificate.png" alt="Сертификат GS11" title="Сертификат GS11"/>
    </a>
<div>
<div class="clear"></div>
</body>
</html>