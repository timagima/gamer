function multiUploader(config){
    this.config = config;
    this.items = "";
    this.all = [];
    var self = this;
    multiUploader.prototype.init = function(){
        if (window.File && window.FileReader && window.FileList && window.Blob) {
            $("#"+this.config.form).find("input[type='file']").on("change", this.read);
            $("#"+ this.config.dragArea).on("dragover", function(e){ e.stopPropagation(); e.preventDefault(); });
            $("#"+ this.config.dragArea).on("drop", this._dropFiles);
            if(this.config.img){
                $('body').on("mouseover", this.editImage);
            }
        }
    }


    multiUploader.prototype.read = function(evt){
        self.idFileInput = $(this).attr("id");
        self.idDivParent = $(this).parent().attr("id");
        if(evt.target.files){
            this.items = evt.target.files;
            self.all.push(evt.target.files);
            self._startUpload();
        } else
            console.log("Failed file reading");
    }


    multiUploader.prototype._validate = function(format){
        var arr = this.config.support.split(",");
        return arr.indexOf(format);
    }

    multiUploader.prototype._dropFiles = function(e){
        e.stopPropagation(); e.preventDefault();
        self._preview(e.dataTransfer.files);
        self.all.push(e.dataTransfer.files);
    }

    multiUploader.prototype._uploader = function(file,f){
        // todo: сделать возможность загрузки формы для нескольких инпутов сейчас идет строго по тегу name file и только с одного инпута на странице можно загружат это нужно исправить
        var data = new FormData();
        var ids = file[f].name._unique();
        self.number = f;
        data.append(self.idFileInput, file[f]);
        data.append('index',ids);
        data.append('method', self.config.method);
        $(".dfiles[rel='"+ids+"']").find(".progress").show();
        $.ajax({
            type:"POST",
            url:this.config.uploadUrl,
            xhr: function () {
                var myXhr = $.ajaxSettings.xhr();
                self.xhr();
                if (myXhr.upload) {
                    myXhr.upload.addEventListener('progress', self.progressHandlingFunction, false);
                }
                return myXhr;
            },
            data:data,
            cache: false,
            contentType: false,
            processData: false,
            success:function(data){
                var arrFile = $.parseJSON(data);
                $("#error-img").remove();
                if(arrFile.length <= 1){
                    $("#"+ self.idDivParent).after('<div id="error-img" class="right error">'+arrFile[0]+'</div>');
                } else {
                    var arrFile = $.parseJSON(data);

                    // todo: подумать над возможностью отправлять в базу hidden с нужным значением
                    // todo: подумать над загружать несколько файлов т.к. сейчас поле загрузки будет удаляться
                    //var storage = 0;
                    $("#stroage").remove();
                    $("#"+ self.idDivParent).after("<div class='edit-image'><img src='/"+arrFile[0]+"' /><input type='hidden' name='"+arrFile[1]+"' id='"+arrFile[1]+"' value='"+arrFile[0]+"' /></div></div>");
                    $("#"+self.idDivParent).remove();


                    /*var obj = $(".dfiles").get();
                     $.each(obj,function(k,fle){
                     if($(fle).attr("rel") == rponse){
                     $(fle).slideUp("normal", function(){ $(this).remove(); });
                     }
                     });*/
                }
            }
        });
        if (f+1 < file.length) {
            self._uploader(file,f+1);
        }
    }
    multiUploader.prototype.editImage = function(){
        $('.edit-image').mouseover(function(){
            if($("#delete-images").length == 0){
                var obj = $(this);
                var $delButton = $('<div/>', {id: "delete-images"}).click(function() {
                    self.deleteImg(obj);
                });
                var widthIconDel = $(this).find('img').width()-15;
                $('.edit-image').css({"width":$(this).find('img').width()+"px"});
                $(this).find('img').before($delButton);
                $(this).find("#delete-images").fadeIn(200).css({'left': widthIconDel+'px'});
            }
            $('#delete-images').mouseover(function(){
                $('#delete-images').stop().animate({opacity: 1}, 200);
            }).mouseleave(function(){
                    $('#delete-images').stop().animate({opacity: 0.6}, 200);
                })
        }).mouseleave(function(){
                $("#delete-images").remove();
            })
        self.all = [];
    }

    multiUploader.prototype.deleteImg = function(link){
        var val = $(link).find("input[type=hidden]").attr("name");
        if(val == "source_img_s"){
            var name = Array("Иконка", "icon-upload-btn");
        } else {
            var name = Array("Изображение", "img-upload-btn");
        }
        $("#"+self.idDivParent).show();
        $(link).after('<div id="'+name[1]+'" class="container upload"><span class="btn">'+name[0]+'</span><input type="file" name="'+val+'" id="'+val+'" /></div>');
        $(link).remove();
        this.init();
    }

    multiUploader.prototype.xhr = function(){
        if(self.config.visualProgress == "modal"){
            showModal('upload-process-ajax-modal');
            $("#upload-process").html('<div class="progress_container"><div class="progress_bar tip"></div></div>');
        }else{
            $("#upload-process-ajax-modal").html('<div class="info-ajax-modal" ><div class="progress_container">' +
                '<div class="progress_bar tip"></div></div></div>');
        }
        $(".progress_container").css("margin","10px 0");
    }

    multiUploader.prototype._startUpload = function(){
        if(this.all.length > 0){
            for(var k=0; k<this.all.length; k++){
                var file = this.all[k];
                this._uploader(file,0);
            }
        }
    }
    multiUploader.prototype.progressHandlingFunction = function(e){
        if (e.lengthComputable) {
            var percentComplete = parseInt((e.loaded / e.total) * 100);
            $('.tip').animate({width: percentComplete + "%"}, 10);
            if(percentComplete == 100){
                $.arcticmodal('close');
                setInterval(function(){
                    $(".progress_container").remove();
                }, 500)

            }
        }
    }

    String.prototype._unique = function(){
        return this.replace(/[a-zA-Z]/g, function(c){
            return String.fromCharCode((c <= "Z" ? 90 : 122) >= (c = c.charCodeAt(0) + 13) ? c : c - 26);
        });
    }

    this.init();
}

function initMultiUploader(){
    new multiUploader(config);
}

