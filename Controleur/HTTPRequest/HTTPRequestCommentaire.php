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

if(isset($_POST["identificateur"])){
    foreach(getAllCommentaireByIndicateur($PDO, $_POST["identificateur"]) as $row){
        $final["Commentaire"] = $row["Commentaire"];
        $final["Date"] = date("Y-n-j", strtotime($row["Date"]));
    }
    echo json_encode($final);
}

function getAllCommentaireByIndicateur($PDO, $id){
    $stmt = $PDO->prepare("SELECT Commentaire, Date FROM Commentaire WHERE id_Identificateur = :identificateur");
    $stmt->bindValue('identificateur', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}