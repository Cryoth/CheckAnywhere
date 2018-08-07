<?php

//Connexion BDD Authentification
$access = parse_ini_file("Modele/BDD/Config/configBDD.ini");

try{
    $PDOAuth = new PDO($access['engine'].':dbname='.$access['name'].';host='.$access['host'], $access['user'], $access['pass']);
    $PDOAuth->query("SET NAMES 'utf8'");
}  
catch(PDOException $e){
    echo "Erreur de connexion : ".$e->getMessage();
}
