// PAGE GESTION MODELE

var tableau;

// Au lancement de la page

$(document).ready(function() {
    tableau = $('#gestionModele').DataTable({
                    "oLanguage": {
                      "sSearch": "Rechercher:",
                      "sZeroRecords": "Aucun modèle à afficher",
                    },
                    "info": false,
                    "paging": false,
                    "rowReorder": true,
                    "columnDefs": [ 
                        { targets: 0, visible: false },
                        { orderable: true, className: 'reorder', targets: [1, 2, 3, 4, 5, 6] },
                        {
                          "targets"  : 'no-sort',
                          "orderable": false,
                        },
                    ],
                });

    tableau.on('row-reorder', function ( e, details, edit ) {

        setTimeout(function() {
            var arr = new Array(),
                ids = tableau.columns(1).data().eq( 0 );

            $.each(ids, function(i, v){           
                arr.push(v);
            });

            var param = 'reorder=' + JSON.stringify(arr);
            $.ajax({
                type: "POST",
                url: "Controleur/HTTPRequest/HTTPRequestModele.php",
                data: param, 
                success: function(contenu){
                    toastrSend("Changement sauvegardé", "Votre ordre d'affichage des modèles a été modifié avec succès", 1)
                },
                async: false
            });
        }, 10); 

    } );  
});

$('#gestionModele tbody').on('click', 'button.delModele', function () {

    $('#btn-supprimer-modele').val($(this).val());

});

$('#btn-supprimer-modele').on('click', function(){

    var param = 's=' + $(this).val();
    $.ajax({
        type: "POST",
        url: "Controleur/HTTPRequest/HTTPRequestModele.php",
        data: param, 
        success: function(contenu){
            toastrSend("Suppression Réussie", "La suppression c'est effectuée avec succès", 1)
        },
        async: false
    });
    
    // Fait disparaitre le modal après le click
    $('#confirm-delete').modal('hide');

    //Supprime la ligne du tableau
    tableau.row($(".delModele").filter(function(){return this.value== $('#btn-supprimer-modele').val()}).parents('tr')).remove().draw();

});

//Affichage du modèle au check d'une checkbox
$(document).on('click', ".checkActif", function(){
    if (this.checked) {
        param = "actif=" + $(this).val();
        $.ajax({
            type: "POST",
            url: "Controleur/HTTPRequest/HTTPRequestModele.php",
            data: param, 
            success: function(contenu){
                toastrSend("Modification Réussie", "Le modèle est à présent visible", 1);
            },
            async: false
        });
    }
    else{
        param = "inactif=" + $(this).val();
        $.ajax({
            type: "POST",
            url: "Controleur/HTTPRequest/HTTPRequestModele.php",
            data: param, 
            success: function(contenu){
                toastrSend("Modification Réussit", "Le modèle est à présent invisible", 1);
            },
            async: false
        });
    }
});

//Validation des modifications
$(document).on('click', '.btn-modifier-modele', function(){
    // Récupération des données pour enregistrement
    param = "modif=" + $(this).val();
    param += "&NomModele=" + $('#ModeleName' + $(this).val()).val();
    param += "&PeriodeModele=" + $('#ModelePeriode' + $(this).val()).val();
    param += "&FormatModele=" + $('#ModeleFormat' + $(this).val()).val();
    param += "&IdIndic1Modele=" + $('#IdIndic' + $(this).val() + "1").val();
    param += "&NomIndic1Modele=" + $('#NomIndic' + $(this).val() + "1").val();
    param += "&Couleur1Modele=" + $('#CouleurIndic' + $(this).val() + "1").val();
    param += "&NomObj1Modele=" + $('#NomObjIndic' + $(this).val() + "1").val();
    param += "&ValObj1Modele=" + $('#ValObjIndic' + $(this).val() + "1").val();
    if(($('#NomIndic' + $(this).val() + "2").length > 0)){
        param += "&IdIndic2Modele=" + $('#IdIndic' + $(this).val() + "2").val();
        param += "&NomIndic2Modele=" + $('#NomIndic' + $(this).val() + "2").val();
        param += "&Couleur2Modele=" + $('#CouleurIndic' + $(this).val() + "2").val();
        param += "&NomObj2Modele=" + $('#NomObjIndic' + $(this).val() + "2").val();
        param += "&ValObj2Modele=" + $('#ValObjIndic' + $(this).val() + "2").val();
    }
    if(($('#NomIndic' + $(this).val() + "3").length > 0)){
        param += "&IdIndic3Modele=" + $('#IdIndic' + $(this).val() + "3").val();
        param += "&NomIndic3Modele=" + $('#NomIndic' + $(this).val() + "3").val();
        param += "&Couleur3Modele=" + $('#CouleurIndic' + $(this).val() + "3").val();
        param += "&NomObj3Modele=" + $('#NomObjIndic' + $(this).val() + "3").val();
        param += "&ValObj3Modele=" + $('#ValObjIndic' + $(this).val() + "3").val();
    }
    // Envoie des données récupérées en ajax pour traitement
    $.ajax({
        type: "POST",
        url: "Controleur/HTTPRequest/HTTPRequestModele.php",
        data: param, 
        success: function(contenu){
            // Envoie un toastr pour signaler le bon déroulement
            toastrSend("Modification Réussie", "Le modèle a été modifié avec succès", 1);
            
            // Recharge la page pour que les modifications soient appliqués au tableau
            location.reload();
        },
        async: false
    });
});

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