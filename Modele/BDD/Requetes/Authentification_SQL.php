<?php
// Récupération de la connexion au serveur d'authentification
include('PDO_Authentification.php');

//Identification
function LogUser($PDOAuth, $login, $password){
    $stmt = $PDOAuth->prepare("SELECT id, id_user_db, id_database, statut FROM Utilisateur, Autorisation WHERE Utilisateur.id = Autorisation.id_utilisateur AND login = :login AND password = :password");
    $stmt->bindValue('login', $login, PDO::PARAM_STR);
    $stmt->bindValue('password', $password, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetch();
}

//Récupère la base de donnée correspondant à l'utilisateur
function getDatabaseName($PDOAuth, $userId){
    $stmt = $PDOAuth->prepare("SELECT id, nom FROM `Database` INNER JOIN Autorisation WHERE Database.id = Autorisation.id_database AND Autorisation.id_utilisateur = :userId");
    $stmt->bindValue('userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch();
}

//Récupère la base de donnée correspondant à l'id
function getDatabaseNameById($PDOAuth, $idDatabase){
    $stmt = $PDOAuth->prepare("SELECT nom FROM `Database` WHERE id = :idDatabase");
    $stmt->bindValue('idDatabase', $idDatabase, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch();
}

//Récupère l'ensemble des bases de données disponibles (accessible uniquement par le super admin)
function getAllDatabase($PDOAuth){
	$stmt = $PDOAuth->prepare("SELECT id, nom FROM `Database`");
    $stmt->execute();

    return $stmt->fetchAll();
}

function getUserIdCurrentDatabase($PDOAuth, $id, $db){
    $stmt = $PDOAuth->prepare("SELECT id_user_db FROM `Autorisation` WHERE id_utilisateur = :id AND id_database = :db");
    $stmt->bindValue('id', $id, PDO::PARAM_INT);
    $stmt->bindValue('db', $db, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch();
}

?>