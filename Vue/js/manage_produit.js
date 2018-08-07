$(document).ready(function(){
    
    if (location.hash) {
        $("a[href='" + location.hash + "']").tab("show");
    }
    $(document.body).on("click", "a[data-toggle]", function(event) {
        location.hash = this.getAttribute("href");
    });
    
    var param = "refresh=true";
    refreshSortable(param);
    sortableLoad();
});

$("#ajout-new-prod").on("click", function(){
    var param = "addProd=true" + "&nom=" + $("#new-prod-nom").val() + "&prix=" + $("#new-prod-prix").val();
    requete_produits(param);
    $("#new-prod-nom").val('');
    $("#new-prod-prix").val('');
});

$(".change-prod-nom").on("change", function(){
    var param = "changeProdNom=true" + "&id=" + $(this).parent().parent().find("button").val() + "&nom=" + $(this).val();
    requete_produits(param);
});

$(".change-prod-prix").on("change", function(){
    var param = "changeProdPrix=true" + "&id=" + $(this).parent().parent().parent().find("button").val() + "&prix=" + $(this).val();
    requete_produits(param);
});

$(".delProd").on("click", function(){
    var param = "delProd=true" + "&id=" + $(this).val();
    requete_produits(param)
    $(this).closest('li').remove();
});

function refreshSortable(param){
    $.ajax({
        type: "POST",
        url: "Controleur/HTTPRequest/HTTPRequestProduitLiaison.php",
        data: param,
        success: function(content){
            $("#link_prod").empty();
            $("#link_prod").append(content);
        },
        async: false
    });
}

function updateSortable(itemEl, depart, destination){
    var param = "updateSortable=true&item=" + itemEl + "&depart=" + depart + "&destination=" + destination;
    console.log(param);
    $.ajax({
        type: "POST",
        url: "Controleur/HTTPRequest/HTTPRequestProduitLiaison.php",
        data: param,
        success: function(content){
            
        },
        async: false
    });
}

function requete_produits(param){
     $.ajax({
            type: "POST",
            url: "Controleur/HTTPRequest/HTTPRequestProduit.php",
            data: param,
            success: function(content){
                switch(content){
                    case "Ajout OK":{
                        toastrSend('Enregistrement Réussie', 'Votre produit a été enregistré avec succès.', 1);
                        break;
                    }
                    case "Update OK":{
                        toastrSend('Mise à jour Réussie', 'Votre produit a été modifié avec succès.', 1);
                        break;
                    }
                    case "Suppr OK":{
                        toastrSend('Suppression Réussie', 'Votre produit a été supprimé avec succès.', 1);
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

// Applique Sortable.js sur toutes les listes de produits
function sortableLoad(){
    [].forEach.call(document.getElementById('link_prod').getElementsByClassName('sortable-ul'), function (el){
        Sortable.create(el, {
                group: 'produit',
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