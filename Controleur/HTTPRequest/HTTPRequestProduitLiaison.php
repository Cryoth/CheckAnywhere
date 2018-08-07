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

$listGrpData = getAllGroupeDonnees($PDO);
$listDataUnused = getAllNonUsedData($PDO);

// MISE A JOUR DRAG & DROP
if(isset($_POST["updateSortable"])){
    if($_POST["depart"] == "list-non-used"){
        echo updateToNotNull($PDO, $_POST["item"], $_POST["destination"]);
    }else{
        echo updateToNull($PDO, $_POST["item"]);
    }
}

// AFFICHAGE
if(isset($_POST["refresh"])){

?>

<div class="col-md-12 spacing-top-row" >
    <div class="col-md-7 text-center">
        <h2>Produits et données liées</h2>
    </div>
    <div class="col-md-4 col-md-offset-1 text-center">
        <h2>Données non liées</h2>
    </div>
    <div class="col-md-8">    
        <div class='col-md-4'>
            <ul class="nav nav-pills nav-stacked nav-pills-scroll" role="tablist">
                <?php foreach($listGrpData as $key => $row){
                if($key == 0){ ?>
                <li role="presentation" class="active"><a href="#produit<?php echo $row["id"]; ?>" aria-controls="prod" role="tab" data-toggle="tab"><?php echo $row["Nom"]; ?></a></li>
                <?php }else{ ?>
                <li role="presentation" ><a href="#produit<?php echo $row["id"]; ?>" aria-controls="prod" role="tab" data-toggle="tab"><?php echo $row["Nom"]; ?></a></li>
                <?php }} ?>
            </ul>
        </div>
        <div class='col-md-6'>
            <div class="tab-content prod-tab-content">
                <?php foreach($listGrpData as $key => $row){ ?>
                <div role="tabpanel" class="tab-pane <?php if($key == 0){ echo "active";} ?> row" id="produit<?php echo $row["id"] ?>">
                    <ul id="<?php echo $row["id"] ?>" class="sortable-ul">
                        <?php foreach(getUsedDatabyGrp($PDO, $row["id"]) as $data){ ?>
                            <li id="<?php echo $data["id"]; ?>"><?php echo $data["Nom"]." <small>[".$data["prov"]; ?>]</small></li>
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
                <ul id="list-non-used" class="sortable-ul">
                    <?php foreach(getAllNonUsedData($PDO) as $data){ ?>
                    <li id="<?php echo $data["id"] ?>"><?php echo $data["Nom"]." <small>[".$data["prov"]; ?>]</small></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php 
}

// FONCTIONS BDD
function getAllGroupeDonnees($PDO){
    $stmt = $PDO->prepare("SELECT * FROM GroupeDonnees");
    $stmt->execute();
    
    return $stmt->fetchAll();
}

function getAllNonUsedData($PDO){
    $stmt = $PDO->prepare("SELECT Donnees.*, Provenance.Nom AS prov FROM Donnees, Provenance WHERE Donnees.Provenance_id = Provenance.id AND GroupeDonnees_id IS NULL");
    $stmt->execute();
    
    return $stmt->fetchAll();
}

function getUsedDatabyGrp($PDO, $id){
    $stmt = $PDO->prepare("SELECT Donnees.*, Provenance.Nom AS prov FROM Donnees, Provenance WHERE Donnees.Provenance_id = Provenance.id AND GroupeDonnees_id = :id");
    $stmt->bindValue('id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

function updateToNotNull($PDO, $id, $dest){
    try{
        $stmt = $PDO->prepare("UPDATE Donnees SET GroupeDonnees_id = :dest WHERE id = :id");
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->bindValue('dest', $dest, PDO::PARAM_INT);
        $stmt->execute();
        
        return "OK";
        
    } catch (Exception $ex) {
        return $ex;
    }
}

function updateToNull($PDO, $id){
    try{
        $stmt = $PDO->prepare("UPDATE Donnees SET GroupeDonnees_id = NULL WHERE id = :id");
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return "OK";
        
    } catch (Exception $ex) {
        return $ex;
    }
    
}

?>