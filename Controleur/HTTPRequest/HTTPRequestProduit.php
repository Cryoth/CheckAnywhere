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

if(isset($_POST["addProd"])){
    echo addNewGroupeDonnee($PDO, $_POST["nom"], $_POST["prix"]);
}

if(isset($_POST["changeProdNom"])){
    echo changeNomProd($PDO, $_POST["id"], $_POST["nom"]);
}

if(isset($_POST["changeProdPrix"])){
    echo changePrixProd($PDO, $_POST["id"], $_POST["prix"]);
}

if(isset($_POST["delProd"])){
    echo deleteProd($PDO, $_POST["id"]);
}

//FONCTIONS BDD

function addNewGroupeDonnee($PDO, $nom, $prix){
    $stmt = $PDO->prepare("INSERT INTO GroupeDonnees VALUES(NULL, :nom, :prix)");
    $stmt->bindValue('nom', $nom, PDO::PARAM_STR);
    $stmt->bindValue('prix', $prix, PDO::PARAM_INT);
    
    try{
        $stmt->execute();
        return "Ajout OK";
    }catch (Exception $ex) {
        return $ex;
    }
}

function changeNomProd($PDO, $id, $nom){
    $stmt = $PDO->prepare("UPDATE GroupeDonnees SET Nom = :nom WHERE id = :id");
    $stmt->bindValue('id', $id, PDO::PARAM_INT);
    $stmt->bindValue('nom', $nom, PDO::PARAM_STR);
    
    try{
        $stmt->execute();
        return "Update OK";
        
    } catch (Exception $ex) {
        return $ex;
    }
}

function changePrixProd($PDO, $id, $prix){
    try{
        //Date du jour
        $date = date("Y-m-d");
        
        //Supprime les doublons de prix sur le même jour
        $stmt = $PDO->prepare("DELETE FROM Prix WHERE GroupeDonnees_id = :id AND Date_changement = :date");
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->bindValue('date', $date, PDO::PARAM_STR);
        $stmt->execute();
        
        //Crée une ligne prix pour garder l'évolution des prix
        $stmt = $PDO->prepare("INSERT INTO Prix VALUES(NULL, :date, :prix, :id)");
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->bindValue('prix', $prix, PDO::PARAM_STR);
        $stmt->bindValue('date', $date, PDO::PARAM_STR);
        $stmt->execute();
        
        //Mise à jour du prix actuel
        $stmt = $PDO->prepare("UPDATE GroupeDonnees SET Prix_Defaut = :prix WHERE id = :id");
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->bindValue('prix', $prix, PDO::PARAM_STR);
        $stmt->execute();
        
        return "Update OK";
        
    } catch (Exception $ex) {
        return $ex;
    }
}

function deleteProd($PDO, $id){
    try{
        $stmt = $PDO->prepare("UPDATE Donnees SET GroupeDonnees_id = 'NULL' WHERE GroupeDonnees_id = :id");
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $PDO->prepare("DELETE FROM Prix WHERE GroupeDonnees_id = :id");
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $PDO->prepare("DELETE FROM GroupeDonnees WHERE id = :id");
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return "Suppr OK";
        
    } catch (Exception $ex){
        return $ex;
    }
}