$(document).ready(function() {
    $("#content_type_0").prop("checked", true);
    $(document).on("change","#content_type", function(){ 
        let value = $("input[name='content[type]']:checked").val();
        $("#mediaPath").css("display","none");
        displayMediaPathControl(value);
        $("#content_mediaPathFile").val("");
    });
});

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


