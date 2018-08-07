<?php

// Récupération de la connexion au serveur d'authentification + Serveur check courrant
include('PDO_Authentification.php');
include('PDO.php');

function addUser($PDOAuth, $PDO, $login, $pass, $email, $droit){
    try{
        $stmt = $PDOAuth->prepare("INSERT INTO Utilisateur VALUES(NULL, :login, :password, '', 2)");
        $stmt->bindValue('login', $login, PDO::PARAM_STR);
        $stmt->bindValue('password', $pass, PDO::PARAM_STR);
        $stmt->execute();
        
        $stmt = $PDOAuth->prepare("SELECT id FROM Utilisateur WHERE login = :login AND password = :password");
        $stmt->bindValue('login', $login, PDO::PARAM_STR);
        $stmt->bindValue('password', $pass, PDO::PARAM_STR);
        $stmt->execute();
        $userauth = $stmt->fetch();
        
        $stmt = $PDO->prepare("INSERT INTO Client VALUES(NULL, :login, :email, :droit, 0)");
        $stmt->bindValue('login', $login, PDO::PARAM_STR);
        $stmt->bindValue('email', $email, PDO::PARAM_STR);
        $stmt->bindValue('droit', $droit, PDO::PARAM_STR);
        $stmt->execute();

        $stmt = $PDO->prepare("SELECT MAX(id) AS id FROM Client");
        $stmt->execute();
        $usercurrent = $stmt->fetch();

        $stmt = $PDOAuth->prepare("INSERT INTO Autorisation VALUES(:idUser, (SELECT id FROM `Database` WHERE nom = :nameDatabase), :idUserDb)");
        $stmt->bindValue('idUser', $userauth["id"], PDO::PARAM_INT);
        $stmt->bindValue('idUserDb', $usercurrent["id"], PDO::PARAM_INT);
        $stmt->bindValue('nameDatabase', $_SESSION["Database"], PDO::PARAM_STR);
        $stmt->execute();
        
        return "<p style='color: green;'>Utilisateur créé avec succés !</p>";
        
    }catch(PDOException $e){
        
        return "<p style='color: red;'>Erreur : ".$e."</p>";
        
    }

}