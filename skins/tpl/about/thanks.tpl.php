<style>
    #main{width:960px;margin:0 auto;padding-top:10px;}
    h2{text-align:center;margin-top:0;}
    .thanks{margin:20px 40px 0 40px;float:left;position:relative;}
    .info-window{font-size:11px; border: 1px solid #e9e9e9; width: 200px; height:200px; position: absolute !important; z-index: 100; left: 100px; bottom:20px; padding: 15px; background-color: white;display:none}
    .info-window:after{content:"Закрыть";position:absolute;left:180px;top:-2px;cursor:pointer;}



</style>
<script>
    $(document).ready(function(){
        $('#main').on('click','.click-thanks',function(){
            var id = $(this).siblings('div').attr('id');
            $.ajax({
                type: 'POST',
                url: document.location.href,
                data: {"id": id},
                success: function(data){
                    var result = '';
                    var obj = $.parseJSON(data);
                    result = "<img src='/storage"+obj.source_img+"' />" +
                        "<p>Партнер "+obj.name_partner+"</p>" +
                        "<a href='"+obj.link+"'>Подробнее о "+obj.link_anchor+"</a>" +
                        "<p>"+obj.text+"</p>";
                    $('#'+id).html(result).show(500);
                }
            });


        });
        $('body').on('click',function(){
            $('.info-window').hide(500);
        });


    });
</script>
<div id="main">

    <? include $_SERVER['DOCUMENT_ROOT']. '/skins/tpl/block/menu-about.block.tpl.php';?>
    <h2>Хотим выразить огромную благодарнсть нашим партнёрам</h2>
    <div class="thanks">
        <img src="/storage/thanks/vk-thanks.png" class="click-thanks" />
        <div class="info-window"  id="vkontakte" ></div>
    </div>
    <div class="thanks">
        <img src="/storage/thanks/Yt-thanks.png" class="click-thanks " />
        <div class="info-window"  id="youtube" ></div>
    </div>
    <div class="thanks">
        <img src="/storage/thanks/gg-thanks.png"  class="click-thanks" />
        <div class="info-window"  id="google" ></div>
    </div>








</div>