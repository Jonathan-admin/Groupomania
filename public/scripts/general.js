$(document).ready(function() { 
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