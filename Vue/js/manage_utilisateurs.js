
//Update des valeurs client au changement
$(document).on('click', ".modUser", function(){
    $(this).parent().parent().find("input[name='modifLog']").removeAttr("disabled");
    $(this).parent().parent().find("input[name='modifMail']").removeAttr("disabled");
    $(this).parent().parent().find("select[name='modifDroit']").removeAttr("disabled");
});

$(document).on('change', "input[name='modifLog']", function(){
    param = "name=" + $(this).val() + "&num=" + $(this).parent().parent().find("div:first").text();
    $("#MessageUpdate").load("Controleur/HTTPRequest/HTTPRequestUser.php", param);
});

$(document).on('change', "input[name='modifMail']", function(){
    param = "mail=" + $(this).val() + "&num=" + $(this).parent().parent().find("div:first").text();
    $("#MessageUpdate").load("Controleur/HTTPRequest/HTTPRequestUser.php", param);
});

$(document).on('change', "select[name='modifDroit']", function(){
    param = "droit=" + $(this).val() + "&num=" + $(this).parent().parent().find("div:first").text();
    $("#MessageUpdate").load("Controleur/HTTPRequest/HTTPRequestUser.php", param);
    console.log($(this).parent().find("td:first").text());
});

$(document).on('click', '.deleteUser', function(){
    var r = confirm("Etes-vous sûr de vouloir supprimer cet utilisateur ?");
    if(r === true){
        param = "suppr=" + $(this).parent().parent().find("div:first").text();
        $("#MessageUpdate").load("Controleur/HTTPRequest/HTTPRequestUser.php", param);
        $(this).parent().parent().remove();
    }
});