<?php

session_start();

if(isset($_SESSION["Database"])){

    //Connexion BDD
    $access = parse_ini_file("../../Modele/BDD/Config/configBDD.ini");

    try{
        $PDO = new PDO($access[engine].':dbname='.$_SESSION["Database"].';host='.$access[host], $access[user], $access[pass]);
        $PDO->query("SET NAMES 'utf8'");
    }
    catch(PDOException $e){
        echo "Erreur de connexion : ".$e->getMessage();
    }

}

// Ajout d'une fréquence
if(isset($_POST["addFrequence"])){
    echo addNewFrequence($PDO, $_POST["code"], $_POST["nom"], $_POST["adresse"], $_POST["application"], $_POST["provenance"]);
}

// Modification du code de la fréquence choisie
if(isset($_POST["changeFrequenceCode"])){
    echo changeCodeFrequence($PDO, $_POST["id"], $_POST["code"]);
}

// Modification du nom de la fréquence choisie
if(isset($_POST["changeFrequenceNom"])){
    echo changeNomFrequence($PDO, $_POST["id"], $_POST["nom"]);
}

// Modification de l'adresse de la fréquence choisie
if(isset($_POST["changeFrequenceAdresse"])){
    echo changeAdresseFrequence($PDO, $_POST["id"], $_POST["adresse"]);
}

// Modification de l'application de la fréquence choisie
if(isset($_POST["changeFrequenceApplication"])){
    echo changeApplicationFrequence($PDO, $_POST["id"], $_POST["application"]);
}

// Suppression de la fréquence choisie
if(isset($_POST["delFrequence"])){
    echo deleteFrequence($PDO, $_POST["id"]);
}

// FONCTIONS BDD

function addNewFrequence($PDO, $code, $nom, $adresse, $application, $provenance){
    $stmt = $PDO->prepare("INSERT INTO Donnees VALUES(NULL, :code, :nom, :application, NULL, :adresse, 1, 1, NULL, :provenance)");
    $stmt->bindValue('code', $code, PDO::PARAM_STR);
    $stmt->bindValue('nom', $nom, PDO::PARAM_STR);
    $stmt->bindValue('adresse', $adresse, PDO::PARAM_STR);
    $stmt->bindValue('application', $application, PDO::PARAM_STR);
    $stmt->bindValue('provenance', $provenance, PDO::PARAM_INT);
    
    try{
        $stmt->execute();
        return "Ajout OK";
    }catch (Exception $ex) {
        return $ex;
    }
}

function changeCodeFrequence($PDO, $id, $code){
    $stmt = $PDO->prepare("UPDATE Donnees SET Code = :code WHERE id = :id");
    $stmt->bindValue('id', $id, PDO::PARAM_INT);
    $stmt->bindValue('code', $code, PDO::PARAM_STR);
    
    try{
        $stmt->execute();
        return "Update OK";
        
    } catch (Exception $ex) {
        return $ex;
    }
}

function changeNomFrequence($PDO, $id, $nom){
    $stmt = $PDO->prepare("UPDATE Donnees SET Nom = :nom WHERE id = :id");
    $stmt->bindValue('id', $id, PDO::PARAM_INT);
    $stmt->bindValue('nom', $nom, PDO::PARAM_STR);
    
    try{
        $stmt->execute();
        return "Update OK";
        
    } catch (Exception $ex) {
        return $ex;
    }
}

function changeAdresseFrequence($PDO, $id, $adresse){
    $stmt = $PDO->prepare("UPDATE Donnees SET Adresse = :adresse WHERE id = :id");
    $stmt->bindValue('id', $id, PDO::PARAM_INT);
    $stmt->bindValue('adresse', $adresse, PDO::PARAM_STR);
    
    try{
        $stmt->execute();
        return "Update OK";
        
    } catch (Exception $ex) {
        return $ex;
    }
}

function changeApplicationFrequence($PDO, $id, $application){
    $stmt = $PDO->prepare("UPDATE Donnees SET Application = :application WHERE id = :id");
    $stmt->bindValue('id', $id, PDO::PARAM_INT);
    $stmt->bindValue('application', $application, PDO::PARAM_STR);
    
    try{
        $stmt->execute();
        return "Update OK";
        
    } catch (Exception $ex) {
        return $ex;
    }
}

function deleteFrequence($PDO, $id){
    try{
        $stmt = $PDO->prepare("DELETE FROM Donnees WHERE id = :id AND Frequence = 1");
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $stmt = $PDO->prepare("DELETE FROM Donnee_Liaison WHERE idDenominateur = :id");
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return "Suppr OK";
        
    } catch (Exception $ex){
        return $ex;
    }
}