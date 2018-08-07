<?php

include_once('Modele/BDD/Requetes/requete.php');

$listDonnees = getAllDonnees($PDO);
$listProvenance = getAllProvenance($PDO);