function likes(){
    var self = this;
    likes.prototype.init = function(){
        $(".like").click( function(){
            var tableIdArray = ($(".like").parent().attr("id")).split("-");
            var classes = ($(".like").parent().attr("class")).split(" ");

        });

        $(".dislike").click( function(){
            var tableIdArray = ($(".like").parent().attr("id")).split("-");
            var classes = ($(".like").parent().attr("class")).split(" ");

        });
    }

    this.init();
}

function initLikes(){
    new likes();
}

