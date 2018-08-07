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
    

if(isset($_GET["a"])){
    
        if($_GET["frequence"] == 1){
            $contrainteF = " AND Donnees.id IN (SELECT idNominateur FROM Donnee_Liaison)";
        }else{
            $contrainteF = "";
        }
        
        if($_GET["euro"] == 1){
            $contrainteE = " AND GroupeDonnees_id IN (SELECT id FROM GroupeDonnees WHERE Prix_Defaut)";
        }else{
            $contrainteE = "";
        }
        
        if($_GET['unite'] == "tous"){
            $contrainteU = "";
        }else{
            $contrainteU = " AND Unite = '".$_GET['unite']."'";
        }
    
        if($_GET['materiel'] == "tous"){
            if($_GET['source'] == "CIP"){
                $stmt = $PDO->prepare("SELECT * FROM Donnees WHERE Application = 'CIP'".$contrainteF.$contrainteE.$contrainteU." AND Frequence = 0 GROUP BY Nom");
            }else{
                $stmt = $PDO->prepare("SELECT * FROM Donnees WHERE Application = 'CHECK'".$contrainteF.$contrainteE.$contrainteU." AND Frequence = 0 GROUP BY Nom");
            }
        }else{
            if($_GET['source'] == "CIP"){
                $stmt = $PDO->prepare("SELECT * FROM Donnees WHERE Application = 'CIP'".$contrainteF.$contrainteE.$contrainteU." AND Frequence = 0 AND Provenance_id = :materiel");
            }else{
                $stmt = $PDO->prepare("SELECT * FROM Donnees WHERE Application = 'CHECK'".$contrainteF.$contrainteE.$contrainteU." AND Frequence = 0 AND Provenance_id = :materiel");
            }
            $stmt->bindValue('materiel', $_GET['materiel'], PDO::PARAM_INT);
        }
        $stmt->execute();
        $tab = $stmt->fetchAll();
        
    foreach($tab as $row){
        echo "<script>$('#valueSelector').append(\"<option value='".$_GET["materiel"]."|".$_GET["source"]."|".$row['id']."|".$_GET["unite"]."|".$_GET["euro"]."|".$_GET["frequence"]."'>".$row['Nom']."</option>\");</script>";
    }
    echo "Filtrer";
}

if(isset($_GET["f"])){
    switch($_GET["f"]){
        case "materiel":

            $stmt = $PDO->prepare("SELECT Provenance.*, Donnees.Application FROM Provenance, Donnees WHERE Provenance.id = Donnees.Provenance_id AND Donnees.Application = :source GROUP BY Provenance.id ORDER BY Provenance.Nom");
            $stmt->bindValue('source', $_GET['source'], PDO::PARAM_STR);
            $stmt->execute();
            $tab = $stmt->fetchAll();
            
            if(count($tab) > 1){
                echo "<option value='tous'>Tous</option>";
            }
            
            foreach($tab as $row){
                echo "<option value='".$row["id"]."'>".$row["Nom"]."</option>";
            }
            
            //Si aucun résultat
            if(empty($tab)){
                echo "<option value='none'>Aucune donnée de type ".$_GET['source']."</option>";
            }
            
            break;
            
        case "unite":
            
            
            if(isset($_GET["frequence"])){
                $contrainteF = " AND Donnees.id IN (SELECT idNominateur FROM Donnee_Liaison)";
            }else{
                $contrainteF = "";
            }
            
            if(isset($_GET["euros"])){
                $contrainteE = " AND GroupeDonnees_id IN (SELECT id FROM GroupeDonnees WHERE Prix_Defaut)";
            }else{
                $contrainteE = "";
            }
                
            if($_GET['materiel'] == "tous"){
                if($_GET['source'] == "CIP"){
                    $stmt = $PDO->prepare("SELECT DISTINCT(Unite) FROM Donnees WHERE Application = 'CIP'".$contrainteE.$contrainteF." AND Frequence = 0 AND Visible = 1");
                }else{
                    $stmt = $PDO->prepare("SELECT DISTINCT(Unite) FROM Donnees WHERE Application = 'CHECK'".$contrainteE.$contrainteF." AND Frequence = 0 AND Visible = 1");
                }
            }else{
                if($_GET['source'] == "CIP"){
                    $stmt = $PDO->prepare("SELECT DISTINCT(Unite) FROM Donnees WHERE Application = 'CIP' AND Provenance_id = :materiel".$contrainteE.$contrainteF." AND Frequence = 0 AND Visible = 1");
                }else{
                    $stmt = $PDO->prepare("SELECT DISTINCT(Unite) FROM Donnees WHERE Application = 'CHECK' AND Provenance_id = :materiel".$contrainteE.$contrainteF." AND Frequence = 0 AND Visible = 1");
                }
                $stmt->bindValue('materiel', $_GET['materiel'], PDO::PARAM_INT);
            }
            $stmt->execute();
            $tab = $stmt->fetchAll();
            
            if(isset($_GET["euros"])){
                if(count($tab) > 1){
                    echo "<option value='tous'>Tous</option>";
                }
            }

            foreach($tab as $row){
                echo "<option value='".$row["Unite"]."'>".$row["Unite"]."</option>";
            }
            
            //Si aucun résultat
            if(empty($tab)){
                echo "<option value='none'>Aucune donnée de type ".$_GET['source']."</option>";
            }
            
            break;
            
        default: break;
    }
}

if(isset($_GET["actif"])){
    
        $stmt = $PDO->prepare("UPDATE Client_Modele SET actif = 1 WHERE idModele = :num AND idClient = :user");
        $stmt->bindValue('num', $_GET['actif'], PDO::PARAM_INT);
        $stmt->bindValue('user', $_GET['user'], PDO::PARAM_INT);
        $stmt->execute();
}

if(isset($_GET["inactif"])){

        $stmt = $PDO->prepare("UPDATE Client_Modele SET actif = 0 WHERE idModele = :num AND idClient = :user");
        $stmt->bindValue('num', $_GET['inactif'], PDO::PARAM_INT);
        $stmt->bindValue('user', $_GET['user'], PDO::PARAM_INT);
        $stmt->execute();
}

if(isset($_GET["P"])){

        $stmt = $PDO->prepare("UPDATE Client_Modele SET place = :place WHERE idModele = :num AND idClient = :user");
        $stmt->bindValue('num', $_GET['modele'], PDO::PARAM_INT);
        $stmt->bindValue('user', $_GET['user'], PDO::PARAM_INT);
        $stmt->bindValue('place', $_GET['P'], PDO::PARAM_INT);
        $stmt->execute();
        
        echo "<input type='number' class='SelectPlace' value='".$_GET['P']."' />";
}
    
?>