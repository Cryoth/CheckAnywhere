<?php

include_once('Modele/BDD/Requetes/requete.php');

// Récupération de la liste des provenances

$listGrpData = getAllGroupeDonnees($PDO);
$listDataUnused = getAllNonUsedData($PDO);
