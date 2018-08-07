// Initialisation des fenêtres de trie au chargement de la page
$(document).ready(function(){
    
    if (location.hash) {
        $("a[href='" + location.hash + "']").tab("show");
    }
    $(document.body).on("click", "a[data-toggle]", function(event) {
        location.hash = this.getAttribute("href");
    });
    
    var param = "refresh=true" + "&provenance=" + $("#provenance_frequence").val();
    refreshSortable(param);
    sortableLoad();
});

// Filtre par provenance de la fenêtre de trie 
$("#provenance_frequence").on("change", function(){
    var param = "refresh=true" + "&provenance=" + $(this).val();
    refreshSortable(param);
    sortableLoad();
});

// Ajout d'une nouvelle fréquence
$("#ajout-new-frequence").on("click", function(){
    var param = "addFrequence=true" + "&code=" + $("#new-frequence-code").val() + "&nom=" + $("#new-frequence-nom").val() + "&adresse=" + $("#new-frequence-adresse").val() + "&provenance=" + $("#new-frequence-provenance").val() + "&application=" + $("#new-frequence-application").val();
    requete_frequence(param);
    $("#new-frequence-code").val('');
    $("#new-frequence-nom").val('');
    $("#new-frequence-adresse").val('');
});

// Mise à jour en base du code d'une fréquence
$(".change-frequence-code").on("change", function(){
    var param = "changeFrequenceCode=true" + "&id=" + $(this).parent().parent().find("button").val() + "&code=" + $(this).val();
    requete_frequence(param);
});

// Mise à jour en base du nom d'une fréquence
$(".change-frequence-nom").on("change", function(){
    var param = "changeFrequenceNom=true" + "&id=" + $(this).parent().parent().find("button").val() + "&nom=" + $(this).val();
    requete_frequence(param);
});

// Mise à jour en base de l'adresse d'une fréquence
$(".change-frequence-adresse").on("change", function(){
    var param = "changeFrequenceAdresse=true" + "&id=" + $(this).parent().parent().find("button").val() + "&adresse=" + $(this).val();
    requete_frequence(param);
});

// Mise à jour en base de l'application d'une fréquence
$(".change-frequence-application").on("change", function(){
    var param = "changeFrequenceApplication=true" + "&id=" + $(this).parent().parent().find("button").val() + "&application=" + $(this).val();
    requete_frequence(param);
});

// Suppression d'une fréquence existante
$(".delFrequence").on("click", function(){
    var param = "delFrequence=true" + "&id=" + $(this).val();
    requete_frequence(param)
    $(this).closest('li').remove();
});

// Affiche et raffraichit les tableux de trie des fréquences
function refreshSortable(param){
    $.ajax({
        type: "POST",
        url: "Controleur/HTTPRequest/HTTPRequestFrequenceLiaison.php",
        data: param,
        success: function(content){
            $("#link_frequence_content").empty();
            $("#link_frequence_content").append(content);
        },
        async: false
    });
}

// Gère les changements de liaison entre données et fréquences
function updateSortable(itemEl, depart, destination, provenance){
    var param = "updateSortable=true&item=" + itemEl + "&depart=" + depart + "&destination=" + destination + "&provenance=" + provenance;
    console.log(param);
    $.ajax({
        type: "POST",
        url: "Controleur/HTTPRequest/HTTPRequestFrequenceLiaison.php",
        data: param,
        success: function(content){
            
        },
        async: false
    });
}

// Envoie une requête AJAX pour traiter les modification des fréquences (ou ajout) puis demande l'affichage d'un toastr
function requete_frequence(param){
     $.ajax({
            type: "POST",
            url: "Controleur/HTTPRequest/HTTPRequestFrequence.php",
            data: param,
            success: function(content){
                switch(content){
                    case "Ajout OK":{
                        toastrSend('Enregistrement Réussie', 'Votre frequence a été enregistré avec succès.', 1);
                        break;
                    }
                    case "Update OK":{
                        toastrSend('Mise à jour Réussie', 'Votre frequence a été modifié avec succès.', 1);
                        break;
                    }
                    case "Suppr OK":{
                        toastrSend('Suppression Réussie', 'Votre frequence a été supprimé avec succès.', 1);
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

// Applique Sortable.js sur toutes les listes de frequence
function sortableLoad(){
    [].forEach.call(document.getElementById('link_frequence_content').getElementsByClassName('sortable-ul'), function (el){
        Sortable.create(el, {
                group: 'frequence',
                animation: 150,
                onAdd: function (/**Event*/evt) {
                    var itemEl = evt.item.id;  // Id element déplacé
                    var destination = evt.to.id; // Id de la liste de destination
                    var depart = evt.from.id;  // Id de la liste de départ
                    var provenance = $("#provenance_frequence").val(); // Id de la provenance de la donnée
                    updateSortable(itemEl, depart, destination, provenance);
	},
        });
    });
    var el = document.getElementById('list-non-used');
    var sortable = new Sortable(el, {
        group: {name: 'frequence', pull: 'clone'},
        animation: 150,
        onAdd: function (/**Event*/evt) {
            var itemEl = evt.item.id;  // Id element déplacé
            var destination = evt.to.id; // Id de la liste de destination
            var depart = evt.from.id;  // Id de la liste de départ
            var provenance = $("#provenance_frequence").val(); // Id de la provenance de la donnée
            updateSortable(itemEl, depart, destination, provenance);
            this.el.removeChild(evt.item);
	},
    });
}

$(window).on("popstate", function() {
    var anchor = location.hash || $("a[data-toggle='tab']").first().attr("href");
    $("a[href='" + anchor + "']").tab("show");
});