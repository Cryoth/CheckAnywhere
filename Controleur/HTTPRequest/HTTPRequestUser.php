<?php

session_start();

// Récupération des identifiants de connexion
$access = parse_ini_file("../../Modele/BDD/Config/configBDD.ini");

// Connexion à la base d'authentification
try{
    $PDOAuth = new PDO($access[engine].':dbname='.$access[name].';host='.$access[host], $access[user], $access[pass]);
    $PDOAuth->query("SET NAMES 'utf8'");
}  
catch(PDOException $e){
    echo "Erreur de connexion : ".$e->getMessage();
}

if(isset($_SESSION["Database"])){

    // Connexion BDD courrante
    try{
        $PDO = new PDO($access[engine].':dbname='.$_SESSION["Database"].';host='.$access[host], $access[user], $access[pass]);
        $PDO->query("SET NAMES 'utf8'");
    }
    catch(PDOException $e){
        echo "Erreur de connexion : ".$e->getMessage();
    }

}

if(isset($_GET["name"])){
    $stmt = $PDO->prepare("UPDATE Client SET Login = :name WHERE id = :num");
    $stmt->bindValue('num', $_GET['num'], PDO::PARAM_INT);
    $stmt->bindValue('name', $_GET['name'], PDO::PARAM_STR);
    $stmt->execute();
    echo "Modification du Login réussi.";
}

if(isset($_GET["mail"])){
    $stmt = $PDO->prepare("UPDATE Client SET Email = :mail WHERE id = :num");
    $stmt->bindValue('num', $_GET['num'], PDO::PARAM_INT);
    $stmt->bindValue('mail', $_GET['mail'], PDO::PARAM_STR);
    $stmt->execute();
    echo "Modification de l'Email réussi.";
}

if(isset($_GET["droit"])){
    $stmt = $PDO->prepare("UPDATE Client SET Droit = :droit WHERE id = :num");
    $stmt->bindValue('num', $_GET['num'], PDO::PARAM_INT);
    $stmt->bindValue('droit', $_GET['droit'], PDO::PARAM_INT);
    $stmt->execute();
    echo "Modification du statut réussi.";
}

if(isset($_GET["suppr"])){
    try{
        //Suppression sur Table Client_Modele
        $stmt = $PDO->prepare("DELETE FROM Client_Modele WHERE idClient = :num");
        $stmt->bindValue('num', $_GET['suppr'], PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $PDO->prepare("SELECT Login FROM Client WHERE id = :num");
        $stmt->bindValue('num', $_GET['suppr'], PDO::PARAM_INT);
        $stmt->execute();
        $userlog = $stmt->fetch();

        //Suppression sur Table Client
        $stmt = $PDO->prepare("DELETE FROM Client WHERE id = :num");
        $stmt->bindValue('num', $_GET['suppr'], PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $PDOAuth->prepare("SELECT id FROM Utilisateur WHERE login = :login");
        $stmt->bindValue('login', $userlog['Login'], PDO::PARAM_STR);
        $stmt->execute();
        $userid = $stmt->fetch();

        //Suppression sur Table Autorisation
        $stmt = $PDOAuth->prepare("DELETE FROM Autorisation WHERE id_utilisateur = :num");
        $stmt->bindValue('num', $userid['id'], PDO::PARAM_INT);
        $stmt->execute();
        //Suppression sur Table Utilisateur
        $stmt = $PDOAuth->prepare("DELETE FROM Utilisateur WHERE id = :num");
        $stmt->bindValue('num', $userid['id'], PDO::PARAM_INT);
        $stmt->execute();

        echo "Suppression de l'utilisateur réussi.";
        
    }catch(PDOException $e){
        echo "ERREUR : ".$e;
    }
}

if(isset($_GET["sendmail"])){
    $stmt = $PDO->prepare("UPDATE Client SET SendDashboard = :mail WHERE id = :user");
    $stmt->bindValue('mail', $_GET['sendmail'], PDO::PARAM_INT);
    $stmt->bindValue('user', $_GET['user'], PDO::PARAM_INT);
    $stmt->execute();
    echo "Modification du statut réussi.";
}