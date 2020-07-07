$(document).ready(function() { 
    $(document).on("click","tr[id*='contentInfosLink']", function(){  
        $("#messageRefreshContent").empty();
        $("#messageServerContent").empty();
        displayInfosContent($(this).attr('data-url'));
    });
    $(document).on("change","#status", function(){ alert("poi");
        $("#messageRefreshContent").empty();
        $("#messageServerContent").empty();
        modifyStatus($(this).attr("data-url"),$(this).val());
    });
    $(document).on("click","#delContentbutton", function(){ 
        $("#messageRefreshContent").empty();
        $("#messageServerContent").empty();
        deleteContent($(this).attr("data-url"));
    });
    $(document).on("click","#refreshContent", function(){
        $("#messageRefreshContent").empty();
        $("#messageServerContent").empty(); 
        refreshContentList($(this).attr("data-url"));
    });
});

const displayInfosContent = url => {  
    $.ajax({ 
        type: 'POST',       
        url: url,
        beforeSend: function() { 
            $("#contentInfos").html("<img src='../images/loading.gif' alt='spinner de chargement'>").css('text-align','center');
        },
        success: function (data) {
            $("#contentInfos").html(data).css('text-align','left');
        },       
        error:function (jqXHR, exception) {
            $("#messageServerContent").html("<i class='fas fa-times'> En raison d\'une erreur survenue, il est impossible d'afficher les informations utilisateur.</i>");
            $("#messageServerContent").attr("class","error");
        }
    });
}

const modifyStatus = (url,status) => {  
    $.ajax({ 
        type: 'POST',       
        url: url,
        data: {
            status: status
        },
        beforeSend: function() { 
            $("#contentInfos").html("<img src='../images/loading.gif' alt='spinner de chargement'>").css('text-align','center');
        },
        success: function (data) { 
            $("#contentInfos").html(data).css('text-align','left');
            $("#messageServerContent").html("<p><i class='fas fa-check-circle'> Le status de ce contenu a bien été modifié! Pour réactualiser la liste, merci de cliquer sur le bouton  <i class='fas fa-sync'></i>.</i></p>").css('text-align','left');
            $("#messageServerContent").attr("class","success");
        },       
        error:function (jqXHR, exception) {
            $("#contentInfos").empty();
            $("#messageServerContent").html("<i class='fas fa-times'> En raison d'une erreur innatendue, le status de ce contenu n'a pas pu être modifié.</i>");
            $("#messageServerContent").attr("class","error");
        }
    });
}

const deleteContent = (url) => {  
    $.ajax({ 
        type: 'POST',       
        url: url,
        beforeSend: function() { 
            $("#contentsList").html("<img src='../images/loading.gif' alt='spinner de chargement'>").css('text-align','center');
        },
        success: function (data) { 
            $("#contentsList").html(getContentsToDisplay(data)).css('text-align','left');
            $("#messageServerContent").html("<p><i class='fas fa-check-circle'> L'utilisateur a bien été supprimé!</i></p>").css('text-align','left');
            setAttrUrlPathContent(data);
            $("#messageServerContent").attr("class","success");
        },       
        error:function (jqXHR, exception) {
            $("#contentInfos").empty();
            $("#messageServerContent").html("<i class='fas fa-times'> En raison d'une erreur innatendue, la suppression de l'utilisateur a échouée.</i>");
            $("#messageServerContent").attr("class","error");
        }
    });
}

const refreshContentList = (url) => {  
    $.ajax({ 
        type: 'POST',       
        url: url,
        beforeSend: function() { 
            $("#contentsList").html("<img src='../images/loading.gif' alt='spinner de chargement'>").css('text-align','center');
        },
        success: function (data) { 
            $("#contentsList").html(getContentsToDisplay(data)).css('text-align','left');
            $("#messageRefreshContent").html("<p><i class='fas fa-check-circle'> La liste des utilisateurs a été réactualisée!</i></p>").css('text-align','left');
            setAttrUrlPathContent(data);
            $("#messageRefreshContent").attr("class","success");
        },       
        error:function (jqXHR, exception) {
            $("#contentsList").empty();
            $("#messageRefreshContent").html("<i class='fas fa-times'> En raison d'une erreur innatendue, la mise à jour de la liste a échouée.</i>");
            $("#messageRefreshContent").attr("class","error");
        }
    });
}

const getContentsToDisplay = data => { 
    content = "<table><tr class='bg-blue-head'><th>Référence</th><th>Titre</th><th>Statut</th></tr>";
    for (let index = 0; index <  data.contents.length; index++) {
        let status = "";
        if(data.contents[index].status == "Vérifié" ) {
            status = "<i class='fas fa-check-square'> "+data.contents[index].status+"</i>";
        } else if(data.contents[index].status == "En attente de vérifications") {
            status = "<i class='fas fa-times-circle'> "+data.contents[index].status+"</i>";
        } else if(data.contents[index].status == "Suspendu") {
            status = "<i class='fas fa-minus-square'> "+data.contents[index].status+"</i>";
        } else {
            status = "<i class='fas fa-ban'> "+data.contents[index].status+"</i>";
        }
        content += "<tr id='contentInfosLink"+data.contents[index].id+"'>"
        +"<td> "+data.contents[index].id+"</td><td>"+data.contents[index].title+"</td><td>"+status+"</td></tr>"; 
    }
    $("#contentCount").html("Contenus actuellement enregistrés ("+data.contents.length+")   <i id='refreshContent' class='fas fa-sync' data-url='/admin/gestion_contenu/actualisation_liste_contenus'></i>");
    content += "</table>";
    return content;
}


const setAttrUrlPathContent = data => { 
    for (let index = 0; index <  data.contents.length; index++) {
        let url = "/admin/gestion_contenu/"+data.contents[index].id;
        $("#contentInfosLink"+data.contents[index].id).attr("data-url",url);
    }  
}




    


   
