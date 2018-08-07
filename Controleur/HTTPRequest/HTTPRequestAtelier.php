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

if(isset($_POST["addAtelier"])){
    echo addNewAtelier($PDO, $_POST["nom"]);
}

if(isset($_POST["changeAtelierNom"])){
    echo changeNomAtelier($PDO, $_POST["id"], $_POST["nom"]);
}

if(isset($_POST["delAtelier"])){
    echo deleteAtelier($PDO, $_POST["id"]);
}

if(isset($_POST["regenerate"])){
    echo regenerateListAtelier($PDO);
}

//FONCTIONS BDD

function addNewAtelier($PDO, $nom){
    $stmt = $PDO->prepare("INSERT INTO Atelier VALUES(NULL, :nom)");
    $stmt->bindValue('nom', $nom, PDO::PARAM_STR);
    
    try{
        $stmt->execute();
        return "Ajout OK";
    }catch (Exception $ex) {
        return $ex;
    }
}

function changeNomAtelier($PDO, $id, $nom){
    $stmt = $PDO->prepare("UPDATE Atelier SET Nom = :nom WHERE id = :id");
    $stmt->bindValue('id', $id, PDO::PARAM_INT);
    $stmt->bindValue('nom', $nom, PDO::PARAM_STR);
    
    try{
        $stmt->execute();
        return "Update OK";
        
    } catch (Exception $ex) {
        return $ex;
    }
}


function deleteAtelier($PDO, $id){
    try{
        $stmt = $PDO->prepare("UPDATE Provenance SET id_atelier = NULL WHERE id_atelier = :id");
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $PDO->prepare("DELETE FROM Atelier WHERE id = :id");
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return "Suppr OK";
        
    } catch (Exception $ex){
        return $ex;
    }
}

// Liste Matériel
function getAtelier($PDO){
    $stmt = $PDO->prepare("SELECT * FROM Atelier");
    $stmt->execute();
    $res = $stmt->fetchall();
    return $res;
}

function regenerateListAtelier($PDO){
    
    $listAtelier = getAtelier($PDO);
    
    foreach($listAtelier as $row){
    $list .= "<li class='list-group-item col-md-6'>
                <div class='col-md-9'>
                    <input type='textbox' class='form-control text-center change-atelier-nom' value='".$row["Nom"]."' />
                </div>
                <div class='col-md-1'>
                    <button class='btn btn-danger delAtelier' value='".$row["id"]."'>Supprimer</button>
                </div>
            </li>";
    }
    
    return $list;
}