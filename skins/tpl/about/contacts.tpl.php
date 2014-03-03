<script>
    $(document).ready(function(){
        $('#main').on('click','.btn-contact',function(){
            $("#box-modal-data-gamer").arcticmodal();
            var id = $(this).attr('id');
            $('#submit').on('click',function(){
                var data = $('.myForm').serializeArray();
                $.ajax({
                    type: 'POST',
                    url: document.location.href,
                    data: {'data':data,'id':id},
                    success: function(data){
                        $('#result').html(data).hide().fadeIn(500);
                    }
                })
            })
        });


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
                <?if(!empty($_SESSION["user-data"])){?>
                <textarea type="text" name="text" id="email" cols='50' rows='4' placeholder="Введте текст сообщения"/></textarea><br>
                <a id="submit">Отправить</a>
                <?}else{?>
                <input type="text" name="name" placeholder="Ваше имя"/><br>
                <input type="text" name="email" placeholder="Ваш E-mail"/><br>
                <textarea type="text" name="text" id="email" cols='50' rows='4' placeholder="Введте текст сообщения"/></textarea><br>
                <a id="submit">Отправить</a>
                <?}?>
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
    #submit{background: #1abc9c; color: #ffffff; padding: 7px 20px; text-decoration: none;
     border-radius: 5px;cursor:pointer;display:block;margin-top:10px;width:70px}
    #submit:hover{background: #2fe2bf;}
    #result{color:#1abc9c;font-size:16px;margin-top:10px;}

</style>
<div id="main">
    <? include $_SERVER['DOCUMENT_ROOT']. '/skins/tpl/block/menu-about.block.tpl.php';?>
    <div id="inner-content">
        <h2>Контакты</h2>
        <div class="contact-info">
            <h3>Для прессы:</h3>
            <div class="inner-button">
                <a class="btn-contact" id="2">Связаться</a>

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
                <a class="btn-contact" id="1">Связаться</a>
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
                <a class="btn-contact" id="4">Связаться</a>
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

