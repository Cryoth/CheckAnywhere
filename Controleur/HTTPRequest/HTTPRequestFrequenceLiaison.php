<?php

session_start();

if(isset($_SESSION["Database"])){

    //Connexion BDD
    $access = parse_ini_file("../../Modele/BDD/Config/configBDD.ini");

    try{
        $PDO = new PDO($access[engine].':dbname='.$_SESSION["Database"].';host='.$access[host], $access[user], $access[pass]);
        $PDO->query("SET NAMES 'utf8'");
    }
    catch(PDOException $e){
        echo "Erreur de connexion : ".$e->getMessage();
    }

}

// MISE A JOUR DRAG & DROP
if(isset($_POST["updateSortable"])){
    if($_POST["depart"] == "list-non-used"){
        echo linkFrequence($PDO, $_POST["item"], $_POST["destination"]);
    }else{
        echo unlinkFrequence($PDO, $_POST["item"], $_POST["depart"]);
    }
}

// AFFICHAGE
if(isset($_POST["refresh"])){

    $listGrpData = getAllDonnees($PDO, $_POST["provenance"]);
    
?>

<div class="col-md-12 spacing-top-row" >
    <div class="col-md-7 text-center">
        <h2>Données et Fréquences liées</h2>
    </div>
    <div class="col-md-4 col-md-offset-1 text-center">
        <h2>Fréquences non liés</h2>
    </div>
    <div class="col-md-8">    
        <div class='col-md-4'>
            <ul class="nav nav-pills nav-stacked nav-pills-scroll" role="tablist">
                <?php foreach($listGrpData as $key => $row){
                if($key == 0){ ?>
                <li role="presentation" class="active"><a href="#materiel<?php echo $row["id"]; ?>" aria-controls="Frequence" role="tab" data-toggle="tab"><?php echo $row["Nom"]; ?></a></li>
                <?php }else{ ?>
                <li role="presentation" ><a href="#materiel<?php echo $row["id"]; ?>" aria-controls="Frequence" role="tab" data-toggle="tab"><?php echo $row["Nom"]; ?></a></li>
                <?php }} ?>
            </ul>
        </div>
        <div class='col-md-6'>
            <div class="tab-content prod-tab-content">
                <?php foreach($listGrpData as $key => $row){ ?>
                <div role="tabpanel" class="tab-pane <?php if($key == 0){ echo "active";} ?> row" id="materiel<?php echo $row["id"] ?>">
                    <ul id="<?php echo $row["id"] ?>" class="sortable-ul">
                        <?php foreach(getUsedDatabyFrequence($PDO, $row["id"], $_POST["provenance"]) as $data){ ?>
                            <li id="<?php echo $data["id"]; ?>"><?php echo $data["Nom"]; ?></li>
                        <?php } ?>
                    </ul>
                </div>
                <?php } ?>
            </div>
        </div>
        <div class="col-md-2 double-arrow">
            <span class="col-md-12 glyphicon glyphicon-arrow-left"></span>
            <span class="col-md-12 glyphicon glyphicon-arrow-right"></span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="tab-content prod-tab-content">
            <div role="tabpanel" class="tab-pane active row">
                <ul id="list-non-used" class="sortable-ul-non-used">
                    <?php foreach(getAllNonUsedFrequence($PDO, $_POST["provenance"]) as $data){ ?>
                        <li id="<?php echo $data["id"] ?>"><?php echo $data["Nom"]; ?></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php 
}

// FONCTIONS BDD
function unlinkFrequence($PDO, $denominateur, $nominateur){
    $stmt = $PDO->prepare("DELETE FROM Donnee_Liaison WHERE idDenominateur = :denominateur AND idNominateur = :nominateur");
    $stmt->bindValue('denominateur', $denominateur, PDO::PARAM_INT);
    $stmt->bindValue('nominateur', $nominateur, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

function linkFrequence($PDO, $denominateur, $nominateur){
    $stmt = $PDO->prepare("INSERT INTO Donnee_Liaison VALUES(:denominateur, :nominateur)");
    $stmt->bindValue('denominateur', $denominateur, PDO::PARAM_INT);
    $stmt->bindValue('nominateur', $nominateur, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

function getAllDonnees($PDO, $provenance){
    $stmt = $PDO->prepare("SELECT * FROM Donnees WHERE Frequence = 0 AND Provenance_id = :provenance");
    $stmt->bindValue('provenance', $provenance, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

function getAllNonUsedFrequence($PDO, $provenance){
    $stmt = $PDO->prepare("SELECT * FROM Donnees WHERE Frequence = 1 AND Provenance_id = :provenance");
    $stmt->bindValue('provenance', $provenance, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

function getUsedDatabyFrequence($PDO, $id, $provenance){
    $stmt = $PDO->prepare("SELECT * FROM Donnees WHERE Frequence = 1 AND id IN (SELECT idDenominateur FROM Donnee_Liaison WHERE idNominateur = :id) AND Provenance_id = :provenance");
    $stmt->bindValue('id', $id, PDO::PARAM_INT);
    $stmt->bindValue('provenance', $provenance, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

?>