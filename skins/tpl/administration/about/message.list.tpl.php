<script>
    $(document).ready(function(){
        $('#click').on('click',function(){
            $('.result').toggle();
        });

    });
    function send(id){
        $.ajax({
            type: 'POST',
            url: document.location.href,
            data: {'id':id}
         })
    }
</script>
<style>
    .contact{}
     #click, #read{cursor:pointer;font-size:16px;color:red;}
     .result{float:left;margin-left:50px;display:none;};
</style>

<h1>Сообщения</h1>
<div class="contact">

    <h3>Непрочитанные сообщения (<?echo $data['count_message'][0][0]?>) <a onclick="send('unread')"  id="click">Показать</a> / <a id="read" onclick="send('read')">Прочитано</a></h3>
    <?foreach($data['message_contact'] as $key => $value){?>
    <div class="result">
        <p><b>Рубрика:</b> <?echo $value['id_rubric']?></p>
        <p><b>ID Пользователя:</b> <?echo $value['id_user']?></p>
        <p><b>Имя:</b> <?echo $value['name_user']?></p>
        <p><b>E-mail:</b> <?echo $value['email']?></p>
        <p><b>Сообщение:</b> <?echo $value['text']?></p>
    </div>
    <?}?>


</div>









