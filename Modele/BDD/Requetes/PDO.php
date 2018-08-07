<?php

if(isset($_SESSION["Database"])){

    //Connexion BDD Authentification
    $access = parse_ini_file("Modele/BDD/Config/configBDD.ini");

    try{
        $PDO = new PDO($access['engine'].':dbname='.$_SESSION["Database"].';host='.$access['host'], $access['user'], $access['pass']);
        $PDO->query("SET NAMES 'utf8'");
    }  
    catch(PDOException $e){
        echo "Erreur de connexion : ".$e->getMessage();
    }

}