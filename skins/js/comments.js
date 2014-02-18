$(document).ready(function(){

    var month = new Array('Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря');

    listComment();

    function renderMenu(){
        // нужно проверять кому принадлежит комментарий
        var sessionUser =  "<?= $this->user['id']?>";

        $(".icon-menu-comment").mouseover(function(){
            if($(".menu-comment").length == 0){
                var userComment = $(this).parent(".user-comment").attr("class").split("-");
                var fieldRemove = (sessionUser == userComment[3]) ? '<div class="menu-comment-remove">Удалить</div>' :  '<div class="menu-comment-spam">Спам</div>';
                var fieldAnswer = (sessionUser == userComment[3]) ? '' :  '<div class="menu-comment-answer">Ответить</div>';
                var res = '<div class="menu-comment">' + fieldAnswer + fieldRemove;
                //'<div class="menu-comment-quote">Цитировать</div>' +
                //'<div class="menu-comment-quote">Редактировать</div>' +
                //'<div class="menu-comment-like">Нравиться</div>' +
                //'<div class="menu-comment-dislike">Не нравиться</div>' + fieldRemove +
                //'<div style="padding-top: 5px;">Все ответы (0)</div></div>';
                $(this).append(res);
                var idComment = $(this).parent(".user-comment").attr("id").split("-");

                $(".menu-comment-remove").click(function(){
                    $("#user-comment-"+idComment[2]).remove();
                    $.ajax({
                        url:  document.location.href,
                        type: 'POST',
                        data:{'ajax-query': 'true', 'type-class':'model', 'method': 'RemoveComment', 'id': idComment[2]},
                        dataType: 'html'
                    });
                })

                $(".menu-comment-spam").click(function(){
                    $("#user-comment-"+idComment[2]).html("Вы отметили данное сообщение как спам");
                    /*$.ajax({
                     url:  document.location.href,
                     type: 'POST',
                     data:{'ajax-query': 'true', 'type-class':'model', 'method': 'RemoveComment', 'id': idComment[2]},
                     dataType: 'html'
                     });*/
                })
                $(".menu-comment-answer").click(function(){
                    $("#text-comment").focus();
                    var nick = $("#user-nick-"+idComment[2]).text();
                    var res = 'Ответ пользователю <b>'+ nick +'</b>' +
                        '<input type="hidden" id="user-comment" value="'+userComment[3]+'" />' +
                        '<input type="hidden" id="id-comment" value="'+idComment[2]+'" />' +
                        '<a href="javascript:void(0)" id="close-answer" style="color: black; padding: 0 0 0px 5px; position: relative; bottom: 8px;left: 5px;">X</a>';
                    $("#action-answer").removeClass("hide").html(res);
                    $("#close-answer").click(function(){
                        $("#text-comment").focus();
                        $("#action-answer").addClass("hide").html("");
                    })
                })

            }
        }).mouseleave(function(){
                if(!$(".menu-comment").is(":hover")){
                    $(this).html('');
                }
            })
    }

    function listComment(){
        $.ajax({
            url:  document.location.href,
            type: 'POST',
            data:{'ajax-query': 'true', 'type-class':'model', 'method': 'ListComments'},
            dataType: 'html',
            success: function (result){
                renderComment(result, true);
                renderMenu();
            }
        });
    }

    function renderComment(result, param){
        var res = $.parseJSON(result); // тестовое изменение
        var result = '';
        // здесь необходимо сделать проверку если есть
        for(var k in res){
            //debugger;
            var imgAvatarAnswer = (res[k].img_avatar_answer == null || res[k].img_avatar_answer == "") ? '/skins/img/m.jpg' : res[k].img_avatar_answer;
            var imgAvatar = (res[k].img_avatar == null || res[k].img_avatar == "") ? '/skins/img/m.jpg' : res[k].img_avatar;
            var nickAnswer = (res[k].nick_answer == null) ? 'Анонимный' : res[k].nick_answer;
            var nick = (res[k].nick == null) ? 'Анонимный' : res[k].nick;
            var userAnswer = (res[k].id_user_answer != 0) ?
                '<img src="/skins/img/interface/comment-answer.png" style="position: relative; bottom: 5px; left: 30px;" /></td>' +
                    '<td style="width: 75px"><img src="' + imgAvatarAnswer + '" class="avatar-comment" /></td>' +
                    '<td class="info-comment" style="width:1px; padding-right: 20px;"><b id="user-nick-'+res[k].id+'">'+nickAnswer+'</b>' +
                    '</td>' : '</td>';
            var date = new Date(res[k].date * 1000);
            result += '<div style="padding: 15px 0;" class="user-comment user-comment-'+res[k].id_user+'" id="user-comment-' + res[k].id + '">' +
                '<table class="left table-comment" style="width: 940px;"><tr>' +
                '<td style="width: 75px;"><img src=' + imgAvatar + ' class="avatar-comment" /></div></td>' +
                '<td class="info-comment"><b id="user-nick-'+res[k].id+'">'+nick+'</b><br>' +
                '<span>'+ date.getDate() + " " + month[date.getMonth()] + " " + date.getHours() + ":" + date.getMinutes() +'</span>' +
                userAnswer +
                '<td><div class="text-comment"><span>'+res[k].comment+'</span></div></td></tr></table>' +
                '<?php if($_SESSION['auth'] == 1){?><div class="right icon-menu-comment"></div><?}?>' +
            '</div><br class="clear">';
        }
        (param == "last-msg") ? $(".content-comment").append(result) : $(".content-comment").html(result);
    }

    $("#send-comment").click(function(){
        var comment = $.trim($('#text-comment').val());
        var idUserAnswer = ($('#user-comment').val() == undefined) ? 0 : $('#user-comment').val();
        if (comment != "" && comment.length > 2){
            $.ajax({
                url:  document.location.href,
                type: 'POST',
                data:{'ajax-query': 'true', 'type-class':'model', 'method': 'AddComment', 'id-user-answer': idUserAnswer, 'comment': comment, 'id-section': 3},
                dataType: 'html',
                success: function (result){
                    $("#text-comment").val('');
                    $("#action-answer").remove();
                    renderComment(result, "last-msg");
                    renderMenu();
                }
            });
        }else{
            alert("Введите комментарий");
        }
    })
})