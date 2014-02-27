<style>
    #main{width:960px;margin:0 auto;overflow:hidden;padding-top:10px;}
    h2{text-align:center;margin-top:0;}
    .thanks{position:relative;margin:20px 40px 0 40px;float:left;}
    .thanks img{cursor:pointer;}
    .hide{display:none;}
    .vkontakte, .youtube,.google{font-size:11px; border:1px solid #e9e9e9; position: absolute !important;
    z-index: 100;  text-align: left; padding: 15px; background-color: white;
    left:100px;top:-60px;width:200px;height:100px;overflow:hidden;}
</style>
<script>
    function showContent(param){
        $('.'+param).css('height',200);
    }
    function showInfoThanks(param){
        if(param == "out"){
            $(".vkontakte,.google,.youtube").css('height','100').hide();
        }else{
            $.ajax({
                type: 'POST',
                url: document.location.href,
                data: {"id": param},
                success: function(data){
                    var result = '';
                    var obj = $.parseJSON(data);
                    result = "<img src='/storage"+obj['source_img']+"' />" +
                        "<p>Партнер "+obj['name_partner']+"</p>" +
                        "<a href='"+obj['link']+"'>Подробнее о "+obj['link_anchor']+"</a>" +
                        "<p>"+obj['text']+"</p>";
                    $('.'+param).show().html(result);
                }
            });
         }
   }

</script>
<div id="main">

    <? include $_SERVER['DOCUMENT_ROOT']. '/skins/tpl/block/menu-about.block.tpl.php';?>
    <h2>Хотим выразить огромную благодарнсть нашим партнёрам</h2>
    <dl class="thanks" onmouseover="showInfoThanks('vkontakte')" onmouseout="showInfoThanks('out')" onclick="showContent('vkontakte')">
        <dt><img src="/storage/thanks/vk-thanks.png" /></dt>
        <dd class="vkontakte hide">Тра</dd>
    </dl>
    <dl class="thanks" onmouseover="showInfoThanks('youtube')" onmouseout="showInfoThanks('out')" onclick="showContent('youtube')">
        <dt><img src="/storage/thanks/Yt-thanks.png"  /></dt>
        <dd class="youtube hide">Тра</dd>
    </dl>
    <dl class="thanks" onmouseover="showInfoThanks('google')" onmouseout="showInfoThanks('out')" onclick="showContent('google')">
        <dt><img src="/storage/thanks/gg-thanks.png"  /></dt>
        <dd class="google hide">Тра</dd>
    </dl>





</div>