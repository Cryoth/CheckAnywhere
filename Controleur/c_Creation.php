<?php

include_once('Modele/BDD/Requetes/requete.php');

$script = "<script src='Vue/js/graph-creation.js'></script>";

echo $_POST["select-Materiels"];

//Enregistrement du modèle
if(isset($_POST["enregMod"])){
    if(isset($_POST["hiddenResult1"], $_POST["color1"]) || isset($_POST["hiddenResult2"], $_POST["color2"]) || isset($_POST["hiddenResult3"], $_POST["color3"])){
        enregModele($PDO, $_POST["nomGraph"], $_POST["Date"], $_POST["chart"], $_SESSION["UserId"], $_POST["Position"]);
        //Vérifie si les modèles ont un affichage pour l'utilisateur en BDD
        updateModeleForAllUser($PDO, $_POST["nomGraph"], $_SESSION["UserId"], $_POST["chart"]);
    }
    if($_POST["hiddenResult1"] != NULL && $_POST["color1"] != NULL){
        enregModeleValues($PDO, $_POST["prod1"], $_POST["hiddenResult1"], $_POST["color1"], $_POST["objectifNom1"], $_POST["objectifVal1"]);
    }
    if($_POST["hiddenResult2"] != NULL && $_POST["color2"] != NULL){
        enregModeleValues($PDO, $_POST["prod2"], $_POST["hiddenResult2"], $_POST["color2"], $_POST["objectifNom2"], $_POST["objectifVal2"]);
    }
    if($_POST["hiddenResult3"] != NULL && $_POST["color3"] != NULL){
        enregModeleValues($PDO, $_POST["prod3"], $_POST["hiddenResult3"], $_POST["color3"], $_POST["objectifNom3"], $_POST["objectifVal3"]);
    }
}