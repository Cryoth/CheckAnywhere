<?php

session_start();

if(isset($_SESSION["Database"])){

    //Connexion BDD
    $access = parse_ini_file("../../Modele/BDD/Config/configBDD.ini");

    try{
        $PDO = new PDO($access['engine'].':dbname='.$_SESSION["Database"].';host='.$access['host'], $access['user'], $access['pass']);
        $PDO->query("SET NAMES 'utf8'");
    }
    catch(PDOException $e){
        echo "Erreur de connexion : ".$e->getMessage();
    }

}

if(isset($_POST["value"])){
    
    $identificateur = json_decode($_POST["value"]);
    $roundValue = true;
    $res = array();
    $tab = array();
   
    foreach($identificateur->Valeurs as $valeur){
        if($identificateur->Monetaire == 1){
            if($identificateur->Ratio == 1){
                $data = getDataFrequenceInEuroTraite($PDO, $_POST["time"], $valeur->Id, $valeur->AtelierId);
                $roundValue = false;
            }else{
                $data = getDataInEuroTraite($PDO, $_POST["time"], $valeur->Id, $valeur->AtelierId);
            }
        }else{
            if($identificateur->Ratio == 1){
                $data = getDataFrequenceTraite($PDO, $_POST["time"], $valeur->Id, $valeur->AtelierId);
                $roundValue = false;
            }else{
                $data = getDataTraite($PDO, $_POST["time"], $valeur->Id, $valeur->AtelierId);
            }
        }
        for($i=count($data) - 1; $i >= 0 ; $i--){
            if($data[$i] < 0){
                $res[$i] += 0;
            }else{
                if($_SESSION["Database"] == "ELVIR"){
                    $roundValue = false;
                }
                // Si fréquence alors arrondi à 2 décimals sinon arrondi sans décimal
                if($roundValue == true){
                    $res[$i] += round($data[$i]);
                }else{
                    $res[$i] += round($data[$i], 2);
                }
            }
        }
    }
    foreach($res as $row){
        $final .= $row.", ";
    }
    echo $final;
}

function getDataTraite($PDO, $temps, $prod, $mat){
    for($i=0; $i < $temps; $i++){
        $nombreS = $i;
        $today = date("Y-m-d", strtotime("-".$nombreS." week"));
        if(date("N") == 1){
            $today = date("Y-m-d", strtotime($today) + (3600 * 24));
        }
        $before = date("Y-m-d",strtotime($today) - (3600 * 24 * 6));
        
        if($mat == "tous"){
            $stmt = $PDO->prepare("SELECT SUM(Valeur) AS Qte FROM Valeur, Donnees "
                                . "WHERE Donnees.id = Valeur.Donnees_id "
                                . "AND Date >= '".$before."' "
                                . "AND Date <= '".$today."' "
                                . "AND Periodicite = 2 "
                                . "AND Donnees_id IN (SELECT id FROM Donnees "
                                . "WHERE Nom = (SELECT Nom FROM Donnees WHERE id = :numProd))");
            $stmt->bindValue('numProd', $prod, PDO::PARAM_INT);
        }else{
            $stmt = $PDO->prepare("SELECT SUM(Valeur) AS Qte FROM Valeur, Donnees "
                                . "WHERE Donnees.id = Valeur.Donnees_id "
                                . "AND Periodicite = 2 "
                                . "AND Date >= '".$before."' "
                                . "AND Date <= '".$today."' AND Donnees_id = :numProd");
            $stmt->bindValue('numProd', $prod, PDO::PARAM_INT);
        }
        $stmt->execute();
        $res[$i] = $stmt->fetch()["Qte"];
    }
    return $res;
}

function getDataInEuroTraite($PDO, $temps, $prod, $mat){
    for($i=0; $i < $temps; $i++){
        $nombreS = $i;
        $today = date("Y-m-d", strtotime("-".$nombreS." week"));
        if(date("N") == 1){
            $today = date("Y-m-d", strtotime($today) + (3600 * 24));
        }
        $before = date("Y-m-d",strtotime($today) - (3600 * 24 * 7));
        
        if($mat == "tous"){
            $stmt = $PDO->prepare("SELECT SUM(Valeur * Prix_Defaut) AS Qte FROM Valeur, Donnees, GroupeDonnees, Prix "
                                . "WHERE Donnees.id = Valeur.Donnees_id "
                                . "AND Donnees.GroupeDonnees_id = GroupeDonnees.id "
                                . "AND GroupeDonnees.id = Prix.GroupeDonnees_id "
                                . "AND Date >= '".$before."' "
                                . "AND Date <= '".$today."' "
                                . "AND Periodicite = 2 "
                                . "AND Valeur.Donnees_id IN (SELECT id FROM Donnees WHERE Nom = (SELECT Nom FROM Donnees WHERE id = :numProd)) "
                                . "AND Prix.Date_changement = (SELECT MAX(Date_changement) FROM Prix, Donnees, GroupeDonnees "
                                    . "WHERE Donnees.GroupeDonnees_id = GroupeDonnees.id "
                                    . "AND GroupeDonnees.id = Prix.GroupeDonnees_id "
                                    . "AND Donnees.id = :numProd "
                                    . "AND Date_Changement <= '".$today."')");
            $stmt->bindValue('numProd', $prod, PDO::PARAM_INT);
        }else{
            $stmt = $PDO->prepare("SELECT SUM(Valeur * Prix_Defaut) AS Qte FROM Valeur, Donnees, GroupeDonnees, Prix "
                                . "WHERE Donnees.id = Valeur.Donnees_id "
                                . "AND Donnees.GroupeDonnees_id = GroupeDonnees.id "
                                . "AND GroupeDonnees.id = Prix.GroupeDonnees_id "
                                . "AND Date >= '".$before."' "
                                . "AND Date <= '".$today."' "
                                . "AND Periodicite = 2 "
                                . "AND Valeur.Donnees_id = :numProd "
                                . "AND Prix.Date_changement = (SELECT MAX(Date_changement) FROM Prix, Donnees, GroupeDonnees "
                                    . "WHERE Donnees.GroupeDonnees_id = GroupeDonnees.id "
                                    . "AND GroupeDonnees.id = Prix.GroupeDonnees_id "
                                    . "AND Donnees.id = :numProd "
                                    . "AND Date_Changement <= '".$today."')");
            $stmt->bindValue('numProd', $prod, PDO::PARAM_INT);
        }
        $stmt->execute();
        $res[$i] = $stmt->fetch()["Qte"];
    }
    return $res;
}

function getDataFrequenceTraite($PDO, $temps, $prod, $mat){
    for($i=0; $i < $temps; $i++){
        $nombreS = $i;
        $today = date("Y-m-d", strtotime("-".$nombreS." week"));
        if(date("N") == 1){
            $today = date("Y-m-d", strtotime($today) + (3600 * 24));
        }
        $before = date("Y-m-d",strtotime($today) - (3600 * 24 * 7));
        
        if($mat == "tous"){
            $stmt = $PDO->prepare("SELECT SUM(Valeur) AS Qte FROM Valeur, Donnees "
                                . "WHERE Donnees.id = Valeur.Donnees_id AND Date >= '".$before."' "
                                . "AND Date <= '".$today."' "
                                . "AND Periodicite = 2 "
                                . "AND Donnees_id IN (SELECT id FROM Donnees "
                                . "WHERE Nom = (SELECT Nom FROM Donnees WHERE id = :numProd))");
            $stmt->bindValue('numProd', $prod, PDO::PARAM_INT);
        }else{
            $stmt = $PDO->prepare("SELECT SUM(Valeur) AS Qte FROM Valeur, Donnees "
                                . "WHERE Donnees.id = Valeur.Donnees_id "
                                . "AND Periodicite = 2 "
                                . "AND Date >= '".$before."' "
                                . "AND Date <= '".$today."' AND Donnees_id = :numProd");
            $stmt->bindValue('numProd', $prod, PDO::PARAM_INT);
        }
        $stmt->execute();
        $res[$i] = $stmt->fetch()["Qte"];

        //Recuperation de la fréquence pour calcul
        if($mat == "tous"){
            $stmt = $PDO->prepare("SELECT SUM(Valeur) AS Qte FROM Valeur, Donnees "
                                . "WHERE Donnees.id = Valeur.Donnees_id AND Date >= '".$before."' "
                                . "AND Date <= '".$today."' "
                                . "AND Periodicite = 2 "
                                . "AND Donnees_id IN (SELECT id FROM Donnees "
                                . "WHERE id IN (SELECT idDenominateur FROM Donnee_Liaison "
                                . "WHERE idNominateur IN (SELECT id FROM Donnees "
                                . "WHERE Nom = (SELECT Nom FROM Donnees WHERE id = :numProd))))");
            $stmt->bindValue('numProd', $prod, PDO::PARAM_INT);
        }else{
            $stmt = $PDO->prepare("SELECT SUM(Valeur) AS Qte FROM Valeur, Donnees "
                                . "WHERE Donnees.id = Valeur.Donnees_id AND Date >= '".$before."' "
                                . "AND Date <= '".$today."' "
                                . "AND Periodicite = 2 "
                                . "AND Donnees_id IN (SELECT id FROM Donnees "
                                . "WHERE id IN (SELECT idDenominateur FROM Donnee_Liaison WHERE idNominateur = :numProd))");
            $stmt->bindValue('numProd', $prod, PDO::PARAM_INT);
        }

        $stmt->execute();
        $res[$i] = $res[$i] / $stmt->fetch()["Qte"];
        
    }
    return $res;
}

function getDataFrequenceInEuroTraite($PDO, $temps, $prod, $mat){
    for($i=0; $i < $temps; $i++){
        $nombreS = $i;
        $today = date("Y-m-d", strtotime("-".$nombreS." week"));
        if(date("N") == 1){
            $today = date("Y-m-d", strtotime($today) + (3600 * 24));
        }
        $before = date("Y-m-d",strtotime($today) - (3600 * 24 * 7));

        if($mat == "tous"){
            $stmt = $PDO->prepare("SELECT SUM(Valeur * Prix_Defaut) AS Qte FROM Valeur, Donnees, GroupeDonnees, Prix "
                                . "WHERE Donnees.id = Valeur.Donnees_id "
                                . "AND Donnees.GroupeDonnees_id = GroupeDonnees.id "
                                . "AND GroupeDonnees.id = Prix.GroupeDonnees_id "
                                . "AND Date >= '".$before."' "
                                . "AND Date <= '".$today."' "
                                . "AND Periodicite = 2 "
                                . "AND Valeur.Donnees_id IN (SELECT id FROM Donnees WHERE Nom = (SELECT Nom FROM Donnees WHERE id = :numProd)) "
                                . "AND Prix.Date_changement = (SELECT MAX(Date_changement) FROM Prix, Donnees, GroupeDonnees "
                                    . "WHERE Donnees.GroupeDonnees_id = GroupeDonnees.id "
                                    . "AND GroupeDonnees.id = Prix.GroupeDonnees_id "
                                    . "AND Donnees.id = :numProd "
                                    . "AND Date_Changement <= '".$today."')");
            $stmt->bindValue('numProd', $prod, PDO::PARAM_INT);
        }else{
            $stmt = $PDO->prepare("SELECT SUM(Valeur * Prix_Defaut) AS Qte FROM Valeur, Donnees, GroupeDonnees, Prix "
                                . "WHERE Donnees.id = Valeur.Donnees_id "
                                . "AND Donnees.GroupeDonnees_id = GroupeDonnees.id "
                                . "AND GroupeDonnees.id = Prix.GroupeDonnees_id "
                                . "AND Date >= '".$before."' "
                                . "AND Date <= '".$today."' "
                                . "AND Periodicite = 2 "
                                . "AND Valeur.Donnees_id = :numProd "
                                . "AND Prix.Date_changement = (SELECT MAX(Date_changement) FROM Prix, Donnees, GroupeDonnees "
                                    . "WHERE Donnees.GroupeDonnees_id = GroupeDonnees.id "
                                    . "AND GroupeDonnees.id = Prix.GroupeDonnees_id "
                                    . "AND Donnees.id = :numProd "
                                    . "AND Date_Changement <= '".$today."')");
            $stmt->bindValue('numProd', $prod, PDO::PARAM_INT);
        }
        $stmt->execute();
        $res[$i] = $stmt->fetch()["Qte"];

        //Recuperation de la fréquence pour calcul
        if($mat == "tous"){
            $stmt = $PDO->prepare("SELECT SUM(Valeur) AS Qte FROM Valeur, Donnees"
                                . "WHERE Donnees.id = Valeur.Donnees_id AND Date >= '".$before."' "
                                . "AND Date <= '".$today."' "
                                . "AND Periodicite = 2 "
                                . "AND Donnees_id IN (SELECT id FROM Donnees "
                                . "WHERE id IN (SELECT idDenominateur FROM Donnee_Liaison "
                                . "WHERE idNominateur IN (SELECT id FROM Donnees "
                                . "WHERE Nom = (SELECT Nom FROM Donnees WHERE id = :numProd))))");
            $stmt->bindValue('numProd', $prod, PDO::PARAM_INT);
        }else{
            $stmt = $PDO->prepare("SELECT SUM(Valeur) AS Qte FROM Valeur, Donnees"
                                . "WHERE Donnees.id = Valeur.Donnees_id AND Date >= '".$before."' "
                                . "AND Date <= '".$today."' "
                                . "AND Periodicite = 2 "
                                . "AND Donnees_id IN (SELECT id FROM Donnees "
                                . "WHERE id IN (SELECT idDenominateur FROM Donnee_Liaison WHERE idNominateur = :numProd)");
            $stmt->bindValue('numProd', $prod, PDO::PARAM_INT);
        }

        $stmt->execute();
        $res[$i] = $res[$i] / $stmt->fetch()["Qte"];
        
    }
    return $res;
}