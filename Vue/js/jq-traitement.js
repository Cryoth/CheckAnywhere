//Global variable
var ordre = 0;
var dataToFill = 1;

//Inititialisation des selectBox du filtre au lancement de la page
$(document).ready(function(){
    var param = "f=materiel" + "&source=" + $("[name='SelectSource']:checked").val();
    $("[name='SelectMateriel']").load("Controleur/HTTPRequest/HTTPRequestFiltre.php", param, function(){
        var param = "f=unite&materiel=" + $("[name='SelectMateriel']").val() + "&source=" + $("[name='SelectSource']:checked").val();
        $("[name='SelectUnite']").load("Controleur/HTTPRequest/HTTPRequestFiltre.php", param, function(){
            if($("[name='SelectEuros']").is(":checked")){
                var param = "a=subfiltre&materiel=" + $("[name='SelectMateriel']").val() + "&source=" + $("[name='SelectSource']:checked").val() + "&unite=" + $("[name='SelectUnite']").val() + "&euro=1";
            }else{
                var param = "a=subfiltre&materiel=" + $("[name='SelectMateriel']").val() + "&source=" + $("[name='SelectSource']:checked").val() + "&unite=" + $("[name='SelectUnite']").val() + "&euro=0";
            }
            if($("[name='SelectFrequence']").is(':checked')){
                param = param + "&frequence=1";
            }else{
                param = param + "&frequence=0";
            }
            $("#valueSelector").empty();
            $("#valueSelector").load("Controleur/HTTPRequest/HTTPRequestFiltre.php", param);
        });
    });
});

$("[name='SelectMateriel']").change(function(){
    var param = "f=unite&materiel=" + $("[name='SelectMateriel']").val() + "&source=" + $("[name='SelectSource']:checked").val();
    if($("[name='SelectEuros']").is(':checked')){
        param += "&euros=1";
    }
    if($("[name='SelectFrequence']").is(':checked')){
        param += "&frequence=1";
    }
    $("[name='SelectUnite']").load("Controleur/HTTPRequest/HTTPRequestFiltre.php", param);

    //Si la valeur est en euro
    if($("[name='SelectEuros']").is(":checked")){
        var param = "a=subfiltre&materiel=" + $("[name='SelectMateriel']").val() + "&source=" + $("[name='SelectSource']:checked").val() + "&unite=" + $("[name='SelectUnite']").val() + "&euro=1";
    }else{
        var param = "a=subfiltre&materiel=" + $("[name='SelectMateriel']").val() + "&source=" + $("[name='SelectSource']:checked").val() + "&unite=" + $("[name='SelectUnite']").val() + "&euro=0";
    }
    if($("[name='SelectFrequence']").is(':checked')){
        param = param + "&frequence=1";
    }else{
        param = param + "&frequence=0";
    }
    $("#valueSelector").empty();
    $("#valueSelector").load("Controleur/HTTPRequest/HTTPRequestFiltre.php", param);
});

$(document).on('click', "[name='SelectEuros']", function(){
    var param = "f=unite&materiel=" + $("[name='SelectMateriel']").val() + "&source=" + $("[name='SelectSource']:checked").val();
    if($("[name='SelectEuros']").is(':checked')){
        param += "&euros=1";
    }
    if($("[name='SelectFrequence']").is(':checked')){
        param += "&frequence=1";
    }
    $("[name='SelectUnite']").load("Controleur/HTTPRequest/HTTPRequestFiltre.php", param);
    $("#valueSelector").empty();
    $("#valueCumul").empty();
});

$(document).on('click', "[name='SelectFrequence']", function(){
    var param = "f=unite&materiel=" + $("[name='SelectMateriel']").val() + "&source=" + $("[name='SelectSource']:checked").val();
    if($("[name='SelectFrequence']").is(':checked')){
        param += "&frequence=1";
    }
    if($("[name='SelectEuros']").is(':checked')){
        param += "&euros=1";
    }
    $("[name='SelectUnite']").load("Controleur/HTTPRequest/HTTPRequestFiltre.php", param);
    $("#valueSelector").empty();
    $("#valueCumul").empty();
});

$(document).on("click", "[name='SelectSource']", function(){
    $("#valueSelector").empty();
    $("#valueCumul").empty();
    var param = "f=materiel&source=" + $("[name='SelectSource']:checked").val();
    $("[name='SelectMateriel']").load("Controleur/HTTPRequest/HTTPRequestFiltre.php", param, function(){
        var param = "f=unite&materiel=" + $("[name='SelectMateriel']").val() + "&source=" + $("[name='SelectSource']:checked").val();
        if($("[name='SelectEuros']").is(':checked')){
            param += "&euros=true";
        }
        if($("[name='SelectFrequence']").is(':checked')){
            param += "&frequence=1";
        }
        $("[name='SelectUnite']").load("Controleur/HTTPRequest/HTTPRequestFiltre.php", param);
    });
});

//Suppression des valeurs précédentes pour éviter mélange de valeurs
$(document).on("change", "[name='SelectUnite']", function(){
    if(!$("[name='SelectEuros']").is(':checked')){
        $("#valueSelector").empty();
        if($("#valueCumul option:first").length > 0){
            if($("#valueCumul option:first").val().split("|")[3] !== $(this).val()){
                $("#valueCumul").empty();
            }
        }
    }
    //Si la valeur est en euro
    if($("[name='SelectEuros']").is(":checked")){
        var param = "a=subfiltre&materiel=" + $("[name='SelectMateriel']").val() + "&source=" + $("[name='SelectSource']:checked").val() + "&unite=" + $("[name='SelectUnite']").val() + "&euro=1";
    }else{
        var param = "a=subfiltre&materiel=" + $("[name='SelectMateriel']").val() + "&source=" + $("[name='SelectSource']:checked").val() + "&unite=" + $("[name='SelectUnite']").val() + "&euro=0";
    }
    if($("[name='SelectFrequence']").is(':checked')){
        param = param + "&frequence=1";
    }else{
        param = param + "&frequence=0";
    }
    $("#valueSelector").empty();
    $("#valueSelector").load("Controleur/HTTPRequest/HTTPRequestFiltre.php", param);
});

//Gestion du visuel du bouton CIP/CHECK
$(document).on("click", "#CIP", function(){
    if(!$(this).hasClass('active')){
        $('#CHECK').removeClass('active');
        $('#CIP').addClass('active');
    }
});

$(document).on("click", "#CHECK", function(){
    if(!$(this).hasClass('active')){
        $('#CIP').removeClass('active');
        $('#CHECK').addClass('active');
    }
});

//Sélection d'un produit pour ajout à la liste au double clique
$(document).on('dblclick', '#valueSelector option', function(){
    if(!$("#valueCumul option[value='" + $(this).val() + "']").length > 0){
    $("#valueCumul").append("<option class='optionselected' value='" + $(this).val() + "'>" + $(this).text() + "</option>");
    }
});

//Suppression d'un produit au double clique
$(document).on('dblclick', '#valueCumul option', function(){
    $(this).remove();
});

// TRAITEMENT PAGE AJOUT DONNEES

var periodicite = "on";

// Affichage et paramétrage du datepicker
$('.datepicker').datepicker({language: 'fr', format: 'dd/mm/yyyy', calendarWeeks: true});
$('.datepicker').datepicker('setDate', new Date());

// Affichage du select2
$('#select-Materiels').select2({
    placeholder: "Selectionnez un Matériel",
    allowClear: true
});

// Evenements demandant à recharger la liste des données
$(document).ready(function(){
    if($('.tab-hebdo-journ').length) {
        loadDOMDataList(0);
    }
});

// Fonction chargeant le tableau des données pour la page Ajout Donnees
function loadDOMDataList(change){
    var date = $('.datepicker').val();
    var materiel = $('#select-Materiels').val();
    var param = "action=generate&date=" + date + "&periodicite=" + periodicite + "&decalage=" + change;
    if($('#select-Materiels option:selected').text() == "Tous"){
        param += "&atelier=" + materiel ;
    }else{
        param += "&materiel=" + materiel ;
    }
    
    $.ajax({
        type: "POST",
        url: "Controleur/HTTPRequest/HTTPRequestDataAdd.php",
        data: param, 
        success: function(contenu){
            $('.tab-hebdo-journ').append(contenu);
        },
        async: false
    });
    
    $("[name='exportExcelCurrent']").val($('#select-Materiels').val());
}

function changeData(date, value, name){
    var param = "action=modifieVal&date=" + date + "&value=" + value + "&name=" + name + "&periodicite=" + periodicite;
    $.ajax({
        type: "POST",
        url: "Controleur/HTTPRequest/HTTPRequestDataAdd.php",
        data: param, 
        success: function(contenu){
            console.log(contenu);
        },
        async: false
    });
}

function changeComm(date, value, name){
    var param = "action=modifieComm&date=" + date + "&value=" + value + "&name=" + name + "&periodicite=" + periodicite;
    $.ajax({
        type: "POST",
        url: "Controleur/HTTPRequest/HTTPRequestDataAdd.php",
        data: param, 
        success: function(contenu){
            console.log(contenu);
        },
        async: false
    });
}

function updateTotalSemaine(date, value, name){
    var param = "action=modifieTotal&date=" + date + "&value=" + value + "&name=" + name + "&periodicite=" + periodicite;
    var result;
    $.ajax({
        type: "POST",
        url: "Controleur/HTTPRequest/HTTPRequestDataAdd.php",
        data: param, 
        success: function(contenu){
            console.log(contenu);
            result = contenu;
        },
        async: false
    });
    return result;
}


// Passage de Hebdo à Journalier et vice-versa
$(document).on('change', '#togglePeriod', function(){
    $('.tab-hebdo-journ').empty();
    if(periodicite === "on"){
        periodicite = "off";
    }else{
        periodicite = "on";
    }
    loadDOMDataList(0);
});

$(document).on('change', '.datepicker', function(){
    $('.tab-hebdo-journ').empty();
    loadDOMDataList(0);
});

$(document).on('change', '#select-Materiels', function(){
    $('.tab-hebdo-journ').empty();
    if($(this).text() == "Tous"){
        $("[name='exportExcelCurrent']").hide();
    }else{
        $("[name='exportExcelCurrent']").show();
    }
    loadDOMDataList(0);
});

$(document).on('change', '.addDataInput', function(){
    var id = $(this).attr("name").split("|");
    var value = $(this).val();
    changeData(id[0], value, id[1]);
    if(periodicite == "on"){
        var result = updateTotalSemaine(id[0], value, id[1]);
        $(this).parent().parent().find(".containTotal").find(".addDataInput").val(result);
    }
});

$(document).on('change', '.addCommInput', function(){
    var id = $(this).attr("name").split("|");
    var value = $(this).val();
    changeComm(id[0], value, id[1]);
});

$('.btn-change-date').on('click', function(){
    $('.tab-hebdo-journ').empty();
    var oldDate = toDate($('.datepicker').val());
    if($(this).attr("name") == "precedent"){
        loadDOMDataList(-1);
        $('.datepicker').datepicker('update', decrementDays(oldDate, 7));
    }else{
        loadDOMDataList(1);
        $('.datepicker').datepicker('update', addDays(oldDate, 7));
    }
});

$('#toggle-send-dashboard').on('change', function(){
    if($(this).is(':checked')){
        param = "sendmail=1";
    }
    else{
        param = "sendmail=0";
    }
    param += "&user=" + $(this).val();
    $.ajax({
        type: "GET",
        url: "Controleur/HTTPRequest/HTTPRequestUser.php",
        data: param,
        success: function(content){
            console.log(content);
        },
        async: false
    });
});

function addDays(date, days) {
    var result = new Date(date);
    result.setDate(result.getDate() + days);
    return result;
}

function decrementDays(date, days) {
    var result = new Date(date);
    result.setDate(result.getDate() - days);
    return result;
}

function toDate(dateStr) {
    var parts = dateStr.split("/");
    return new Date(parts[2], parts[1] - 1, parts[0]);
}