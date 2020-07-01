$(document).ready(function() { 
    $(document).on("click","i[class*=thumbs]:not('.disabled')", function(){ 
        $("#messageServer").empty();
        likingContent($(this).attr('data-url'),$(this).attr('data-role'),$(this));
    });
});

const likingContent = (url,role,elt) => {  
    $.ajax({ 
        type: 'POST',       
        url: url,
        data: {
            role: role
        },
        success: function (data) { alert("poi");
            $("#sys-like").html(data);
        },       
        error:function (jqXHR, exception) {
            $("#messageServer").html("<i class='fas fa-times'> En raison d\'une erreur survenue, le like n'a pas été enregistré!.</i>");
            $("#messageServer").attr("class","error");
        }
    });
}


