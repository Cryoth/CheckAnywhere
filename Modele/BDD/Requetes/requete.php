<?php
include('PDO.php');

function getCurrentUserData($PDO, $userId){
    $stmt = $PDO->prepare("SELECT Login, Email, Droit FROM Client WHERE id = :userId");
    $stmt->bindValue('userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch();
}

// Enregistrement Modele
function enregModele($PDO, $nom, $periode, $type, $client, $placement){
    $stmt = $PDO->prepare("INSERT INTO Modele VALUES(NULL, :client, :nom, 0, :type, :periode)");
    $stmt->bindValue('client', $client, PDO::PARAM_INT);
    $stmt->bindValue('nom', $nom, PDO::PARAM_STR);
    $stmt->bindValue('periode', $periode, PDO::PARAM_INT);
    $stmt->bindValue('type', $type, PDO::PARAM_STR);
    $stmt->execute();
    
    $stmt = $PDO->prepare("INSERT INTO Client_Modele VALUES(:idCli, (SELECT MAX(id) FROM Modele), :place, 1)");
    $stmt->bindValue('idCli', $client, PDO::PARAM_INT);
    $stmt->bindValue('place', $placement, PDO::PARAM_INT);
    $stmt->execute();
}

function enregModeleValues($PDO, $nom, $value, $color, $objTitre, $objectif){
    if($objTitre == ""){
        $objTitre = NULL;
    }
    if($objectif == ""){
        $objectif = NULL;
    }
    $tab = explode("_", $value);
    $i = 0;
    foreach($tab as $t){
        $tab2[$i] =  explode("|", $t);
        $i++;
    }
    //Vérifie si les valeurs sont en Euros
    $euro = 0;
    foreach($tab2 as &$t){
        if($t[4] == 1){
            $euro = 1;
        }
        unset($t[4]);
    }
    //Vérifie si les valeurs sont au format Fréquence
    $frequence = 0;
    foreach($tab2 as &$t){
        if($t[5] == 1){
            $frequence = 1;
        }
        unset($t[5]);
    }
    //Serialize du tableau pour envoie vers la BDD
    $valeurTab = serialize($tab2);
    try{
        $stmt = $PDO->prepare("INSERT INTO Identificateur_Modele Values(NULL, :nom, :valeur, :euro, :frequence, :color, :objectif, :objectifTitre, (SELECT MAX(id) FROM Modele))");
        $stmt->bindValue('nom', $nom, PDO::PARAM_STR);
        $stmt->bindValue('valeur', $valeurTab, PDO::PARAM_STR);
        $stmt->bindValue('euro', $euro, PDO::PARAM_INT);
        $stmt->bindValue('frequence', $frequence, PDO::PARAM_INT);
        $stmt->bindValue('color', $color, PDO::PARAM_STR);
        $stmt->bindValue('objectif', $objectif, PDO::PARAM_INT);
        $stmt->bindValue('objectifTitre', $objTitre, PDO::PARAM_STR);
        $stmt->execute();
    }catch (PDOException $e){
        echo $e;
    }
}

//Récupération données pour listes filtre

function getNomProduit($PDO){
    $stmt = $PDO->prepare("SELECT Nom FROM Donnees WHERE Visible = 1");
    $stmt->execute();
    
    return $stmt->fetchAll();
}

function getNomProduitById($PDO, $idProd){
    $stmt = $PDO->prepare("SELECT Nom FROM Donnees WHERE id = :numProd");
    $stmt->bindValue('numProd', $idProd, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetch()["Nom"];
}

//Requêtes Gestion Produit

function getAllGroupeDonnees($PDO){
    $stmt = $PDO->prepare("SELECT * FROM GroupeDonnees");
    $stmt->execute();
    
    return $stmt->fetchAll();
}

function getAllNonUsedData($PDO){
    $stmt = $PDO->prepare("SELECT * FROM Donnees WHERE GroupeDonnees_id IS NULL");
    $stmt->execute();
    
    return $stmt->fetchAll();
}


function getAllDonnees($PDO){
    $stmt = $PDO->prepare("SELECT Donnees.*, Provenance.Nom As Provenance FROM Donnees, Provenance WHERE Provenance.id = Donnees.Provenance_id AND Frequence = 0");
    $stmt->execute();
    
    return $stmt->fetchAll();
}

function getlistGroupeDonnees($PDO){
    $stmt = $PDO->prepare("SELECT * FROM GroupeDonnees");
    $stmt->execute();
    
    return $stmt->fetchAll();
}

function getAllProduitValue($PDO){
    $stmt = $PDO->prepare("SELECT Donnees.*, Prix.date FROM Produit, Prix WHERE Prix.idProduit = Produit.id AND PrixCourant <> 'NULL' OR NomSpeClient <> 'NULL' GROUP BY id");
    $stmt->execute();
    
    return $stmt->fetchAll();
}

function getPrixProdValue($PDO, $idProd){
    $stmt = $PDO->prepare("SELECT * FROM Prix INNER JOIN (SELECT idProd_Value AS idMax, MAX(date) AS MaxDate FROM Prix WHERE idProd_Value = :idProd GROUP BY idProd_Value) groupMax ON Prix.idProd_Value = groupMax.idMax AND groupMax.MaxDate = Prix.date ");
    $stmt->bindValue('idProd', $idProd, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetch();
}

function addNewLiaisonGroupe($PDO, $donnee, $groupe){
    $stmt = $PDO->prepare("UPDATE Donnees SET GroupeDonnees_id = :groupe WHERE id = :donnee");
    $stmt->bindValue('donnee', $donnee, PDO::PARAM_INT);
    $stmt->bindValue('groupe', $groupe, PDO::PARAM_INT);
    $stmt->execute();
}

function addNewProduitValue($PDO, $nomProd, $prixProd){
    $date = date("Y-m-d");
    $stmt = $PDO->prepare("INSERT INTO GroupeDonnees VALUES(NULL, :nom, :prix)");
    $stmt->bindValue('nom', $nomProd, PDO::PARAM_INT);
    $stmt->bindValue('prix', $prixProd, PDO::PARAM_INT);
    $stmt->execute();
    
    $stmt = $PDO->prepare("INSERT INTO Prix VALUES(NULL, :date, :prix, (SELECT MAX(id) FROM GroupeDonnees))");
    $stmt->bindValue('prix', $prixProd, PDO::PARAM_INT);
    $stmt->bindValue('date', $date, PDO::PARAM_STR);
    $stmt->execute();
}

function getModeleByID($PDO, $userId){
    $stmt = $PDO->prepare("SELECT Modele.*, Client.Login, Client_Modele.*, Client_Modele.place FROM Modele, Client, Client_Modele WHERE Client.id = Modele.idCreateur AND Client_Modele.idModele = Modele.id AND Client_Modele.idClient = :idClient");
    $stmt->bindValue('idClient', $userId, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

function getDonneeModeleById($PDO, $numMod){
    $stmt = $PDO->prepare("SELECT * FROM Identificateur_Modele WHERE Modele_id = :numMod");
    $stmt->bindValue('numMod', $numMod, PDO::PARAM_STR);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

function getMaterielNom($PDO, $idMateriel){
    $stmt = $PDO->prepare("SELECT Nom FROM Provenance WHERE id = :materiel");
    $stmt->bindValue('materiel', $idMateriel, PDO::PARAM_INT);
    $stmt->execute();
    $res = $stmt->fetch();

    return $res["Nom"];
}

function getNomMateriel($PDO, $idMat){
    $stmt = $PDO->prepare("SELECT Nom FROM Provenance WHERE id = :mat");
    $stmt->bindValue('mat', $idMat, PDO::PARAM_INT);
    $stmt->execute();
    $res = $stmt->fetch();

    return $res["Nom"];
}

function CountModele($PDO, $userId){
    $stmt = $PDO->prepare("SELECT COUNT(Modele.id) AS compte FROM Modele, Client_Modele WHERE Client_Modele.idModele = Modele.id AND Client_Modele.idClient = :userId AND Client_Modele.Actif = 1");
    $stmt->bindValue('userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $res = $stmt->fetch();
    
    return $res["compte"];
}

function getGraphInfo($PDO, $userId){
    $stmt = $PDO->prepare("SELECT * FROM Modele, Client_Modele WHERE Modele.id = Client_Modele.idModele AND Client_Modele.idClient = :userId AND Client_Modele.Actif = 1 ORDER BY Client_Modele.Place ");
    $stmt->bindValue('userId', $userId, PDO::PARAM_STR);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

function getLegendeTitre($PDO, $idProd, $idMateriel){
    $stmt = $PDO->prepare("SELECT Nom FROM Donnees WHERE Donnees.id = :num");
    $stmt->bindValue('num', $idProd, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetch()["Nom"];
}

function getAllUsers($PDO){
    $stmt = $PDO->prepare("SELECT * FROM Client");
    $stmt->execute();
    
    return $stmt->fetchAll();
}

function getUser($PDO, $id){
    $stmt = $PDO->prepare("SELECT * FROM Client WHERE id = :id");
    $stmt->bindValue('id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetch();
}

function getUserPass($PDO, $id){
    $stmt = $PDO->prepare("SELECT Password FROM Client WHERE id = :id");
    $stmt->bindValue('id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $res = $stmt->fetch();
    return $res["Password"];
}

function setNewPassword($PDO, $pass, $id){
    $stmt = $PDO->prepare("UPDATE Client SET Password = :pass WHERE id = :id");
    $stmt->bindValue('id', $id, PDO::PARAM_INT);
    $stmt->bindValue('pass', $pass, PDO::PARAM_STR);
    $stmt->execute();
}

function setNewPasswordbyCode($PDO, $pass, $code){
    $stmt = $PDO->prepare("UPDATE Client SET Password = :pass WHERE ResetPass = :code");
    $stmt->bindValue('code', $code, PDO::PARAM_STR);
    $stmt->bindValue('pass', $pass, PDO::PARAM_STR);
    $stmt->execute();
}

function setNewMail($PDO, $mail, $id){
    $stmt = $PDO->prepare("UPDATE Client SET Email = :mail WHERE id = :id");
    $stmt->bindValue('id', $id, PDO::PARAM_INT);
    $stmt->bindValue('mail', $mail, PDO::PARAM_STR);
    $stmt->execute();
}

function getMail($PDO, $login){
    $stmt = $PDO->prepare("SELECT Email FROM Client WHERE Login = :login");
    $stmt->bindValue('login', $login, PDO::PARAM_STR);
    $stmt->execute();
    $res = $stmt->fetch();
    return $res["Email"];
}

function setResetCode($PDO, $code, $mail){
    $stmt = $PDO->prepare("UPDATE Client SET ResetPass = :code WHERE Email = :mail");
    $stmt->bindValue('mail', $mail, PDO::PARAM_STR);
    $stmt->bindValue('code', $code, PDO::PARAM_STR);
    $stmt->execute();
}

function checkCodePass($PDO, $code){
    $stmt = $PDO->prepare("SELECT id FROM Client WHERE ResetPass = :code");
    $stmt->bindValue('code', $code, PDO::PARAM_STR);
    $stmt->execute();
    $res = $stmt->fetch();
    return $res["id"];
}

function resetCodeToNull($PDO, $code){
    $stmt = $PDO->prepare("UPDATE Client SET ResetPass = NULL WHERE ResetPass = :code");
    $stmt->bindValue('code', $code, PDO::PARAM_STR);
    $stmt->execute();
}

// Retourne la liste des matériels et leurs id
function getMaterialListedByAtelier($PDO, $atelier){
    $stmt = $PDO->prepare("SELECT * FROM Provenance WHERE id_atelier = :atelier");
    $stmt->bindValue('atelier', $atelier, PDO::PARAM_INT);
    $stmt->execute();
    $res = $stmt->fetchall();
    return $res;
}

//Récupération du nombre de visuel à afficher
function getNumberOfActifModele($PDO){
    $stmt = $PDO->prepare("SELECT id FROM Modele");
    $stmt->execute();
    $res = $stmt->fetchAll();
    
    return $res;
}

function checkIfModeleExist($PDO, $idClient, $idModele){
    try{
        $stmt = $PDO->prepare("INSERT IGNORE INTO Client_Modele VALUES(:client, :modele, 1, 0, 0)");
        $stmt->bindValue('client', $idClient, PDO::PARAM_INT);
        $stmt->bindValue('modele', $idModele, PDO::PARAM_INT);
        $stmt->execute();
    }catch (PDOException $e){
        echo $e;
    }
}

function getAllGraphActifFromUser($PDO, $userId){
    $stmt = $PDO->prepare("SELECT Modele.* FROM Modele, Client_Modele WHERE Modele.id = Client_Modele.idModele AND Client_Modele.idClient = :userId AND Client_Modele.Actif = 1 ORDER BY Client_Modele.Place ");
    $stmt->bindValue('userId', $userId, PDO::PARAM_STR);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

function getAllIndicateursFromGraph($PDO, $modeleId){
    $stmt = $PDO->prepare("SELECT * FROM Identificateur_Modele WHERE Modele_id = :modeleId");
    $stmt->bindValue('modeleId', $modeleId, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

// Liste Matériel
function getAtelier($PDO){
    $stmt = $PDO->prepare("SELECT * FROM Atelier");
    $stmt->execute();
    $res = $stmt->fetchall();
    return $res;
}

// Liste Fréquences
function getAllFrequence($PDO){
    $stmt = $PDO->prepare("SELECT Donnees.*, Provenance.Nom AS Provenance FROM Donnees, Provenance WHERE Donnees.Provenance_id = Provenance.id AND Frequence = 1 ORDER BY Provenance.Nom");
    $stmt->execute();
    $res = $stmt->fetchall();
    return $res;
}

// Liste Provenances
function getAllProvenance($PDO){
    $stmt = $PDO->prepare("SELECT * FROM Provenance");
    $stmt->execute();
    $res = $stmt->fetchall();
    return $res;
}

// Vérifie l'existance d'un commentaire et renvoie un booléen
function checkIfCommentExist($PDO, $identificateur, $date){
    $stmt = $PDO->prepare("SELECT EXISTS (SELECT * FROM Commentaire WHERE id_Identificateur = :id AND Date = :date)");
    $stmt->bindValue('id', $identificateur, PDO::PARAM_STR);
    $stmt->bindValue('date', $date, PDO::PARAM_STR);
    $stmt->execute();
    $res = $stmt->fetch()[0];
    
    if($res == 0){
        return false;
    }else{
        return true;
    }
}

// Ajout d'un commentaire sur graphique
function addNewCommentaire($PDO, $identificateur, $date, $commentaire){
    if(!checkIfCommentExist($PDO, $identificateur, $date)){
        $stmt = $PDO->prepare("INSERT INTO Commentaire VALUES(NULL, :date, :commentaire, :id)");
        $stmt->bindValue('id', $identificateur, PDO::PARAM_INT);
        $stmt->bindValue('date', $date, PDO::PARAM_STR);
        $stmt->bindValue('commentaire', $commentaire, PDO::PARAM_STR);
        $stmt->execute();
    }else{
        $stmt = $PDO->prepare("UPDATE Commentaire SET Commentaire = :commentaire WHERE Date = :date AND id_Identificateur = :id)");
        $stmt->bindValue('id', $identificateur, PDO::PARAM_INT);
        $stmt->bindValue('date', $date, PDO::PARAM_STR);
        $stmt->bindValue('commentaire', $commentaire, PDO::PARAM_STR);
        $stmt->execute();
    }
}

// Suppression d'un commentaire
function deleteCommentaire($PDO, $identificateur, $date){
    $stmt = $PDO->prepare("DELETE FROM Commentaire WHERE id_Identificateur = :id AND Date = :date");
    $stmt->bindValue('id', $identificateur, PDO::PARAM_INT);
    $stmt->bindValue('date', $date, PDO::PARAM_STR);
    $stmt->execute();
}

function getAllModele($PDO, $user){
    $stmt = $PDO->prepare("SELECT Modele.*, Mail FROM Modele, Client_Modele WHERE Modele.id = Client_Modele.idModele AND Client_Modele.idClient = :user");
    $stmt->bindValue('user', $user, PDO::PARAM_INT);
    $stmt->execute();
    $res = $stmt->fetchall();
    
    return $res;
}

function getAllModeleWithCreator($PDO, $user){
    $stmt = $PDO->prepare("SELECT Modele.*, Client_Modele.Actif FROM Modele, Client_Modele WHERE Modele.id = Client_Modele.idModele AND Client_Modele.idClient = :user ORDER BY Place");
    $stmt->bindValue('user', $user, PDO::PARAM_INT);
    $stmt->execute();
    $res = $stmt->fetchall();
    
    return $res;
}


function checkIfSendDashboard($PDO, $user){
    $stmt = $PDO->prepare("SELECT SendDashboard FROM Client WHERE id = :user");
    $stmt->bindValue('user', $user, PDO::PARAM_INT);
    $stmt->execute();
    $res = $stmt->fetchall();
    
    return $res[0];
}

function updateModeleForAllUser($PDO, $nom, $createur, $type){
    $users = getAllUsers($PDO);
    
    $stmt = $PDO->prepare("SELECT id FROM Modele WHERE Nom = :nom AND FormeGraph = :type AND idCreateur = :createur");
    $stmt->bindValue('createur', $createur, PDO::PARAM_STR);
    $stmt->bindValue('nom', $nom, PDO::PARAM_STR);
    $stmt->bindValue('type', $type, PDO::PARAM_STR);
    $stmt->execute();
    $modele = $stmt->fetchall()[0]["id"];
    
    foreach($users as $user){
        checkIfModeleExist($PDO, $user["id"], $modele);
    }
}

function getNomCreateurById($PDO, $id){
    $stmt = $PDO->prepare("SELECT Login FROM Client WHERE id = :id");
    $stmt->bindValue('id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $res = $stmt->fetch();
    
    return $res["Login"];
}