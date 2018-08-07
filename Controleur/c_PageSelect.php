<?php

//Permet la navigation quand l'utilisateur est connecté

if(isset($_GET["C"]) && $controleur != 'connexion'){
    $controleur = $_GET["C"];
}

switch ($controleur){
    case "connexion": include_once('Controleur/c_Tableau_Bord.php'); $vue = 'Vue/v_Tableau_Bord.php'; break;
    case "accueil": include_once('Controleur/c_Tableau_Bord.php'); $vue = 'Vue/v_Tableau_Bord.php'; break;
    case "creation": include_once('Controleur/c_Creation.php'); $vue = 'Vue/v_Creation.php'; break;
    case "modif": include_once('Controleur/c_Modification.php'); $vue = 'Vue/v_Modification.php'; break;
    case "data": include_once('Controleur/c_GestionDonnee.php'); $vue = 'Vue/v_GestionDonnee.php'; break;
    case "atelier": include_once('Controleur/c_GestionAtelier.php'); $vue = 'Vue/v_GestionAtelier.php'; break;
    case "prod": include_once('Controleur/c_Produit.php'); $vue = 'Vue/v_Produit.php'; break;
    case "manualAdd": include_once('Controleur/c_ManualAdd.php'); $vue = 'Vue/v_ManualAdd.php'; break;
    case "admin_user": include_once('Controleur/c_Admin.php'); $vue = 'Vue/v_Admin.php'; break;
    case "frequence": include_once('Controleur/c_GestionFrequence.php'); $vue = 'Vue/v_GestionFrequence.php'; break;
    case "user": include_once('Controleur/c_User.php'); $vue = 'Vue/v_User.php'; break;
    default : include_once('Controleur/c_Tableau_Bord.php'); $vue = 'Vue/v_Tableau_Bord.php'; break;
}