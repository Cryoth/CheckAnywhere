// Changement des préférances de périodicité (hebdo / journalier)

$('#togglePeriod').on('change', function(){
	if($(this).prop("checked") == true){
       param = "periodicite=journalier";
    }else{
       param = "periodicite=hebdomadaire"
    }
	$.ajax({
        type: "POST",
        url: "Controleur/HTTPRequest/HTTPRequestChangePeriodicite.php",
        data: param,
        success: function(content){
            console.log("Valeur passée à " + content);
        },
        async: false
    });
});
