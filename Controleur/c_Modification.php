<?php

include_once('Modele/BDD/Requetes/requete.php');

$modeles = getAllModeleWithCreator($PDO, $_SESSION['UserId']);

//Variable des session pour requêtes http
echo "<script>var user = ".$_SESSION['UserId']."</script>";
echo "<script>var admin = ".$_SESSION["Autorisation"]."</script>";

$script = "<script src='Vue/js/manage_modele.js?test=1323551'></script>";
