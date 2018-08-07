
// Ajout d'une nouvelle donnée
$("#ajout-new-donnee").on("click", function(){
    var param = "addDonnee=true" + "&code=" + $("#new-donnee-code").val() + "&nom=" + $("#new-donnee-nom").val() + "&adresse=" + $("#new-donnee-adresse").val() + "&provenance=" + $("#new-donnee-provenance").val() + "&application=" + $("#new-donnee-application").val();
    requete_donnee(param);
    $("#new-donnee-code").val('');
    $("#new-donnee-nom").val('');
    $("#new-donnee-adresse").val('');
});

// Mise à jour en base du code d'une donnée
$(".change-donnee-code").on("change", function(){
    var param = "changeDonneeCode=true" + "&id=" + $(this).parent().parent().find('input:checkbox:first').val() + "&code=" + $(this).val();
    console.log(param);
    requete_donnee(param);
});

// Mise à jour en base du nom d'une donnée
$(".change-donnee-nom").on("change", function(){
    var param = "changeDonneeNom=true" + "&id=" + $(this).parent().parent().find('input:checkbox:first').val() + "&nom=" + $(this).val();
    requete_donnee(param);
});

// Mise à jour en base de l'adresse d'une donnée
$(".change-donnee-adresse").on("change", function(){
    var param = "changeDonneeAdresse=true" + "&id=" + $(this).parent().parent().find('input:checkbox:first').val() + "&adresse=" + $(this).val();
    requete_donnee(param);
});

// Mise à jour en base de l'application d'une donnée
$(".change-donnee-application").on("change", function(){
    console.log($(this).parent().parent().find('input:checkbox:first').val());
    var param = "changeDonneeApplication=true" + "&id=" + $(this).parent().parent().find('input:checkbox:first').val() + "&application=" + $(this).val();
    requete_donnee(param);
});

// Mise à jour en base de l'application d'une donnée
$(".change-donnee-visibilite").on("click", function(){
    if($(this).is(':checked')){
        var param = "changeDonneeVisibilite=true" + "&id=" + $(this).val() + "&visibilite=" + 1;
    }else{
        var param = "changeDonneeVisibilite=true" + "&id=" + $(this).val() + "&visibilite=" + 0;
    }
    requete_donnee(param);
});

// Envoie une requête AJAX pour traiter les modification des données (ou ajout) puis demande l'affichage d'un toastr
function requete_donnee(param){
     $.ajax({
            type: "POST",
            url: "Controleur/HTTPRequest/HTTPRequestDonnee.php",
            data: param,
            success: function(content){
                switch(content){
                    case "Ajout OK":{
                        toastrSend('Enregistrement Réussie', 'Votre donnee a été enregistré avec succès.', 1);
                        break;
                    }
                    case "Update OK":{
                        toastrSend('Mise à jour Réussie', 'Votre donnee a été modifié avec succès.', 1);
                        break;
                    }
                    case "Suppr OK":{
                        toastrSend('Suppression Réussie', 'Votre donnee a été supprimé avec succès.', 1);
                        break;
                    }
                    default:{
                        toastrSend('Erreur', "Votre opération a échouée dûe à l'érreur suivante: " + content, 4);
                    }
                }
            },
            async: false
        });
}

// Affichage d'un toastr en fonction des attributs passé en paramètre
function toastrSend(titre, message, type){
    switch(type){
        case 1:{
            toastr.success(message, titre, {timeOut: 2000});
            break;
        }
        case 4:{
            toastr.error(message, titre, {timeOut: 4000});
            break;
        }
    }
}