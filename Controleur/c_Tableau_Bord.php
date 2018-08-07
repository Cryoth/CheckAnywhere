<?php 

include_once('Modele/BDD/Requetes/requete.php');

//Enregistrement commentaires
if(isset($_POST['input_indicateur']) && !empty($_POST['input_indicateur']) && isset($_POST['input_commentaire']) && !empty($_POST['input_commentaire']) && isset($_POST['input_date_semaine']) && !empty($_POST['input_date_semaine'])){
        
    $indicateur = $_POST['input_indicateur'];
    $commentaire = $_POST['input_commentaire'];
    $timestamp_date_semaine = $_POST['input_date_semaine']/1000;
    $date_semaine = date('Y-m-d', intval($timestamp_date_semaine));
    addNewCommentaire($PDO, $indicateur, $date_semaine, $commentaire);
}

if(isset($_POST['delete_commentaire']) && isset($_POST['input_date_semaine']) && !empty($_POST['input_date_semaine']) && isset($_POST['input_indicateur']) && !empty($_POST['input_indicateur'])){
    $indicateur = $_POST['input_indicateur'];
    $timestamp_date_semaine = $_POST['input_date_semaine']/1000;
    $date_semaine = date('Y-m-d', intval($timestamp_date_semaine));
    deleteCommentaire($PDO, $indicateur, $date_semaine);
}


//Vérifie si les modèles ont un affichage pour l'utilisateur en BDD
$nbreModeles = getNumberOfActifModele($PDO);
foreach($nbreModeles as $row){
    checkIfModeleExist($PDO, $_SESSION["UserId"], $row["id"]);
}

//Obtention des informations pour le graphique
$modeles = getAllGraphActifFromUser($PDO, $_SESSION["UserId"]);

$listGraph = [];

foreach($modeles as $keymodele => $modele){
    $indicateurs = getAllIndicateursFromGraph($PDO, $modele["id"]);
    $listGraph[$keymodele]["Id"] = $modele["id"];
    $listGraph[$keymodele]["Nom"] = $modele["Nom"];
    $listGraph[$keymodele]["Forme"] = $modele["FormeGraph"];
    $listGraph[$keymodele]["Temps"] = $modele["Periode"];
    
    foreach($indicateurs as $keyindicateur => $indicateur){
        $listGraph[$keymodele]["Identificateurs"][$keyindicateur]["IdIndicateur"] = $indicateur["id"];
        $listGraph[$keymodele]["Identificateurs"][$keyindicateur]["Nom"] = $indicateur["Nom"];
        $listGraph[$keymodele]["Identificateurs"][$keyindicateur]["Couleur"] = $indicateur["Couleur"];
        $listGraph[$keymodele]["Identificateurs"][$keyindicateur]["Ratio"] = $indicateur["Ratio"];
        $listGraph[$keymodele]["Identificateurs"][$keyindicateur]["Monetaire"] = $indicateur["Monetaire"];
        $listGraph[$keymodele]["Identificateurs"][$keyindicateur]["Objectif"]["Nom"] = $indicateur["Objectif_Libelle"];
        $listGraph[$keymodele]["Identificateurs"][$keyindicateur]["Objectif"]["Valeur"] = $indicateur["Objectif_Val"];

        foreach(unserialize($indicateur["Valeurs"]) as $keyValue => $values){
            $listGraph[$keymodele]["Identificateurs"][$keyindicateur]["Valeurs"][$keyValue]["Id"] = $values[2];
            $listGraph[$keymodele]["Identificateurs"][$keyindicateur]["Valeurs"][$keyValue]["AtelierId"] = $values[0];
            $listGraph[$keymodele]["Identificateurs"][$keyindicateur]["Valeurs"][$keyValue]["Unite"] = $values[3];
        }
    }
}

echo "<script type='text/javascript'>var client = '".$_SESSION['Database']."';</script>";

?>