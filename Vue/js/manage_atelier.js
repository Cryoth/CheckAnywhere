$(document).ready(function(){
    
    if (location.hash) {
        $("a[href='" + location.hash + "']").tab("show");
    }
    $(document.body).on("click", "a[data-toggle]", function(event) {
        location.hash = this.getAttribute("href");
    });
    refreshListAtelier();
    refreshSortable();
    sortableLoad();
});

$("#ajout-new-atelier").on("click", function(){
    var param = "addAtelier=true" + "&nom=" + $("#new-atelier-nom").val();
    requete_atelier(param);
    $("#new-atelier-nom").val('');
    refreshListAtelier();
    refreshSortable();
});

$(".change-atelier-nom").on("change", function(){
    var param = "changeAtelierNom=true" + "&id=" + $(this).parent().parent().find("button").val() + "&nom=" + $(this).val();
    requete_atelier(param);
});

// On utilise '$(document)' car l'objet .delAtelier est créé dynamiquement est n'est pas affecté par un appel direct
$(document).on("click", ".delAtelier", function(){
    if(confirm("Les matériels liés à cet atelier ne seront plus liés à aucun atelier, êtes vous sûr de vouloir le supprimer ?")){
        var param = "delAtelier=true" + "&id=" + $(this).val();
        requete_atelier(param);
        $(this).closest('li').remove();
        refreshSortable();
    }
});

function refreshSortable(){
    
    var param = "refresh=true";
    
    $.ajax({
        type: "POST",
        url: "Controleur/HTTPRequest/HTTPRequestAtelierLiaison.php",
        data: param,
        success: function(content){
            $("#link_atelier").empty();
            $("#link_atelier").append(content);
        },
        async: false
    });
}

function refreshListAtelier(){
    
    var param = "regenerate=true";
    
    $.ajax({
        type: "POST",
        url: "Controleur/HTTPRequest/HTTPRequestAtelier.php",
        data: param,
        success: function(content){
            $("#list-all-atelier").empty();
            $("#list-all-atelier").append(content);
        },
        async: false
    });
}

function updateSortable(itemEl, depart, destination){
    var param = "updateSortable=true&item=" + itemEl + "&depart=" + depart + "&destination=" + destination;
    console.log(param);
    $.ajax({
        type: "POST",
        url: "Controleur/HTTPRequest/HTTPRequestAtelierLiaison.php",
        data: param,
        success: function(content){
            
        },
        async: false
    });
}

function requete_atelier(param){
     $.ajax({
            type: "POST",
            url: "Controleur/HTTPRequest/HTTPRequestAtelier.php",
            data: param,
            success: function(content){
                switch(content){
                    case "Ajout OK":{
                        toastrSend('Enregistrement Réussie', 'Votre atelier a été enregistré avec succès.', 1);
                        break;
                    }
                    case "Update OK":{
                        toastrSend('Mise à jour Réussie', 'Votre atelier a été modifié avec succès.', 1);
                        break;
                    }
                    case "Suppr OK":{
                        toastrSend('Suppression Réussie', 'Votre atelier a été supprimé avec succès.', 1);
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

// Applique Sortable.js sur toutes les listes d'atelier
function sortableLoad(){
    [].forEach.call(document.getElementById('link_atelier').getElementsByClassName('sortable-ul'), function (el){
        Sortable.create(el, {
                group: 'atelier',
                animation: 150,
                onAdd: function (/**Event*/evt) {
                    var itemEl = evt.item.id;  // Id element déplacé
                    var destination = evt.to.id; // Id de la liste de destination
                    var depart = evt.from.id;  // Id de la liste de départ
                    updateSortable(itemEl, depart, destination);
	},
        });
    });
}

$(window).on("popstate", function() {
    var anchor = location.hash || $("a[data-toggle='tab']").first().attr("href");
    $("a[href='" + anchor + "']").tab("show");
});