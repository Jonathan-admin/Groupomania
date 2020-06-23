$(document).ready(function() { 
    $(document).on("click",".userInfosLink", function(){ 
        displayInfosUser($(this).attr('data-url'));
    });
    $(document).on("change","#roles", function(){ 
        modifyRoles($(this).attr("data-url"),$(this).val());
    });
    $(document).on("click","#delete_button", function(){ 
        deleteUser($(this).attr("data-url"));
    });
});

const displayInfosUser = url => {  
    $.ajax({ 
        type: 'POST',       
        url: url,
        beforeSend: function() { 
            $("#userInfos").html("<img src='../images/loading.gif' alt='spinner de chargement'>").css('text-align','center');
        },
        success: function (data) {
            $("#userInfos").html(data).css('text-align','left');
        },       
        error:function (jqXHR, exception) {
            $("#messageServer").html("<i class='fas fa-times'>En raison d\'une erreur survenue, il est impossible d'afficher lesinformations utilisateur.</i>");
            $("#messageServer").css('color','red');
        }
    });
}

const modifyRoles = (url,role) => {  
    $.ajax({ 
        type: 'POST',       
        url: url,
        data: {
            role: role
        },
        beforeSend: function() { 
            $("#userInfos").html("<img src='../images/loading.gif' alt='spinner de chargement'>").css('text-align','center');
        },
        success: function (data) { 
            $("#userInfos").html(data).css('text-align','left');
            $("#messageServer").html("<p>Les droits de cet utilisateur ont bien été modifiés!</p>").css('text-align','left');
        },       
        error:function (jqXHR, exception) {
            $("#messageServer").html("<i class='fas fa-times'>En raison d'une erreur innatendue, les droits de cet utilisateur n'ont pas pu être modifiés.</i>");
            $("#messageServer").css('color','red');
        }
    });
}

const deleteUser = (url) => {  
    $.ajax({ 
        type: 'POST',       
        url: url,
        beforeSend: function() { 
            $("#usersList").html("<img src='../images/loading.gif' alt='spinner de chargement'>").css('text-align','center');
        },
        success: function (data) { 
            $("#usersList").html(getUsersToDisplay(data)).css('text-align','left');
            $("#messageServer").html("<p>L'utilisateur a bien été supprimé!</p>").css('text-align','left');
        },       
        error:function (jqXHR, exception) {
            $("#messageServer").html("<i class='fas fa-times'>En raison d'une erreur innatendue, la suppression de l'utilisateur a échouée.</i>");
            $("#messageServer").css('color','red');
        }
    });
}

const getUsersToDisplay = data => { 
    content = "<table><tr><th>Matricule</th><th>Pseudo</th></tr>";
    for (let index = 0; index <  data.users.length; index++) {
        content += "<tr class='userInfosLink' data-url='{{ path('admin_userView',{'id':"+data.users[index].id+"}) }}'></tr>"
        +"<td> "+data.users[index].id+"</td><td>"+data.users[index].username+"</td></tr>"; 
    }
    content += "</table>";
    return content;
}



