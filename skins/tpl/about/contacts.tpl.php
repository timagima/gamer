<script type="text/javascript" xmlns="http://www.w3.org/1999/html">
    $(document).ready(function(){
        $('#main').on('click','.btn-contact',function(){
            $("#box-modal-data-gamer").arcticmodal();
        });
        $('#submit').on('click',function(){
            var data = $('.myForm, select[name="rubric"]').serializeArray();
            $.ajax({
                type: 'POST',
                url: document.location.href,
                data: {'data':data}
                })
        })

    });

</script>
<div class="hide">
    <div class="box-modal" id="box-modal-data-gamer" style="width: 500px">
        <div class="header-modal">
            <b>Отправить сообщение</b>
            <div class="box-modal_close arcticmodal-close" onclick="closeModalAll()">
                <img src="/skins/img/interface/close-modal.png">
            </div>
        </div>
        <div style="padding:15px; padding-bottom: 45px;">
            <form action="" class="myForm">
                <select name="rubric">
                    <option  value="1" >Сотрудничество</option>
                    <option  value="2">Пресса</option>
                    <option  value="3">Ошибка</option>
                    <option  value="4">Другие вопросы</option>
                </select><br>
                <textarea type="text" name="text" id="email" cols='50' rows='4'/>Написать сообщение</textarea><br>
                <input type="button" id="submit" value="Отправить"/>

            </form>
            <div id="result"></div>
        </div>
    </div>
</div>
<style>
    #main{width:960px;margin:0 auto;}
    #inner-content{margin-left:230px;}
    .contact-info{margin-bottom:30px;}
    .contact-info h3{float:left;margin:0;}
    .inner-text{margin:0 90px 0 180px;}
    .inner-text p{margin:0}
    .inner-button{float:right;}
    .btn-contact{background: #1abc9c; color: #ffffff; padding: 7px 20px; text-decoration: none;
    border-radius: 5px;cursor:pointer;}
    .btn-contact:hover {background: #2fe2bf;}
</style>
<div id="main">
    <? include $_SERVER['DOCUMENT_ROOT']. '/skins/tpl/block/menu-about.block.tpl.php';?>
    <div id="inner-content">
        <h2>Контакты</h2>
        <div class="contact-info">
            <h3>Для прессы:</h3>
            <div class="inner-button">
                <a class="btn-contact">Связаться</a>

            </div>
            <div class="inner-text">
                <p>Текст текст текст текст текст текст текст текст текст текст текст текст текст текст текст текст
                    текст текст текст текст текст текст текст текст текст текст текст текст текст текст текст текст
                    текст текст текст текст текст текст текст текст текст текст текст текст текст текст текст
                </p>
            </div>
        </div>
        <div class="contact-info">
            <h3>Для сотрудничества:</h3>
            <div class="inner-button">
                <a class="btn-contact">Связаться</a>
            </div>
            <div class="inner-text">
                <p>Текст текст текст текст текст текст текст текст текст текст текст текст текст текст текст текст
                    текст текст текст текст текст текст текст текст текст текст текст текст текст текст текст текст
                    текст текст текст текст текст текст текст текст текст текст текст текст текст текст текст
                </p>
            </div>
        </div>
        <div class="contact-info">
            <h3>Другие вопросы:</h3>
            <div class="inner-button">
                <a class="btn-contact">Связаться</a>
            </div>
            <div class="inner-text">
                <p>Текст текст текст текст текст текст текст текст текст текст текст текст текст текст текст текст
                    текст текст текст текст текст текст текст текст текст текст текст текст текст текст текст текст
                    текст текст текст текст текст текст текст текст текст текст текст текст текст текст текст
                </p>
            </div>
        </div>
    </div>
</div>

