<?php

$config = parse_ini_file("Config/configBDD.ini");

try{
    $PDO = new PDO($config[engine].':dbname='.$_POST["client"].';host='.$config[host], $config[user], $config[pass]);
    $PDO->query("SET NAMES 'utf8'");
    
    if($_POST["token"] == $config[token_check]){
        $post_array = $_POST;
        unset($post_array["token"]);
        unset($post_array["client"]);
        $message = callInsertHTTP($PDO, $post_array);
        $arr = array('message' => "==== Donnees bien recu ====", "Liste donnees :" => $message);
        header('HTTP/1.1 201 Created');
        echo json_encode($arr);
    }else{
        echo "Erreur token";
        $arr = array('message' => "Mauvais Token");
        header('HTTP/1.1 500 Fail');
        echo json_encode($arr);
    }
}  
catch(PDOException $e){
    echo "Erreur de connexion : ".$e->getMessage();
}



//Appel de la procédure Ajout_Valeur_HTTP
function callInsertHTTP($PDO, $arrayData){
    $dateData = $arrayData["date"];
    unset($arrayData["date"]);
    $periodicite = 1;
    foreach($arrayData as $nameData => $valueData){
        $nameData = str_replace('<point/>', '.',$nameData);
        $message .= $nameData." ".$valueData." ";
        $stmt = $PDO->prepare("CALL insert_valeur(:val, :name, :date, :periode);");
        $stmt->bindValue("val", $valueData, PDO::PARAM_STR);
        $stmt->bindValue("name", $nameData, PDO::PARAM_STR);
        $stmt->bindValue("date", $dateData, PDO::PARAM_STR);
        $stmt->bindValue("periode", $periodicite, PDO::PARAM_INT);
        $stmt->execute();
        
        if(date("N", strtotime($dateData)) != 1){
            $dateDataAdded = date("Y-m-d", strtotime("previous monday", strtotime($dateData)));
        }
        $stmt = $PDO->prepare("SELECT SUM(Valeur) AS cumul FROM Valeur WHERE Date >= :dateDebut AND Date <= :dateFin AND Periodicite = 1 AND Donnees_id IN (SELECT id FROM Donnees WHERE Code = :nom)");
        $stmt->bindValue('nom', $nameData, PDO::PARAM_STR);
        $stmt->bindValue('dateDebut', $dateDataAdded, PDO::PARAM_STR);
        $stmt->bindValue('dateFin', date("Y-m-d", strtotime("next sunday", strtotime($dateDataAdded))), PDO::PARAM_STR);
        $stmt->execute();
        $sommeData = $stmt->fetch()["cumul"];
        
        // Synchronise la valeur hebdo à la nouvelle valeur journalière (somme des val journalières = hebdo)
        $stmt = $PDO->prepare("CALL insert_valeur(:val, :nom, :date, 2);");
        $stmt->bindValue('val', $sommeData, PDO::PARAM_STR);
        $stmt->bindValue('nom', $nameData, PDO::PARAM_STR);
        $stmt->bindValue('date', $dateDataAdded, PDO::PARAM_STR);
        $stmt->execute();
    }
    return $message;
}