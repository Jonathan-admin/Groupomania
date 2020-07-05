const submit = document.getElementById("submit");  

var dataControlAuth = [                                  // Tableau multidimentionnel stockant les informations des contrôles du formulaire d'authentification
    [document.getElementById("login"),/^[\wéèàçîôûê.]{2}[\w-éèàçîôûê.]{0,23}$/i,true,
    "Nom d'utilisateur - Le format du login est invalide ! Il doit comporter entre 2 et 15 caractères. La ponctuation (sauf le .) et les caractères spéciaux sont exclus"],
    [document.getElementById("password"),/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{9,}$/i,true,
    "Mot de passe - Le mot de passe doit comporter au moins 10 caractères, posséder au moins un chiffre, une lettre majuscule et minuscule et l'un des caractères spéciaux suivants: @$!%*?&."]
];

var dataControlSub = [                                  // Tableau multidimentionnel stockant les informations des contrôles du formulaire d'authentification
    [document.getElementById("registration_username"),/^[\wéèàçîôûê]{2}[\w-éèàçîôûê]{0,23}$/i,true,
    "Nom d'utilisateur - Le format du login est invalide ! Il doit comporter entre 2 et 15 caractères. La ponctuation et les caractères spéciaux sont exclus"],
    [document.getElementById("registration_email"),/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/i,true,
    "Adresse email - Cette adresse ne semble  pas valide !"],
    [document.getElementById("registration_password"),/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{9,}$/i,true,
    "Mot de passe - Le mot de passe doit comporter au moins 10 caractères, posséder au moins un chiffre, une lettre majuscule et minuscule et l'un des caractères spéciaux suivants: @$!%*?&."],
    [document.getElementById("registration_confirm_password"),null,null,
    "Confirmation du mot de passe - Le mot de passe doit être identique!"]
];


$(document).ready(function() { 
    switch (detectPageUrl()) { 
        case 0:
            dataValidation(dataControlAuth,false);
            break;
        case 1: 
            dataValidation(dataControlSub,true);
            checkConfirmPassword(dataControlSub);
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
        if(elt.value!="") {                         // Affiche le message d'erreur dans la balise précédente
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

disableButtonSubmit = (nbInputCheck,tabLength) => {
    if(nbInputCheck==tabLength) {                // Si tous les champs sont OK et au moins 1 produit dans le panier alors on active le bouton
        submit.removeAttribute("disabled");
    } else {                                         // sinon on le désactive
        submit.setAttribute("disabled",true);
    }
}

const detectPageUrl = () => {
    let page = null;
    if(document.location.href.indexOf("inscription")>0) { 
        page = 1;
    } else if (document.location.href.indexOf("connexion")>0) {
        page = 0;
    }
    return page;
}

const dataValidation = (tabControl,confirmPass) => {
    let tabLong = tabControl.length;
    if(confirmPass) {
        tabLong = tabControl.length - 1;
    }
    for (let i = 0; i < tabLong; i++) {
        tabControl[i][2] = markDataLoad(tabControl[i][0],tabControl[i][1],tabControl[i][3])  
        tabControl[i][0].addEventListener("input", function(event) {                    
            tabControl[i][2] = markData(tabControl[i][0],tabControl[i][1],event,tabControl[i][3]);  
            disableButtonSubmit(getNbcheckInput(tabControl),tabControl.length);                  
        });                                                                                
    }
    disableButtonSubmit(getNbcheckInput(tabControl),tabControl.length);
}

checkConfirmPassword = (tabControl) => {
    tabControl[3][0].addEventListener("input", function(event) { 
        if(tabControl[2][0].value === tabControl[3][0].value) {
            tabControl[3][0].style.border = "1px solid rgb(196, 186, 196)";          // Affiche le message success dans la balise précédente
            tabControl[3][0].previousElementSibling.innerHTML = tabControl[3][3].split("-")[0]+"<i class='fa fa-check-circle'></i>";
            submit.removeAttribute("disabled");
        } else {
            tabControl[3][0].style.border = "1px solid red";
            tabControl[3][0].previousElementSibling.innerHTML = tabControl[3][3].split("-")[0]+"<span class='error'><i class='fa fa-times-circle'></i>"+tabControl[3][3].split("-")[1]+"</span>";
            submit.setAttribute("disabled",true);
        }
    });
}