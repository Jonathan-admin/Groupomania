// Fonctions mère

$(document).ready(function() { 
    $(document).on("click","i[class*=thumbs]:not('.disabled')", function(){ 
        $("#messageServer").empty();
        likingContent($(this).attr('data-url'),$(this).attr('data-role'),$(this));
    });
    $(document).on("click","#createComment-button", function(){ 
        $("#viewWriting").css("display","block");
        $("#viewWriting").next().css("display","none");
        $("#sendComment-button").prop( "disabled", true );
    });
    $(document).on("click","#cancelComment-button", function(){ 
        $("#viewWriting").css("display","none");
        $("#viewWriting").next().css("display","block");
        $("#comment").val("");
    });
    $(document).on("click","#sendComment-button", function(){ 
        createComment($(this).attr('data-url'),$("#comment").val());
    });
    $(document).on("click",".fa-trash-alt", function(){ 
        deleteComment($(this).attr('data-url'));
    });
});


/**
 * Gerer le système de like 
 */
const likingContent = (url,role) => {  
    $.ajax({ 
        type: 'POST',       
        url: url,
        data: {
            role: role
        },
        success: function (data) { 
            $("#sys-like").html(data).attr("data-user", data.contentUser);
        },       
        error:function () {
            $("#messageServer").html("<i class='fas fa-times'> En raison d\'une erreur survenue, le like n'a pas été enregistré!.</i>");
            $("#messageServer").attr("class","error");
        }
    });
}

/**
 * Créer un commentaire
 */
const createComment = (url,message) => {  
    $("#viewWriting").css("display","none");
    $("#viewWriting").next().css("display","block");
    $.ajax({ 
        type: 'POST',       
        url: url,
        data: {
            message: message
        },
        success: function (data) { 
            $("#viewComments").html(data);
        },       
        error:function () {
            $("#messageServerComment").attr("class","error");
            $("#messageServerComment").html("<i class='fas fa-times'> En raison d\'une erreur survenue, le commentaire n'a pas été enregistré!.</i>");
        }
    });
}

/**
 * Supprimer un commentaire
 */
const deleteComment = url => {  
    $.ajax({ 
        type: 'POST',       
        url: url,
        beforeSend: function() { 
            $(".view__comments-list").html("<img src='../../images/loading.gif' alt='spinner de chargement'>").css('text-align','center');
        },
        success: function (data) { 
            $("#viewComments").html(data);
        },       
        error:function () {
            $("#messageServerComment").html("<i class='fas fa-times'> En raison d\'une erreur survenue, le commentaire n'a pas été supprimé!.</i>");
            $("#messageServerComment").attr("class","error");
        }
    });
}




