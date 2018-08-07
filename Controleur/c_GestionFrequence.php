<?php

include_once('Modele/BDD/Requetes/requete.php');

$listFrequences = getAllFrequence($PDO);
$listProvenance = getAllProvenance($PDO);