$(document).ready(function() {
    $(document).on("change","#content_type", function(){ 
        let value = $("input[name='content[type]']:checked").val();
        $("#mediaPath").empty();
        $("#mediaPath").append(displayMediaPathControl(value));
    });
});

const displayMediaPathControl = value => { 
    let result = '<div class="form-group">';
    switch (value) {
        case 'Texte':
            result = "";
            break;
        case 'Musique':
            result += "<label for='music'>Veuillez indiquer une URL ciblant la musique à insérer</label>"
                   +"<input placeholder='Insérer le lien ici...' type='text' class='form-control' required />";
            break;
        case 'Vidéo':
            result += "<label for='video'>Veuillez indiquer une URL ciblant la vidéo à partager</label>"
                   +"<input placeholder='Insérer le lien ici...' type='text' class='form-control' required />";
            break;
        case 'Image':
            result += "<label for='image'>Image à partager</label>"
            +"<input type='file' id='picture' name='picture_file' accept='image/png, image/jpeg, image/jpg'>";
    break;
        default:
            result = "";
            break;
    }
    result += "</div>";
    return result;
}
