<?php

// Récupère les requêtes SQL
include_once('Modele/BDD/Requetes/Authentification_SQL.php');

// Récupère la liste des bases de données accessibles par l'admin
$listDatabase = getAllDatabase($PDOAuth);

if(isset($_POST["submit_database"])){
	$database = getDatabaseNameById($PDOAuth, $_POST["submit_database"]);
    $_SESSION["Database"] = $database["nom"];

    include_once('Modele/BDD/Requetes/requete.php');
    
    $user = getUserIdCurrentDatabase($PDOAuth, $_SESSION["UserId"], $_POST["submit_database"]);

    $_SESSION["UserId"] = $user["id_user_db"];

    $currentUser = getCurrentUserData($PDO, $_SESSION["UserId"]);

    // Applique une liaison entre l'utilisateur qui se connecte et tous les modèles existant
    $modeles = getAllModele($PDO, $user["id_user_db"]);

    foreach ($modeles as $modele) {
        checkIfModeleExist($PDO, $user["id_user_db"], $modele);
    }
    
    $_SESSION["Nom"] = $currentUser["Login"];
    $_SESSION["Mail"] = $currentUser["Email"];
    $_SESSION["Autorisation"] = $currentUser["Droit"];
    $_SESSION["Periodicite"] = $currentUser["Periodicite"];

    $controleur = 'connexion';
    include('Controleur/c_PageSelect.php');
}