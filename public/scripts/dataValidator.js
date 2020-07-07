const submitAuth = document.getElementById("submit");  
const submitSeach = document.getElementById("searchContent_button"); 
const submitCreate = document.getElementById("editContent");
const submitComment = document.getElementById("sendComment-button");

var dataControlAuth = [                                  // Tableau multidimentionnel stockant les informations des contrôles du formulaire d'authentification
    [document.getElementById("login"),/^[a-zA-Z0-9_.]{3,25}$/i,true,
    "Nom d'utilisateur - Le format du login est invalide ! Il doit comporter entre 3 et 25 caractères. La ponctuation (sauf le .), les espaces et les caractères spéciaux sont exclus"],
    [document.getElementById("password"),/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{9,}$/i,true,
    "Mot de passe - Le mot de passe doit comporter au moins 10 caractères, posséder au moins un chiffre, une lettre majuscule et minuscule et l'un des caractères spéciaux suivants: @$!%*?&."]
];

var dataControlSub = [                                  // Tableau multidimentionnel stockant les informations des contrôles du formulaire d'authentification
    [document.getElementById("registration_username"),/^[a-zA-Z0-9_.]{3,15}$/i,true,
    "Nom d'utilisateur - Le format du login est invalide ! Il doit comporter entre 3 et 15 caractères. La ponctuation (sauf le .), les espaces et les caractères spéciaux sont exclus"],
    [document.getElementById("registration_email"),/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/i,true,
    "Adresse email - Cette adresse ne semble  pas valide !"],
    [document.getElementById("registration_password"),/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{9,}$/i,true,
    "Mot de passe - Le mot de passe doit comporter au moins 10 caractères, posséder au moins un chiffre, une lettre majuscule et minuscule et l'un des caractères spéciaux suivants: @$!%*?&."],
    [document.getElementById("registration_confirm_password"),null,null,
    "Confirmation du mot de passe - Le mot de passe doit être identique!"]
];

var dataControlSearch = [                                 
    [document.getElementById("author"),/^[\w-.éèàçîôûê]{0,15}$/i,true,
    "Autheur - Les caractères que vous avez saisi ne sont pas tous autorisés!"],
    [document.getElementById("title"),/^[\w\s,?!;:()-.éèàçîôûê]{0,15}$/i,true,
    "Titre - Les caractères que vous avez saisi ne sont pas tous autorisés!"]
];

var dataControlCreate = [                                 
    [document.getElementById("content_title"),/^[^&'\{\[\<\>\]\}#=@$%\s]{1}[^&'\{\[\<\>\]\}#=@$%]{9,253}$/i,true,
    "Titre - Les caractères que vous avez saisi ne sont pas tous autorisés ou le titre est trop long (10-255 caractères)!"],
    [document.getElementById("content_message"),/^[^&'\{\[\<\>\]\}#=@$\s]{1}[^&\{\[\<\>\]\}#=@$]{9,4990}$/i,true,
    "Message - Vous avez inscrit des caractères non autorisés dans le message ou il est trop long. Il doit être d'au moins 10 caractères et ne pas dépasser 5000 caractères."]
];

var dataControlComment = [                                 
    [$("#comment"),/^[^&'\{\[\<\>\]\}#=@$%\s]{1}[^&'\{\[\<\>\]\}#=@$%]{1,1990}$/i,true,
    "Votre commentaire - Vous avez inscrit des caractères non autorisés dans le message ou il est trop long. Il doit être d'au moins 10 caractères et ne pas dépasser 2000 caractères."],
];


$(document).ready(function() { 
    $(".form-error-icon").text("ERREUR");
    switch (detectPageUrl()) { 
        case 0:
            dataValidation(dataControlAuth,false,submitAuth,true);
            break;
        case 1: 
            dataValidation(dataControlSub,true,submitAuth,false);
            checkConfirmPassword(dataControlSub);
            break;
        case 2: 
            dataValidation(dataControlSearch,false,submitSeach,true);
            break;
        case 3: 
            dataValidation(dataControlCreate,false,submitCreate,true);
            $(document).on("change","#content_type", function(){  
                checkMimeTypeFile(submitCreate,dataControlCreate);
            });
            checkMimeTypeFile(submitCreate,dataControlCreate);
            break;
        case 4: 
            $("#comment").val("");
            checkCommentData(dataControlComment);
            break;
    }       
});



const markData = (elt,regex,event,label) => {  // Valide la saisie suite à un évènement sur le contrôle
    if(regex.test(event.target.value)) {                    // Test le regex de validité de la saisie
        elt.style.border = "1px solid rgb(196, 186, 196)";  // Affiche le message success dans la balise précédente
        elt.previousElementSibling.innerHTML = label.split("-")[0]+"<i class='fa fa-check-circle'></i>";
        return true;
    } else {
        elt.style.border = "1px solid red";      // Affiche le message d'erreur dans la balise précédente
        elt.previousElementSibling.innerHTML = label.split("-")[0]+"<span class='error'><i class='fa fa-times-circle'></i>"+label.split("-")[1]+"</span>";
        if(elt.value=="") {
            elt.previousElementSibling.innerHTML = label.split("-")[0]+"<span class='error'><i class='fa fa-times-circle'></i> Le champ ne peut pas être vide !</span>";
        }   
        return false;
    }
}

const markDataLoad = (elt,regex,label) => {        // Valide la saisie au chargement de la page
    if(regex.test(elt.value)) {                                     // Test le regex de validité de la saisie
        elt.style.border = "1px solid rgb(196, 186, 196)";          // Affiche le message success dans la balise précédente
        elt.previousElementSibling.innerHTML = label.split("-")[0]+"<i class='fa fa-check-circle'></i>";
        return true;
    } else {      
        if(elt.value!="") {                   // Affiche le message d'erreur dans la balise précédente
            elt.style.border = "1px solid red";
            elt.previousElementSibling.innerHTML = label.split("-")[0]+"<span class='error'><i class='fa fa-times-circle'></i>"+label.split("-")[1]+"</span>";
        }
        return false;
    }   
}

const getNbcheckInput = (tabControl) => {
    let compteur = 0; 
    for (let index = 0; index < tabControl.length; index++) {
        if(tabControl[index][2]) {
            compteur++              
        }  
    }            // Retourne le nombre de champs validés
    return compteur;        
}

disableButtonSubmit = (nbInputCheck,tabLength,submitButton) => {  
    if(nbInputCheck==tabLength) {          // Si tous les champs sont OK et au moins 1 produit dans le panier alors on active le bouton
        submitButton.removeAttribute("disabled");
    } else {                                         // sinon on le désactive
        submitButton.setAttribute("disabled",true);
    }
}

const detectPageUrl = () => {
    let page = null;
    if(document.location.href.indexOf("connexion")>0) { 
        page = 0;
    } else if (document.location.href.indexOf("inscription")>0) {
        page = 1;
    } else if (document.location.href == "http://127.0.0.1:8000/" || document.location.href.indexOf("Rechercher_des_contenus")>0) {
        page = 2; 
    } else if (document.location.href.indexOf("nouveau_contenu")>0 ||  document.location.href.indexOf("modification_contenu")>0) {
        page = 3;  
    } else if (document.location.href.indexOf("/forum/contenu/")>0 ) {
        page = 4; 
    }
    return page;
}

const dataValidation = (tabControl,confirmPass,submitButton,load) => {
    let tabLong = tabControl.length;
    if(confirmPass) {
        tabLong = tabControl.length - 1;
    }
    for (let i = 0; i < tabLong; i++) {
        if(load) {
            tabControl[i][2] = markDataLoad(tabControl[i][0],tabControl[i][1],tabControl[i][3]);
        }
        tabControl[i][0].addEventListener("input", function(event) {               
            tabControl[i][2] = markData(tabControl[i][0],tabControl[i][1],event,tabControl[i][3]);  
            disableButtonSubmit(getNbcheckInput(tabControl),tabControl.length,submitButton);                  
        });                                                                                
    }
    disableButtonSubmit(getNbcheckInput(tabControl),tabControl.length,submitButton);
}


checkConfirmPassword = (tabControl) => {
    tabControl[3][0].addEventListener("input", function(event) { 
        if(tabControl[2][0].value === tabControl[3][0].value) {
            tabControl[3][0].style.border = "1px solid rgb(196, 186, 196)";          // Affiche le message success dans la balise précédente
            tabControl[3][0].previousElementSibling.innerHTML = tabControl[3][3].split("-")[0]+"<i class='fa fa-check-circle'></i>";
            submitAuth.removeAttribute("disabled");
        } else {
            tabControl[3][0].style.border = "1px solid red";
            tabControl[3][0].previousElementSibling.innerHTML = tabControl[3][3].split("-")[0]+"<span class='error'><i class='fa fa-times-circle'></i>"+tabControl[3][3].split("-")[1]+"</span>";
            submitAuth.setAttribute("disabled",true);
        }
    });
}

checkMimeTypeFile = (submitButton,tabControl) => {
        const valueFile = $("label[for=content_mediaPathFile]").text();
        if( $("input[name='content[type]']:checked").val() == "Texte" || 
        ($("input[name='content[type]']:checked").val() == "Image" && (valueFile.endsWith(".jpg")||valueFile.endsWith(".jpeg")||valueFile.endsWith(".png")||valueFile.endsWith(".gif"))) ||
        ($("input[name='content[type]']:checked").val() == "Musique" && valueFile.endsWith(".mp3")) ||
        ($("input[name='content[type]']:checked").val() == "Vidéo" && valueFile.startsWith("https://www.youtube.com/embed/"))) {
            dataValidation(tabControl,false,submitButton,true);       
        } else {
            submitCreate.setAttribute("disabled",true);
        }
        $(document).on("change","#content_mediaPathFile", function() { 
            const value = $("#content_mediaPathFile").val();
            if( $("input[name='content[type]']:checked").val() != "Musique") {
                if(value.endsWith(".jpg") || value.endsWith(".jpeg") || value.endsWith(".png") || value.endsWith(".gif")) {
                    dataValidation(tabControl,false,submitButton,true);
                } else {
                    submitCreate.setAttribute("disabled",true);
                } 
            } else {
                if(value.endsWith(".mp3")) {
                    dataValidation(tabControl,false,submitButton,true);
                } else {
                    submitCreate.setAttribute("disabled",true);
                } 
            } 
        });
        $(document).on("input","#content_mediaPathUrl", function() { 
            let value = $("#content_mediaPathUrl").val(); 
            if(value.startsWith("https://www.youtube.com/embed/")) {
                dataValidation(tabControl,false,submitButton,true);
            } else {
                submitCreate.setAttribute("disabled",true);
            }  
    });
}

checkCommentData = tabControl => {
    $(document).on("input", "#comment", function(event) {
        if(tabControl[0][1].test($(this).val())) {                              
            $(this).css("border","1px solid rgb(196, 186, 196)");          
            $(this).prev().html(tabControl[0][3].split("-")[0]+"<i class='fa fa-check-circle'></i>"); 
            $("#sendComment-button").prop( "disabled", false );
        } else {        
            $(this).css("border","1px solid red");
            $(this).prev().html(tabControl[0][3].split("-")[0]+"<span class='error'><i class='fa fa-times-circle'></i>"+tabControl[0][3].split("-")[1]+"</span>");
            if($(this).val()=="") {                  
                $(this).prev().html(tabControl[0][3].split("-")[0]+"<span class='error'><i class='fa fa-times-circle'></i> Le champ ne peut pas être vide !</span>");
            }
            $("#sendComment-button").prop( "disabled", true );
        }   
    });
}



