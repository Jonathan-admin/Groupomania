$(document).ready(function() { 
    $(document).on("click","tr[id*='userInfosLink']", function(){ 
        $("#messageRefresh").empty();
        $("#messageServer").empty();
        displayInfosUser($(this).attr('data-url'));
    });
    $(document).on("change","#roles", function(){ 
        $("#messageRefresh").empty();
        $("#messageServer").empty();
        modifyRoles($(this).attr("data-url"),$(this).val());
    });
    $(document).on("click","#delete_button", function(){ 
        $("#messageRefresh").empty();
        $("#messageServer").empty();
        deleteUser($(this).attr("data-url"));
    });
    $(document).on("click",".fa-sync", function(){
        $("#messageRefresh").empty();
        $("#messageServer").empty(); 
        refreshUserList($(this).attr("data-url"));
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
            $("#messageServer").html("<i class='fas fa-times'> En raison d\'une erreur survenue, il est impossible d'afficher les informations utilisateur.</i>");
            $("#messageServer").attr("class","error");
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
            $("#messageServer").html("<p><i class='fas fa-check-circle'> Les droits de cet utilisateur ont bien été modifiés! Pour réactualiser la liste, merci de cliquer sur le bouton  <i class='fas fa-sync'></i>.</i></p>").css('text-align','left');
            $("#messageServer").attr("class","success");
        },       
        error:function (jqXHR, exception) {
            $("#userInfos").empty();
            $("#messageServer").html("<i class='fas fa-times'> En raison d'une erreur innatendue, les droits de cet utilisateur n'ont pas pu être modifiés.</i>");
            $("#messageServer").attr("class","error");
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
            $("#messageServer").html("<p><i class='fas fa-check-circle'> L'utilisateur a bien été supprimé!</i></p>").css('text-align','left');
            setAttrUrlPath(data);
            $("#messageServer").attr("class","success");
        },       
        error:function (jqXHR, exception) {
            $("#usersList").empty();
            $("#messageServer").html("<i class='fas fa-times'> En raison d'une erreur innatendue, la suppression de l'utilisateur a échouée.</i>");
            $("#messageServer").attr("class","error");
        }
    });
}

const refreshUserList = (url) => {  
    $.ajax({ 
        type: 'POST',       
        url: url,
        beforeSend: function() { 
            $("#usersList").html("<img src='../images/loading.gif' alt='spinner de chargement'>").css('text-align','center');
        },
        success: function (data) { 
            $("#usersList").html(getUsersToDisplay(data)).css('text-align','left');
            $("#messageRefresh").html("<p><i class='fas fa-check-circle'> La liste des utilisateurs a été réactualisée!</i></p>").css('text-align','left');
            setAttrUrlPath(data);
            $("#messageRefresh").attr("class","success");
        },       
        error:function (jqXHR, exception) {
            $("#usersList").empty();
            $("#messageRefresh").html("<i class='fas fa-times'> En raison d'une erreur innatendue, la mise à jour de la liste a échouée.</i>");
            $("#messageRefresh").attr("class","error");
        }
    });
}

const getUsersToDisplay = data => { 
    content = "<table><tr class='bg-blue-head'><th>Matricule</th><th>Pseudo</th><th>Statut</th></tr>";
    for (let index = 0; index <  data.users.length; index++) {
        let status = "";
        if(data.users[index].roles.length==0) {
            status = "<i class='fas fa-user-alt-slash'></i>";
        } else {
            for (let index1 = 0; index1 <  data.users[index].roles.length; index1++) {
                if(data.users[index].roles[index1] == "ROLE_USER") {
                    status = "<i class='fas fa-user-check'> </i>";
                } else if(data.users[index].roles[index1] == "ROLE_ADMIN") {
                    status += "<i class='fas fa-user-nurse'> </i>";
                } else {
                    status += "<i class='fas fa-user-times'> </i>";
                }  
            }
        }
        content += "<tr id='userInfosLink"+data.users[index].id+"'>"
        +"<td> "+data.users[index].id+"</td><td>"+data.users[index].username+"</td><td>"+status+"</td></tr>"; 
    }
    $("#userCount").html("Utilisateurs actuellement inscrits ("+data.users.length+")   <i class='fas fa-sync' data-url='/admin/gestion_utilisateur/actualisation_liste_utilisateurs'></i>");
    content += "</table>";
    return content;
}

const setAttrUrlPath = data => { 
    for (let index = 0; index <  data.users.length; index++) {
        let url = "/admin/gestion_utilisateur/"+data.users[index].id;
        $("#userInfosLink"+data.users[index].id).attr("data-url",url);
    }  
}



