$(document).ready(function() { 
    $(document).on("click","#likeButton", function(){ 
        $("#messageServer").empty();
        likingContent($(this).attr('data-url'));
    });
});

const likingContent = url => {  
    $.ajax({ 
        type: 'POST',       
        url: url,
        success: function (data) { 
            $("#likeButton").html(data.nbLikes.nbLikes);
        },       
        error:function (jqXHR, exception) {
            $("#messageServer").html("<i class='fas fa-times'> En raison d\'une erreur survenue, le like n'a pas été enregistré!.</i>");
            $("#messageServer").attr("class","error");
        }
    });
}

function newFunction(data) {
    alert(data.length);
}
