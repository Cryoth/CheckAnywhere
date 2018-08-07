<?php

include_once('Modele/BDD/Requetes/Authentification_SQL.php');

//Test de connexion et initialisation des variables $_SESSION si réussite, message d'erreur si échec

if(isset($_POST["login"], $_POST["password"]) && $_POST["login"] != "" && $_POST["password"] != ""){
    
    // Verification du login et mdp
    $user = LogUser($PDOAuth, $_POST["login"], sha1($_POST["password"]));
    
    // Si l'authentification a réussit initialise les variables de session
    if(isset($user[0])){
        $_SESSION["UserId"] = $user["id"];
        $_SESSION["Droit"] = $user["statut"];

        if($_SESSION["Droit"] != 1){
            
            $database = getDatabaseNameById($PDOAuth, $user["id_database"]);
            $_SESSION["Database"] = $database["nom"];

            include_once('Modele/BDD/Requetes/requete.php');

            // Applique une liaison entre l'utilisateur qui se connecte et tous les modèles existant
            $modeles = getAllModele($PDO, $user["id_user_db"]);

            foreach ($modeles as $modele) {
                checkIfModeleExist($PDO, $user["id_user_db"], $modele);
            }
            
            $currentUser = getCurrentUserData($PDO, $user["id_user_db"]);
            
            $_SESSION["UserId"] = $user["id_user_db"];
            $_SESSION["Nom"] = $currentUser["Login"];
            $_SESSION["Mail"] = $currentUser["Email"];
            $_SESSION["Autorisation"] = $currentUser["Droit"];

            $controleur = 'connexion';
            $vue = 'Vue/v_Tableau_Bord.php';
        }
        
        
    }
    else{
        $errorLog = "<br><p style='color: red;'>Identifiant ou mot de passe incorrect !</p>";
    }
}
else{
    $errorLog = "<br><p style='color: red;'>Identifiant ou mot de passe incorrect !</p>";
}