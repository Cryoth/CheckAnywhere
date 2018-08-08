
var graphique, valeurs = [], date = new Date();

Highcharts.dateFormats = {
    W: function (timestamp) {
        var date = new Date(timestamp),
            day = date.getUTCDay() == 0 ? 7 : date.getUTCDay(),
            dayNumber;
        date.setDate(date.getUTCDate() + 4 - day);
        dayNumber = Math.floor((date.getTime() - new Date(date.getUTCFullYear(), 0, 1, -6)) / 86400000);
        return 1 + Math.floor(dayNumber / 7);

    }
}

//A l'ouverture de la page
$(document).ready(function(){
    graphique = Highcharts.chart('GraphContainer', {
        chart: {
            type: "column",
            height: 250,
        },
        title: {
            text: ''
        },
        yAxis: {
            title: {
                text: ''
            },
        },
        tooltip: {
            formatter: function () {
                this.fillColor = "#ff4C4C";
                var s = '<b>' + this.series.name +'</b><br/>' + '<b>Date:</b> ' + Highcharts.dateFormat('%e-%m-%Y', new Date(this.x)) + '<br/><b>Valeur:</b> ' + this.y;
                return s;
            }
        },
        xAxis: {
            type: 'datetime',
            tickInterval: 7 * 24 * 36e5, // une semaine
            labels: {
                format: '{value:S %W}',
                align: 'right',
            }
        },
    });
});

//Au changement du nom du graphique
$(document).on('change', "[name='nomGraph']", function(){
    graphique.setTitle({text: $(this).val()});
});

//Au changement de la couleur 1
$(document).on('change', "[name='color1']", function(){
    graphique.series[0].options.color = $(this).val();
    graphique.series[0].update(graphique.series[0].options);
    ObjTitle = $("[name='objectifNom1']").val();
    ObjVal = $("[name='objectifVal1']").val();
    ObjColor = $(this).val();
    graphique.yAxis[0].removePlotLine("objectif1");
    graphique.yAxis[0].addPlotLine({
        id: "objectif1",
        value: ObjVal,
        color: ObjColor,
        dashStyle: 'solid',
        width: 2,
        zIndex: 5,
        label: {
            text: ObjTitle + " (" + ObjVal + ")"
        }
    });
});

//Au changement de la couleur 2
$(document).on('change', "[name='color2']", function(){
    graphique.series[1].options.color = $(this).val();
    graphique.series[1].update(graphique.series[1].options);
    ObjTitle = $("[name='objectifNom2']").val();
    ObjVal = $("[name='objectifVal2']").val();
    ObjColor = $(this).val();
    graphique.yAxis[0].removePlotLine("objectif2");
    graphique.yAxis[0].addPlotLine({
        id: "objectif2",
        value: ObjVal,
        color: ObjColor,
        dashStyle: 'solid',
        width: 2,
        zIndex: 5,
        label: {
            text: ObjTitle + " (" + ObjVal + ")"
        }
    });
});

//Au changement de la couleur 3
$(document).on('change', "[name='color3']", function(){
    graphique.series[2].options.color = $(this).val();
    graphique.series[2].update(graphique.series[2].options);
    ObjTitle = $("[name='objectifNom3']").val();
    ObjVal = $("[name='objectifVal3']").val();
    ObjColor = $(this).val();
    graphique.yAxis[0].removePlotLine("objectif3");
    graphique.yAxis[0].addPlotLine({
        id: "objectif3",
        value: ObjVal,
        color: ObjColor,
        dashStyle: 'solid',
        width: 2,
        zIndex: 5,
        label: {
            text: ObjTitle + " (" + ObjVal + ")"
        }
    });
});

//Empeche de soumettre le formulaire au clique sur entrer
$(document).keypress(function(event){
    if(event.keyCode === 10 || event.keyCode === 13){
        event.preventDefault();
    }
});

//Au clique sur un radio bouton "type de graphe"
$("#radio-courbe").on("click", function(){
    graphique.series[0].update({type: "line"});
    graphique.series[1].update({type: "line"});
    graphique.series[2].update({type: "line"});
});

$("#radio-histogramme").on("click", function(){
    graphique.series[0].update({type: "column"});
    graphique.series[1].update({type: "column"});
    graphique.series[2].update({type: "column"});
});

//Au remplissage du nom de l'objectif 1
$(document).on('change', "[name='objectifNom1']", function(){
    ObjTitle = $(this).val();
    ObjVal = $("[name='objectifVal1']").val();
    ObjColor = $("[name='color1']").val();
    graphique.yAxis[0].removePlotLine("objectif1");
    graphique.yAxis[0].addPlotLine({
        id: "objectif1",
        value: ObjVal,
        color: ObjColor,
        dashStyle: 'solid',
        width: 2,
        zIndex: 5,
        label: {
            text: ObjTitle + " (" + ObjVal + ")"
        }
    });
});

//Au changement de valeur de la case objectif valeur 1
$(document).on('change', "[name='objectifVal1']", function(){
    ObjVal = $(this).val();
    ObjTitle = $("[name='objectifNom1']").val();
    ObjColor = $("[name='color1']").val();
    graphique.yAxis[0].removePlotLine("objectif1");
    graphique.yAxis[0].addPlotLine({
        id: "objectif1",
        value: ObjVal,
        color: ObjColor,
        dashStyle: 'solid',
        width: 2,
        zIndex: 5,
        label: {
            text: ObjTitle + " (" + ObjVal + ")"
        }
    });
});

//Au remplissage du nom de l'objectif 2
$(document).on('change', "[name='objectifNom2']", function(){
    ObjTitle = $(this).val();
    ObjVal = $("[name='objectifVal2']").val();
    ObjColor = $("[name='color2']").val();
    graphique.yAxis[0].removePlotLine("objectif2");
    graphique.yAxis[0].addPlotLine({
        id: "objectif2",
        value: ObjVal,
        color: ObjColor,
        dashStyle: 'solid',
        width: 2,
        zIndex: 5,
        label: {
            text: ObjTitle + " (" + ObjVal + ")"
        }
    });
});

//Au changement de valeur de la case objectif valeur 2
$(document).on('change', "[name='objectifVal2']", function(){
    ObjVal = $(this).val();
    ObjTitle = $("[name='objectifNom2']").val();
    ObjColor = $("[name='color2']").val();
    graphique.yAxis[0].removePlotLine("objectif2");
    graphique.yAxis[0].addPlotLine({
        id: "objectif2",
        value: ObjVal,
        color: ObjColor,
        dashStyle: 'solid',
        width: 2,
        zIndex: 5,
        label: {
            text: ObjTitle + " (" + ObjVal + ")"
        }
    });
});

//Au remplissage du nom de l'objectif 3
$(document).on('change', "[name='objectifNom3']", function(){
    ObjTitle = $(this).val();
    ObjVal = $("[name='objectifVal3']").val();
    ObjColor = $("[name='color3']").val();
    graphique.yAxis[0].removePlotLine("objectif3");
    graphique.yAxis[0].addPlotLine({
        id: "objectif3",
        value: ObjVal,
        color: ObjColor,
        dashStyle: 'solid',
        width: 2,
        zIndex: 5,
        label: {
            text: ObjTitle + " (" + ObjVal + ")"
        }
    });
});

//Au changement de valeur de la case objectif valeur 3
$(document).on('change', "[name='objectifVal3']", function(){
    ObjVal = $(this).val();
    ObjTitle = $("[name='objectifNom3']").val();
    ObjColor = $("[name='color3']").val();
    graphique.yAxis[0].removePlotLine("objectif3");
    graphique.yAxis[0].addPlotLine({
        id: "objectif3",
        value: ObjVal,
        color: ObjColor,
        dashStyle: 'solid',
        width: 2,
        zIndex: 5,
        label: {
            text: ObjTitle + " (" + ObjVal + ")"
        }
    });
});

//Au clique sur le bouton Effacer
$(document).on('click', '.clearData', function(e){
    e.preventDefault();
    if(!$(this).parent("tr").find("td:first").is(":empty")){
        $(this).parent().find("input").val('');
        $(this).parent().parent().parent().find(".hiddenResult").val('');
        $(this).parent().parent().find("input[type='text']").val('');
        if($("[name='prod" + $(this).val() + "']").val() == ''){
            $("input[name='objectifNom" + $(this).val() + "']").val('');
            $("input[name='objectifVal" + $(this).val() + "']").val('');
            if(graphique.series.length < $(this).val()){
                graphique.series[(graphique.series.length - 1)].remove();
            }else{
                graphique.series[($(this).val() - 1)].remove();
            }
            graphique.yAxis[0].removePlotLine("objectif" + $(this).val());
            dataToFill = $(this).val();
        }
    }
});

//Confirmation des données cumulés
$(document).on('click', '#ValidCumul', function(){
    
    var cumul = "", name = "", monetaire , ratio, couleur, idVal = [];
    
    $("#valueCumul option").each(function(index){
        
        var tab = $(this).val().split("|");
        
        idVal.push({Id: tab[2], AtelierId: tab[0], Unite: tab[3]});
        monetaire = tab[4];
        ratio = tab[5];
        couleur = $("[name='color" + dataToFill + "']").css("color");
        
        if(index == 0){
            cumul += $(this).val();
            name += $(this).text();
        }else{
            cumul += "_" + $(this).val();
            name += " | " + $(this).text();
        }
    });
    
    $("[name='prod" + dataToFill + "']").val(name);
    $("[name='hiddenResult" + dataToFill + "']").val(cumul);
    
    if(dataToFill < 3 && !$("#valueCumul").is(':empty')){
        dataToFill += 1;
    }
    
    var value = {Monetaire: monetaire, Ratio: ratio, Valeurs: idVal};
    
    valeurs[dataToFill] = value;
    
    var data = getPreparedData(value, 52).split(",").map(function(item){
        return parseFloat(item, 10);
    });
    
    var d = new Date(date);
    if(d.getDay() != 1){
        d.setDate(d.getDate() - (d.getDay() + 6) % 7);
    }
    d.setDate(date.getDate() - (7 * 52));

    var type = "column";

    if($('#radio-courbe').is(':checked')) {
        type = "line";
    }

    graphique.addSeries({
        id: dataToFill,
        name: name,
        type: type,
        color: couleur,
        data: data,
        pointInterval: 7 * 24 * 36e5, // une semaine
        pointStart: Date.UTC(d.getFullYear(), d.getMonth(), d.getDate()),
    });
    
    d = new Date(date);
    if(d.getDay() != 1){
        d.setDate(d.getDate() - (d.getDay() + 6) % 7);
    }
    d.setDate(date.getDate() - (7 * $("[name='DateText']").val()));
    graphique.xAxis[0].setExtremes(Date.UTC(d.getFullYear(), (d.getMonth() + 1), d.getDate()), Date.UTC(date.getFullYear(), (date.getMonth() + 1), date.getDate()));
});

$("[name='Date'], [name='DateText']").on('change', function(){
    
    time = $(this).val();
    var d = new Date(date);
    d.setDate(date.getDate() - (7 * time));
    $("[name='DateText']").val($(this).val());
    $("[name='Date']").val($(this).val());
    
    graphique.xAxis[0].setExtremes(Date.UTC(d.getFullYear(), (d.getMonth() + 1), d.getDate()), Date.UTC(date.getFullYear(), (date.getMonth() + 1), date.getDate())); 
});
