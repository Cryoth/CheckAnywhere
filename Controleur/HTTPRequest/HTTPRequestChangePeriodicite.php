<?php 

session_start();

if(isset($_POST["periodicite"]) && isset($_SESSION["Periodicite"])){

	//Connexion BDD
    $access = parse_ini_file("../../Modele/BDD/Config/configBDD.ini");

    try{
        $PDO = new PDO($access['engine'].':dbname='.$_SESSION["Database"].';host='.$access['host'], $access['user'], $access['pass']);
        $PDO->query("SET NAMES 'utf8'");
    }
    catch(PDOException $e){
        echo "Erreur de connexion : ".$e->getMessage();
    }
	
	if($_POST["periodicite"] == "hebdomadaire"){

		$_SESSION["Periodicite"] = 1;
		changePeriodiciteUser($PDO, 1, $_SESSION["Nom"], $_SESSION["Mail"]);

		echo "hebdomadaire";

	}else if($_POST["periodicite"] == "journalier"){

		$_SESSION["Periodicite"] = 0;
		changePeriodiciteUser($PDO, 0, $_SESSION["Nom"], $_SESSION["Mail"]);

		echo "journalier";

	}

}

// FONCTIONS BDD
function changePeriodiciteUser($PDO, $periodicite, $nom, $mail){
    $stmt = $PDO->prepare("UPDATE Client SET Periodicite = :periode WHERE Login = :nom AND Email = :mail");
    $stmt->bindValue('periode', $periodicite, PDO::PARAM_INT);
    $stmt->bindValue('nom', $nom, PDO::PARAM_STR);
    $stmt->bindValue('mail', $mail, PDO::PARAM_STR);
    $stmt->execute();
    
    return $stmt->fetchAll();
}