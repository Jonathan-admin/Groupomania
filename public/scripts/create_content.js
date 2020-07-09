// Fonction mère

$(document).ready(function() {
    if(document.location.href.indexOf("nouveau_contenu")>0) {
        $("#content_type_0").prop("checked", true);
    } else { 
        displayMediaPathControl($("input[name='content[type]']:checked").val());
    }
    $(document).on("change","#content_type", function(){ 
        let value = $("input[name='content[type]']:checked").val();
        $("#mediaPath").css("display","none");
        displayMediaPathControl(value);
        $("#content_mediaPathFile").val("");
    });
});

/**
 * Gerer l'apparition du bouton d'ajout de fichier en fonction du type de contenu sélectionné 
 */
const displayMediaPathControl = (value) => { 
    if(value=="Image" || value=="Musique") {
        value = "imgMus";
    }
    switch (value) {
        case "Texte":
            $("#mediaPath").css("display","none");
            break;
        case "imgMus":
            $("#mediaPath").css("display","block");
            $("#mediaPathFile").css("display","block");
            $("#mediaPathUrl").css("display","none");
            break;
        case "Vidéo":  
            $("#mediaPath").css("display","block");
            $("#mediaPathFile").css("display","none");
            $("#mediaPathUrl").css("display","block");
    }
}


