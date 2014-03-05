<script>
    $(document).ready(function(){
        $('#main').on('click','.btn-contact',function(){
            $("#box-modal-data-gamer").arcticmodal();
            $('span').removeClass();
                var id = $(this).attr('id');
                    $('#submit').on('click',function(){
                        var data = $('.myForm').serializeArray();
                            if(data.length > 1){
                                if(validName() && validEmail()){
                                    sendMessage(id,data);
                                }
                            }else{
                                sendMessage(id,data);
                            }
                    })
        });
        function sendMessage(id,data){
            $.ajax({
                type: 'POST',
                url: document.location.href,
                data: {'data':data,'id':id},
                dataFilter: function(){
                    $(".myForm").find("input,textarea").val('');
                    $('span').removeClass();
                    $("#modal-result").arcticmodal();
                },
                success: function(){
                    setTimeout(function(){
                        $("#modal-result").arcticmodal('close');
                    },3000);
                }
            })
        }

                /******* Валидация *********/
        function validName(){
            var isValid = false;
            var name = $('#name').val();
            if(name == ''){
                $('#valid-name-result').removeClass().addClass('valid-false');
            }else{
                $('#valid-name-result').removeClass().addClass('valid-true');
                isValid = true;
            }
            return isValid;
        }
        function validEmail(){
            var isValid = false;
            var email = $('#email').val();
            var emailVal = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
            if(email == '' || !email.match(emailVal)){
                $('#valid-email-result').removeClass().addClass('valid-false');
            }else{
                $('#valid-email-result').removeClass().addClass('valid-true');
                isValid = true;
            }
            return isValid;
        }
        $('#name').keyup(validName).focusout(validName);
        $('#email').keyup(validEmail).focusout(validEmail);

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
                <textarea type="text" name="text" id="email" cols='45' rows='4' placeholder="Введте текст сообщения"/></textarea><br>
                <a id="submit">Отправить</a>
                <?}else{?>
                <div class="top-input-contact">
                    <input type="text" id="name"  name="name" placeholder="Ваше имя"/><br>
                    <span id="valid-name-result"></span>
                </div>
                <div class="center-input-contact">
                    <input type="text" id="email" name="email" placeholder="Ваш E-mail"/><br>
                    <span id="valid-email-result"></span>
                </div>
                <textarea type="text" name="text"  cols='50' rows='4' placeholder="Введте текст сообщения"/></textarea><br>
                <a id="submit">Отправить</a>
                <?}?>
            </form>
            <div id="result"></div>
        </div>
    </div>
</div>
<div class="hide">
<div id="modal-result" class="box-modal" style="width: 330px;">
    <div style="padding:15px;">
        <p style="color:#1abc9c;font-size:16px;">Ваше сообщение успешно отправлено</p>
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
    .center-input-contact,.top-input-contact{position:relative;margin-bottom:10px;}
    #valid-name-result, #valid-email-result{position:absolute;left:160px;top:10px;}

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

