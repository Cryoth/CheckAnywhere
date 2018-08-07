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

if(isset($_POST["actif"])){
    
        $stmt = $PDO->prepare("UPDATE Client_Modele SET actif = 1 WHERE idModele = :num AND idClient = :user");
        $stmt->bindValue('num', $_POST['actif'], PDO::PARAM_INT);
        $stmt->bindValue('user', $_SESSION["UserId"], PDO::PARAM_INT);
        $stmt->execute();

}

if(isset($_POST["inactif"])){

        $stmt = $PDO->prepare("UPDATE Client_Modele SET actif = 0 WHERE idModele = :num AND idClient = :user");
        $stmt->bindValue('num', $_POST['inactif'], PDO::PARAM_INT);
        $stmt->bindValue('user', $_SESSION["UserId"], PDO::PARAM_INT);
        $stmt->execute();

}

//VALIDATION DE LA MODIFICATION D'UN MODELE
if(isset($_POST["modif"])){

    //Mise à jour de la table Modele
    $stmt = $PDO->prepare("UPDATE Modele SET Nom = :nom, FormeGraph = :type, Periode = :periode WHERE id = :mod");
    $stmt->bindValue('nom', $_POST['NomModele'], PDO::PARAM_STR);
    $stmt->bindValue('type', $_POST['FormatModele'], PDO::PARAM_STR);
    $stmt->bindValue('mod', $_POST["modif"], PDO::PARAM_INT);
    $stmt->bindValue('periode', $_POST['PeriodeModele'], PDO::PARAM_INT);
    $stmt->execute();

    for($i=1; $i < 4; $i++){
        if(isset($_POST["NomIndic".$i."Modele"])){
            $stmt = $PDO->prepare("UPDATE Identificateur_Modele SET Nom = :nom, Couleur = :couleur, Objectif_Val = :val, Objectif_Libelle = :obj WHERE id = :id");
            $stmt->bindValue('nom', $_POST["NomIndic".$i."Modele"], PDO::PARAM_STR);
            $stmt->bindValue('couleur', $_POST["Couleur".$i."Modele"], PDO::PARAM_STR);
            $stmt->bindValue('obj', $_POST["NomObj".$i."Modele"], PDO::PARAM_STR);
            $stmt->bindValue('val', $_POST["ValObj".$i."Modele"], PDO::PARAM_INT);
            $stmt->bindValue('id', $_POST["IdIndic".$i."Modele"], PDO::PARAM_INT);
            $stmt->execute();
        }
    }
}

// CHANGEMENT DE L'ORDRE D'AFFICHAGE DES MODELES
if(isset($_POST["reorder"])){
    $arr = json_decode($_POST["reorder"], true);

    foreach ($arr as $key => $modeleId) {
        $stmt = $PDO->prepare("UPDATE Client_Modele SET Place = :place WHERE idModele = :idModele AND idClient = :idUser");
        $stmt->bindValue('idModele', $modeleId, PDO::PARAM_INT);
        $stmt->bindValue('place', $key, PDO::PARAM_INT);
        $stmt->bindValue('idUser', $_SESSION["UserId"], PDO::PARAM_INT);
        $stmt->execute();
    }
}

if(isset($_GET["T"])){

if($_GET["T"] == "Actif"){
    $stmt = $PDO->prepare("SELECT Modele.*, (SELECT Login FROM Client WHERE id = Modele.idCreateur) As Login, Client_Modele.* FROM Modele, Client_Modele WHERE Client_Modele.idModele = Modele.id AND Client_Modele.idClient = :num GROUP BY Modele.id ORDER BY Client_Modele.Actif ".$_GET['Ordre']);
    $stmt->execute();
    $mod = $stmt->fetchAll();
}elseif($_GET["T"] == "Nom Modèle") {
    $stmt = $PDO->prepare("SELECT Modele.*, (SELECT Login FROM Client WHERE id = Modele.idCreateur) As Login, Client_Modele.* FROM Modele, Client_Modele WHERE Client_Modele.idModele = Modele.id AND Client_Modele.idClient = :num GROUP BY Modele.id ORDER BY Nom ".$_GET['Ordre']);
    $stmt->execute();
    $mod = $stmt->fetchAll();
}elseif($_GET["T"] == "Forme"){
    $stmt = $PDO->prepare("SELECT Modele.*, (SELECT Login FROM Client WHERE id = Modele.idCreateur) As Login, Client_Modele.* FROM Modele, Client_Modele WHERE Client_Modele.idModele = Modele.id AND Client_Modele.idClient = :num GROUP BY Modele.id ORDER BY FormeGraph ".$_GET['Ordre']);
    $stmt->execute();
    $mod = $stmt->fetchAll();
}elseif($_GET["T"] == "Temps"){
    $stmt = $PDO->prepare("SELECT Modele.*, (SELECT Login FROM Client WHERE id = Modele.idCreateur) As Login, Client_Modele.* FROM Modele, Client_Modele WHERE Client_Modele.idModele = Modele.id AND Client_Modele.idClient = :num GROUP BY Modele.id ORDER BY Periode ".$_GET['Ordre']);
    $stmt->execute();
    $mod = $stmt->fetchAll();
}elseif($_GET["T"] == "Créateur"){
    $stmt = $PDO->prepare("SELECT Modele.*, (SELECT Login FROM Client WHERE id = Modele.idCreateur) As Login, Client_Modele.* FROM Modele, Client_Modele WHERE Client_Modele.idModele = Modele.id AND Client_Modele.idClient = :num GROUP BY Modele.id ORDER BY idCreateur ".$_GET['Ordre']);
    $stmt->execute();
    $mod = $stmt->fetchAll();
}elseif($_GET["T"] == "Place"){
    $stmt = $PDO->prepare("SELECT Modele.*, (SELECT Login FROM Client WHERE id = Modele.idCreateur) As Login, Client_Modele.* FROM Modele, Client_Modele WHERE Client_Modele.idModele = Modele.id AND Client_Modele.idClient = :num GROUP BY Modele.id ORDER BY Place ".$_GET['Ordre']);
    $stmt->execute();
    $mod = $stmt->fetchAll();
}elseif($_GET["T"] == "ID"){
    $stmt = $PDO->prepare("SELECT Modele.*, (SELECT Login FROM Client WHERE id = Modele.idCreateur) As Login, Client_Modele.* FROM Modele, Client_Modele WHERE Client_Modele.idModele = Modele.id AND Client_Modele.idClient = :num GROUP BY Modele.id ORDER BY Modele.id ".$_GET['Ordre']);
}
    $stmt->bindValue('num', $_GET["user"], PDO::PARAM_INT);
    $stmt->execute();
    $mod = $stmt->fetchAll();
    echo afficheListeModele($PDO, $mod);
}

if(isset($_POST["s"])){
    
    $stmt = $PDO->prepare("SELECT idClient FROM Modele WHERE id = :num");
    $stmt->bindValue('num', $_POST['s'], PDO::PARAM_INT);
    $stmt->execute();
    $val = $stmt -> fetch();
        
    $stmt = $PDO->prepare("DELETE FROM Identificateur_Modele WHERE Modele_id = :num");
    $stmt->bindValue('num', $_POST['s'], PDO::PARAM_INT);
    $stmt->execute();

    $stmt = $PDO->prepare("DELETE FROM Client_Modele WHERE idModele = :num");
    $stmt->bindValue('num', $_POST['s'], PDO::PARAM_INT);
    $stmt->execute();

    $stmt = $PDO->prepare("DELETE FROM Modele WHERE id = :num");
    $stmt->bindValue('num', $_POST['s'], PDO::PARAM_INT);
    $stmt->execute();

    echo "Supprimer";
}

//FONCTIONS POUR LA PAGE DE GESTION DES MODELES
function afficheListeModele($PDO, $mod){
    // Partie supérieur du tableau
    $Modeles = "<div class='container'>
                    <div id='gestionModele' class='row text-center'>
                        <div class='panel panel-default'>
                            <div class='panel-heading'>
                                <div class='panel-title row'>
                                    <div class='trie col-md-1'>ID<span class='glyphicon glyphicon-triangle-bottom'></span></div>
                                    <div class='trie col-md-1'>Actif<span class='glyphicon glyphicon-triangle-bottom'></span></div>
                                    <div class='trie col-md-1'>Place<span class='glyphicon glyphicon-triangle-bottom'></span></div>
                                    <div class='trie col-md-2'>Créateur<span class='glyphicon glyphicon-triangle-bottom'></span></div>
                                    <div class='trie col-md-2'>Nom Modèle<span class='glyphicon glyphicon-triangle-bottom'></span></div>
                                    <div class='trie col-md-1'>Forme<span class='glyphicon glyphicon-triangle-bottom'></span></div>
                                    <div class='trie col-md-1'>Temps<span class='glyphicon glyphicon-triangle-bottom'></span></div>
                                </div>
                            </div>
                            <div class='panel-body text-center'>";
    foreach($mod as $row){
        $Modeles .= "<div class='ModeleListe list-group-item row'>";
        $Modeles .= "<div class='col-md-1'>".$row["id"]."</div>";
        if($row["Actif"] == 1){
            $Modeles .= "<div class='col-md-1'><input value='".$row["id"]."' class='checkActif form-control' type='checkbox' checked /></div>";
        }
        else{
            $Modeles .= "<div class='col-md-1'><input value='".$row["id"]."' class='checkActif form-control' type='checkbox' /></div>";
        }
        $Modeles .= "<div class='col-md-1'><input type='number' min='1' class='form-control SelectPlace col-xs-1' value='".$row["Place"]."'/></div>";
        $Modeles .= "<div class='col-md-2'><input type='hidden' class='CreatorNumber' value='".$row['idCreateur']."'/>".$row["Login"]."</div>";
        $Modeles .= "<div class='col-md-2'>".$row["Nom"]."</div>";
        $Modeles .= "<div class='col-md-1'>".$row["FormeGraph"]."</div>";
        $Modeles .= "<div class='col-md-1'>".$row["Periode"]." Sem.</div>";
        $Modeles .= "<div class='col-md-3'><button class='modifModele btn btn-primary' value='".$row["id"]."'>Modifier</button>";
        $Modeles .= "<button class='delModele btn btn-warning' value='".$row["id"]."'>Supprimer</button></div></div>";
        $Modeles .= "<div class='ModeledataListe' style='display: none;'>"
                  . "<div class='list-group-item row'>"
                  . "<div class='col-md-2'>Donnée</div>"
                  . "<div class='col-md-2'>Nom Produit</div>"
                  . "<div class='col-md-2'>Unité</div>"
                  . "<div class='col-md-1'>Euros</div>"
                  . "<div class='col-md-1'>Ratio</div>"
                  . "<div class='col-md-2'>Materiel</div>"
                  . "<div class='col-md-2'>Couleur</div>"
                  . "</div>";
        $i = 0;
        $tab = getdonneeModeleById($PDO, $row["id"]);
        foreach($tab as $row2){
            $i++;
            $valeurTab = unserialize($row2["Valeurs"]);
            foreach($valeurTab as $row3){
                if($row2["Monetaire"] == 1){
                    $euros = "Oui";
                }else{
                    $euros = "Non";
                }
                if($row2["Ratio"]){
                    $frequence = "Oui";
                }else{
                    $frequence = "Non";
                }
                if($row3[0] != "tous"){
                    $row3[0] = getMaterielNom($PDO, $row3[0]);
                }
                $row3[2] = getNomProduitById($PDO, $row3[2]);
                $Modeles .= "<div class='row list-group-item'>"
                        . "<div class='col-md-2'>N°".$i."</div>"
                        . "<div class='col-md-2'>".$row3[2]."</div>"
                        . "<div class='col-md-2'>".$row3[3]."</div>"
                        . "<div class='col-md-1'>".$euros."</div>"
                        . "<div class='col-md-1'>".$frequence."</div>"
                        . "<div class='col-md-2'>".$row3[0]."</div>"
                        . "<div class='col-md-2'><div style=' width: 20px; height: 20px; border: 1px solid black; border-radius: 5px; display: inline-block; background-color: ".$row2['Couleur']."; margin-top: 5px;'></div>"
                        . "</div></div>";
            }
        }
        $Modeles .= "</div><div class='modifPlace'></div>";
    }
    $Modeles .= "<br>";

    return $Modeles;
}

function getDonneeModeleById($PDO, $numMod){
    $stmt = $PDO->prepare("SELECT * FROM Identificateur_Modele WHERE Modele_id = :numMod");
    $stmt->bindValue('numMod', $numMod, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

function getNomProduitById($PDO, $idProd){
    $stmt = $PDO->prepare("SELECT Nom FROM Donnees WHERE id = :numProd");
    $stmt->bindValue('numProd', $idProd, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetch()["Nom"];
}

function getMaterielNom($PDO, $idMateriel){
    $stmt = $PDO->prepare("SELECT Nom FROM Provenance WHERE id = :materiel");
    $stmt->bindValue('materiel', $idMateriel, PDO::PARAM_INT);
    $stmt->execute();
    $res = $stmt->fetch();

    return $res["Nom"];
}