$(document).ready(function()
{
    url = window.location.toString();
    setHistorySearchParameters(); 
});

function setHistorySearchParameters() { 
    if(url.indexOf("/forum/Rechercher_des_contenus")>0) { 
        $("#type").val($("#searchContent_button").attr("data-type"));
        $("#topic").val($("#searchContent_button").attr("data-topic"));
        $("#author").val($("#searchContent_button").attr("data-author"));
        $("#title").val($("#searchContent_button").attr("data-title"));
        $("#sorting").val($("#searchContent_button").attr("data-sorting"));
    }
}