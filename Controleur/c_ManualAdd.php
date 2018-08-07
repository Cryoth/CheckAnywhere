<?php

include_once('Modele/BDD/Requetes/requete.php');

if(isset($_POST["exportExcelAll"]) || isset($_POST["exportExcelCurrent"])){
    include_once('Controleur/c_Generate_Excel.php');
}

$listMateriel = getMaterialListedByAtelier($PDO, $_GET["atelier"]);
$listMaterielToReturn = "";

$listMaterielToReturn .= "<option value='".$_GET["atelier"]."'>Tous</option>";

foreach($listMateriel as $row){
    $listMaterielToReturn .= "<option value='".$row["id"]."'>".$row["Nom"]."</option>";
}