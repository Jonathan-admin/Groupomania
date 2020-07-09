// Fonction mère

$(document).ready(function() { 
    navbarActiveItems();
    $(document).on("click",".reduce .fa-chevron-down", function(){ 
        $(this).parent().next().css("display","none");
        $(this).parent().next().next().css("display","none");
        $(this).attr("class","fas fa-chevron-up");
    });
    $(document).on("click",".reduce .fa-chevron-up", function(){ 
        $(this).parent().next().css("display",$(this).attr("data-display"));
        $(this).attr("class","fas fa-chevron-down");
        $(this).parent().next().next().css("display","block");
    });
});


/**
 * Mettre en évidence dans le navbar la page courante
 */
const navbarActiveItems = () => {
    $(".nav-link i").removeClass("active");
    if (document.location.href == "http://127.0.0.1:8000/" ||
    document.location.href.indexOf("http://127.0.0.1:8000/forum")>=0 || 
    document.location.href.indexOf("http://127.0.0.1:8000/?page=")>=0) { 
        $(".navbar .nav-item:nth-child(1) i").addClass("active");
    } else if (document.location.href.indexOf("espace_membre")>0 ) {
        $(".navbar .nav-item:nth-child(2) i").addClass("active");
    } else if (document.location.href.indexOf("admin")>0) {
        $(".navbar .nav-item:nth-child(3) i").addClass("active");
    } else {
        $(".navbar .nav-item:nth-child(4) i").addClass("active");
    }   
}