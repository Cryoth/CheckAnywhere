<?php

include_once('Modele/BDD/Requetes/requete.php');

//Enregistrement du modèle
if(isset($_POST["enregMod"])){
    if(isset($_POST["SelectMat1"], $_POST["colorD1"])){
        $value = explode(' ', $_POST["SelectMat1"]);
        if(!isset($_POST["objectif1"])){
            $_POST["objectif1"] = "";
        }
        EnregModele($PDO, $_POST["nomGraph"], $_POST["chart"], $_POST["Date"], $_POST["Position"], $_SESSION["UserId"], $_SESSION["Nom"]);
        EnregModeleData($PDO, $value[1], $_POST["colorD1"], $_POST["objectif1"], $value[0]);
    }
    if(isset($_POST["SelectMat2"], $_POST["colorD2"])){
        $value = explode(' ', $_POST["SelectMat2"]);
        if(!isset($_POST["objectif2"])){
            $_POST["objectif2"] = "";
        }
        EnregModeleData($PDO, $value[1], $_POST["colorD2"], $_POST["objectif2"], $value[0]);
    }
    if(isset($_POST["SelectMat3"], $_POST["colorD3"])){
        $value = explode(' ', $_POST["SelectMat3"]);
        if(!isset($_POST["objectif3"])){
            $_POST["objectif3"] = "";
        }
        EnregModeleData($PDO, $value[1], $_POST["colorD3"], $_POST["objectif3"], $value[0]);
    }
}

//Affichage des modèles existants
$mod = getModeleByID($PDO, $_SESSION["UserId"]);
$Modeles = "";
foreach($mod as $row){
    $Modeles .= "<div class='ModeleListe'><div><table><tr>";
    if($row["actif"] == 1){
        $Modeles .= "<td><input value='".$row["idModele"]."' class='checkActif' type='checkbox' checked /></td>";
    }
    else{
        $Modeles .= "<td><input value='".$row["idModele"]."' class='checkActif' type='checkbox' /></td>";
    }
    $Modeles .= "<td><input type ='number' class='SelectPlace' value='".$row["place"]."'/></td>";
    $Modeles .= "<td>".$row["Login"]."</td>";
    $Modeles .= "<td>".$row["Nom"]."</td>";
    $Modeles .= "<td>".$row["TypeGraph"]."</td>";
    $Modeles .= "<td>".$row["Periode"]." Semaines</td>";
    $Modeles .= "<td><button class='modifModele' value='".$row["idModele"]."'>Modifier</button></td>";
    $Modeles .= "<td><button class='delModele' value='".$row["idModele"]."'>Supprimer</button></td></tr></table></div></div>";
    $Modeles .= "<div class='ModeledataListe'><table>";
    $data = getDonneeModeleById($PDO, $row["idModele"]);
    foreach($data as $row2){
        $Modeles .= "<tr><td>Produit : ".$row2["Nom"]."</td><td style='text-align: right;'>Couleur : </td><td><div style=' width: 20px; height: 20px; border: 1px solid black; border-radius: 5px; background-color: ".$row2['Couleur'].";'></div></td><td>Objectif : <input type='text' disabled='disabled' value='".$row2['Objectif']."' /></td></tr>";
    }
    $Modeles .= "</table></div><div class='modifPlace'></div>";
}
$Modeles .= "<br>";

//Variable des session pour requêtes ajax
echo "<script>var user = ".$_SESSION['UserId']."</script>";