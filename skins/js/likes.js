function likes(){
    var self = this;
    likes.prototype.init = function(){
        $(".like").click( function(e){
            self.vote(e, "Like");
        });

        $(".dislike").click( function(e){
            self.vote(e, "Dislike");
        });
    }

    likes.prototype.vote = function(e, method){
        var parentElement = $(e.currentTarget).parent();
        var currentElement = $(e.currentTarget);
        var fieldIdArray = ($(e.currentTarget).parent().attr("id")).split("-");
        var classes = ($(e.currentTarget).parent().attr("class")).split(" ");
        var voted = (classes[1] === "voted" ) ? "true" : "false";
        $.ajax({
            type: 'POST',
            url: document.location.href,
            dataType: 'html',
            data: {"method": method, "voted": voted, "likes-group":fieldIdArray[0], "record":fieldIdArray[1]},
            success: function(data){
                if( data==="liked" ){
                    $(parentElement).attr("class", "likes voted");
                    $(currentElement).attr("class", "like liked");
                    $(parentElement).find(".dislike").attr("class", "dislike");
                }
                if( data==="disliked" ){
                    $(parentElement).attr("class", "likes voted");
                    $(currentElement).attr("class", "dislike disliked");
                    $(parentElement).find(".like").attr("class", "like");
                }
            }
        });
    }

    this.init();
}

function initLikes(){
    new likes();
}

