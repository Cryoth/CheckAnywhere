/*
 * =================================================================
 * GENERATION DES GRAPHIQUES POUR L'ENSEMBLE DES PAGES CHECKANYWHERE
 * =================================================================
 * 
 * Création de l'objet Js 'Graphique' et déploiement de ce même objet via Highcharts sur l'élément HTML ciblé
 * 
 */

/**
 * Create a global getSVG method that takes an array of charts as an argument. The SVG is returned as an argument in the callback.
 */
Highcharts.getSVG = function (charts, options, callback) {
    var svgArr = [],
        top = 0,
        width = 0,
        addSVG = function (svgres) {
            // Grab width/height from exported chart
            var svgWidth = +svgres.match(
                    /^<svg[^>]*width\s*=\s*\"?(\d+)\"?[^>]*>/
                )[1],
                svgHeight = +svgres.match(
                    /^<svg[^>]*height\s*=\s*\"?(\d+)\"?[^>]*>/
                )[1],
                // Offset the position of this chart in the final SVG
                svg = svgres.replace('<svg', '<g transform="translate(0,' + top + ')" ');
            svg = svg.replace('</svg>', '</g>');
            top += svgHeight;
            width = Math.max(width, svgWidth);
            svgArr.push(svg);
        },
        exportChart = function (i) {
            if (i === charts.length) {
                return callback('<svg height="' + top + '" width="' + width +
                  '" version="1.1" xmlns="http://www.w3.org/2000/svg">' + svgArr.join('') + '</svg>');
            }
            charts[i].getSVGForLocalExport(options, {}, function () {
                console.log("Failed to get SVG");
            }, function (svg) {
                addSVG(svg);
                return exportChart(i + 1); // Export next only when this SVG is received
            });
        };
    exportChart(0);
};

/**
 * Create a global exportCharts method that takes an array of charts as an argument,
 * and exporting options as the second argument
 */
Highcharts.exportCharts = function (charts, options) {
    options = Highcharts.merge(Highcharts.getOptions().exporting, options);

        // Get SVG asynchronously and then download the resulting SVG
    Highcharts.getSVG(charts, options, function (svg) {
        Highcharts.downloadSVGLocal(svg, options, function () {
            console.log("Failed to export on client side");
        });
    });
};

// Set global default options for all charts
Highcharts.setOptions({
    exporting: {
        fallbackToExportServer: false // Ensure the export happens on the client side or not at all
    }
});

var listchart = [];

$.each(json_graphiques, function(key, value) {
    listchart.push(addHighchart(value.Id, value.Nom, value.Forme, value.Temps, value.Identificateurs));
});

// CONFIGURATION GRAPHIQUE

function addHighchart(id, nom, forme, startTime, values){

    var sizeClass, typeGraph, commentaires = [];
    
    switch(forme){
        case "histogramme": typeGraph = "column"; break;
        case "courbe": typeGraph = "line"; break;
        default: typeGraph = "histogramme"; break;
    }
    
    if(startTime <= 26){
        sizeClass = "col-centered";
    }else if(startTime > 26){
        sizeClass = "col-lg-12";
    }
    
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
    
    var date = new Date();
    date.setDate(date.getDate() - (7 * (startTime - 1)));
    
    // Ajuste le date de départ pour qu'elle commence un lundi si ce n'est pas encore le cas
    if(date.getDay() != 1){
        date.setDate(date.getDate() - (date.getDay() + 6) % 7);
    }
    
    var series = [];
    var objectifs = [];
    var commentaires = [];
    
    $.each(values, function(key, value) {
        var data = getPreparedData(value, startTime).split(",").map(function(item) {
            return parseFloat(item, 10);
        });

        c = getCommentByIdentificateur(value.IdIndicateur);
        commentaires[value.IdIndicateur] = new Array();
        if(c != null){
            commentaires[value.IdIndicateur][c.Date] = c.Commentaire;
        }
        series.push({
            id: value.IdIndicateur,
            name: value.Nom,
            color: value.Couleur,
            borderColor: value.Couleur,
            data: data,
            pointInterval: 7 * 24 * 36e5, // une semaine
            pointStart: Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()),
        });
        objectifs.push({
            value: value.Objectif.Valeur,
            color: value.Couleur,
            dashStyle: 'solid',
            width: 2,
            zIndex: 5,
            label: {
                text: value.Objectif.Nom + " (" + value.Objectif.Valeur + ")"
            }
        });
    });
    
    $("<div id='" + id + "' class='" + nom + " " + sizeClass + "'>").appendTo("#GraphContainer").highcharts({
        chart:{
            events:{
                load: function(){
                    var count = this.series.length;
                    for(var i=0; i < count; i++ ){
                        var id = this.series[i].userOptions.id;
                        var countdata = this.series[i].data.length;
                        if(Object.keys(commentaires[id]).length > 0){
                            for(var j = 0; j < countdata; j++){
                                var key = new Date(parseInt(this.series[i].data[j].x));
                                key = key.getFullYear() + "-" + (key.getMonth() + 1) + "-" + key.getDate();
                                if(key in commentaires[id]){
                                    this.series[i].data[j].update({color : '#FFFFFF'});
                                }
                            };
                        }
                    };
                },
            },
            type: typeGraph,
            zoomType: 'x',
        },
        title: {
            text: nom,
            align: 'center',
            margin: 0,
            x: 30,
        },
        credits: {
            enabled: false
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            floating: true,
            y: 70,
        },
        tooltip: {
            formatter: function () {
                this.fillColor = "#ff4C4C";
                var s = '<b>' + this.series.name +'</b><br/>' + '<b>Date:</b> ' + Highcharts.dateFormat('%e-%m-%Y', new Date(this.x)) + '<br/><b>Valeur:</b> ' + this.y;
                var key = new Date(parseInt(this.x));
                key = key.getFullYear() + "-" + (key.getMonth() + 1) + "-" + key.getDate();
                if(typeof commentaires[this.series.userOptions.id][key] != "undefined"){
                    s += '<br/><b>Commentaire : </b>' + commentaires[this.series.userOptions.id][key];
                }
                else{
                    s += '<br/><b>Pas de commentaire</b>';
                }
                return s;
            }
        },
        xAxis: {
            type: 'datetime',
            tickInterval: 7 * 24 * 36e5, // une semaine
            labels: {
                formatter: function(){
                    var date = new Date(this.value),
                    day = date.getUTCDay() == 0 ? 7 : date.getUTCDay(),
                    dayNumber;
                    date.setDate(date.getUTCDate() + 4 - day);
                    dayNumber = Math.floor((date.getTime() - new Date(date.getUTCFullYear(), 0, 1, -6)) / 86400000);
                    return "S " + (1 + Math.floor(dayNumber / 7));
                },
            }
        },
        yAxis: {
            title: {
                text: ''
            },
            plotLines: objectifs,
        },
        series: series,
        plotOptions: {
            series:{
                lineWidth: 1,
                states: {
                    animation: false,
                    hover: {
                        enabled: false,
                    }
                },
                cursor: 'pointer',
                point: {
                    events: {
                        click: function(){
                                var key = new Date(parseInt(this.x));
                                key = key.getFullYear() + "-" + (key.getMonth() + 1) + "-" + key.getDate();
                                $('#input_indicateur').val(this.series.userOptions.id);
                                $('#input_date_semaine').val(this.x);
                                if(typeof commentaires[this.series.userOptions.id][key] != "undefined"){
                                    $('#input_commentaire').val(commentaires[this.series.userOptions.id][key]);
                                }
                                $('#myModal').modal('show');
                        }
                    },
                },
            },
        },
        exporting: {
            url: "http://export.highcharts.com/",
            buttons: {
                contextButton: {
                    onclick: function(){
                            this.exportChart();
                    }
                }
            }
        }
    });
    
    var chart = $("#" + id).highcharts();
    
    // Send chart config to Highcharts export server (JSON -> Highcharts server -> PNG URL)
    var data = {
        options: JSON.stringify(chart.userOptions),
        filename: 'test',
        type: 'image/png',
        async: true
    };

    var exportUrl = 'http://export.highcharts.com/';
    $.post(exportUrl, data, function(d) {
        var url = exportUrl + d;
        console.log(url);
        var param = "url=" + url + "&idModele=" + id;
        $.ajax({
            type: "POST",
            url: "Controleur/HTTPRequest/HTTPRequestSaveImgByURL.php",
            data: param,
            success: function(content){
                if(content != ""){
                    console.log(content);
                }
            },
            async: false
        });
    });

    return chart;
}

function getPreparedData(value, startTime){
    
    var param = "time=" + startTime + "&value=" + JSON.stringify(value);
    var resultat;
    
    $.ajax({
        type: "POST",
        url: "Controleur/HTTPRequest/HTTPRequestCreate.php",
        data: param,
        success: function(content){
            resultat = content;
        },
        async: false
    });
    
    return resultat;
}

function getCommentByIdentificateur(id){
    
    var param = "identificateur=" + id;
    var resultat;
    
    $.ajax({
        type: "POST",
        url: "Controleur/HTTPRequest/HTTPRequestCommentaire.php",
        data: param,
        success: function(content){
            resultat = JSON.parse(content);
        },
        async: false
    });
    
    return resultat;
}


$('#export-pdf').click(function () {
    Highcharts.exportCharts([listchart], {
        type: 'application/pdf'
    });
});

$('#export-png').click(function () {
    Highcharts.exportCharts(listchart);
});