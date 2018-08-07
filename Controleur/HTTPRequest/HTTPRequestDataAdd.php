<?php
// ----------------------------------------------------------------------------
//   === Récupération et traitement des données avant leur envoie en Ajax ===
// ----------------------------------------------------------------------------

// Signal au serveur que nous souhaitons des dates en FR
setlocale(LC_TIME, 'fr_FR.utf8');

// Declaration variables à retourner

$dateLegende = "";
$contenuTableau = "";

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

// Adaptation de la date au mode d'affichage
if($_POST["periodicite"] == "on"){
    switch($_POST["decalage"]){
        case 0:
            $_POST["date"] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST["date"])));
            break;
        case 1:
            $_POST["date"] = date("Y-m-d", strtotime("+ 7 day", strtotime(str_replace('/', '-', $_POST["date"]))));
            break;
        case -1:
            $_POST["date"] = date("Y-m-d", strtotime("- 7 day", strtotime(str_replace('/', '-', $_POST["date"]))));
            break;
    }
}else{
    switch($_POST["decalage"]){
        case 0:
            $_POST["date"] = date("Y-m-d", strtotime("previous monday", strtotime(str_replace('/', '-', $_POST["date"]))));
            break;
        case 1:
            $_POST["date"] = date("Y-m-d", strtotime("previous monday", strtotime(str_replace('/', '-', $_POST["date"]))));
            break;
        case -1:
            $_POST["date"] = date("Y-m-d", strtotime("previous monday", strtotime(str_replace('/', '-', $_POST["date"]))));
            break;
    }
}

switch($_POST["action"]){
    case "modifieVal":
        
        // Insert ou update la valeur hebdomadaire
        $stmt = $PDO->prepare("CALL insert_valeur(:val, :nom, :date, :periode);");
        $stmt->bindValue('val', $_POST["value"], PDO::PARAM_STR);
        $stmt->bindValue('nom', $_POST["name"], PDO::PARAM_STR);
        if($_POST["periodicite"] == "on"){
            $periode = 1;
            $stmt->bindValue('date', $_POST["date"], PDO::PARAM_STR);
        }else{
            $periode = 2;
            $stmt->bindValue('date', date("Y-m-d", strtotime("next monday", strtotime($_POST["date"]))), PDO::PARAM_STR);
        }
        $stmt->bindValue('periode', $periode, PDO::PARAM_INT);
        $stmt->execute();
        
        // Adapte les valeurs journalières à la nouvelle valeur hebdo (Hebdo / 7 = chaque valeur journalière)
        if($periode == 2){
            for($i = 0; $i < 7; $i++){
                $stmt = $PDO->prepare("CALL insert_valeur(:val, :nom, :date, 1);");
                $stmt->bindValue('val', ($_POST["value"] / 7), PDO::PARAM_STR);
                $stmt->bindValue('nom', $_POST["name"], PDO::PARAM_STR);
                $stmt->bindValue('date', date("Y-m-d", strtotime("+ ".$i." day", strtotime("next monday", strtotime($_POST["date"])))), PDO::PARAM_STR);
                $stmt->execute();
            }
        }
        
        break;
    
    case "modifieComm":
        
        // Modifie le commentaire
        if(date("N", strtotime($_POST["date"])) != 1){
            $_POST["date"] = date("Y-m-d", strtotime("previous monday", strtotime($_POST["date"])));
        }
        $stmt = $PDO->prepare("UPDATE Valeur SET Commentaire = :val WHERE Date = :date AND Periodicite = 2 AND Donnees_id IN (SELECT id FROM Donnees WHERE Code = :nom)");
        $stmt->bindValue('val', $_POST["value"], PDO::PARAM_STR);
        $stmt->bindValue('nom', $_POST["name"], PDO::PARAM_STR);
        $stmt->bindValue('date', $_POST["date"], PDO::PARAM_STR);
        $stmt->execute();
        break;
        
    case "modifieTotal":
        
        // Modification du total sur l'onglet journalier
        if(date("N", strtotime($_POST["date"])) != 1){
            $_POST["date"] = date("Y-m-d", strtotime("previous monday", strtotime($_POST["date"])));
        }
        $stmt = $PDO->prepare("SELECT SUM(Valeur) AS cumul FROM Valeur WHERE Date >= :dateDebut AND Date <= :dateFin AND Periodicite = 1 AND Donnees_id IN (SELECT id FROM Donnees WHERE Code = :nom)");
        $stmt->bindValue('nom', $_POST["name"], PDO::PARAM_STR);
        $stmt->bindValue('dateDebut', $_POST["date"], PDO::PARAM_STR);
        $stmt->bindValue('dateFin', date("Y-m-d", strtotime("next sunday", strtotime($_POST["date"]))), PDO::PARAM_STR);
        $stmt->execute();
        $sommeData = $stmt->fetch()["cumul"];
        
        // Synchronise la valeur hebdo à la nouvelle valeur journalière (somme des val journalières = hebdo)
        $stmt = $PDO->prepare("CALL insert_valeur(:val, :nom, :date, 2);");
        $stmt->bindValue('val', $sommeData, PDO::PARAM_STR);
        $stmt->bindValue('nom', $_POST["name"], PDO::PARAM_STR);
        $stmt->bindValue('date', $_POST["date"], PDO::PARAM_STR);
        $stmt->execute();
        
        // Renvoie la valeur total de la semaine pour l'afficher
        echo $sommeData;
        
        break;

    case "generate":
    
        // GENERATION DU TABLEAU
        
        if(isset($_POST["atelier"])){
            
            $legendeReady = true;
            
            foreach(getAllProvenanceFromAtelier($PDO, $_POST["atelier"]) as $materiel){
                
                $listNomData = getAllDataFromProvenance($PDO, $materiel["id"]);
                
                $contenuTableau .= "<tr><td colspan='12'>".$materiel["Nom"]."<td><tr>";
                
                // Si tableau journalier
                if($_POST["periodicite"] == "on"){
                    // Récupération des données via SQL
                    $datePremierJour = date("Y-m-d", strtotime("previous monday", strtotime($_POST["date"])));
                    $dateDernierJour = date("Y-m-d", strtotime("+ 6 day", strtotime($datePremierJour)));
                    if($legendeReady){
                        $dateLegende .= "<th class='text-center'>Données</th><th class='text-center'>Unité</th><th class='text-center'>Prov.</th>";
                    }

                    //Préparation du <thead> du tableau et remplissage du tableau
                    $headOK = false;
                    
                    foreach($listNomData as $row){
                        
                        $contenuTableau .= "<tr><td>".$row[0]."</td>";
                        $contenuTableau .= "<td>".$row[2]."</td>";
                        $contenuTableau .= "<td>".$row[3]."</td>";
                        $cumulData = 0;

                        for($i = 0; $i < 7; $i++){
                            $stmt = $PDO->prepare("SELECT Valeur FROM Valeur WHERE Donnees_id IN (SELECT id FROM Donnees WHERE Code = :nom) AND Date >= :dateDebut AND Date <= :dateFin AND Periodicite = 1 ORDER BY Date DESC");
                            $stmt->bindValue('dateDebut', date("Y-m-d", strtotime("+ ".($i)." day", strtotime($datePremierJour))), PDO::PARAM_STR);
                            $stmt->bindValue('dateFin', date("Y-m-d", strtotime("+ ".($i)." day", strtotime($datePremierJour))), PDO::PARAM_STR);
                            $stmt->bindValue('nom', $row[1], PDO::PARAM_STR);
                            $stmt->execute();
                            $dataJourn = $stmt->fetch()["Valeur"];
                            $dateToUse = date("Y-m-d", strtotime("+ ".($i)." day", strtotime($datePremierJour)));
                            if($headOk == false && $legendeReady){
                                $dateLegende .= "<th class='text-center'>".strftime("%a", strtotime($dateToUse))."<br/> ".date("d/m/Y", strtotime($dateToUse))."</th>";
                            }
                            $cumulData += $dataJourn;
                            if($dataJourn == ""){
                                $contenuTableau .= "<td><input type='text' class='addDataInput form-control' name='".$dateToUse."|".$row[1]."' value='0' /></td>";
                            }else{
                                $contenuTableau .= "<td><input type='text' class='addDataInput form-control' name='".$dateToUse."|".$row[1]."' value='".$dataJourn."' /></td>";
                            }
                        }

                        $contenuTableau .= "<td class='containTotal'><input type='text' disabled='disabled' class='addDataInput form-control' value='".$cumulData."' /></td>";

                        $stmt = $PDO->prepare("SELECT Commentaire FROM Valeur WHERE Date >= :dateDebut AND Date <= :dateFin AND Periodicite = 2 AND Donnees_id IN (SELECT id FROM Donnees WHERE Code = :nom)");
                        $stmt->bindValue("dateDebut", $datePremierJour, PDO::PARAM_STR);
                        $stmt->bindValue("dateFin", $datePremierJour, PDO::PARAM_STR);
                        $stmt->bindValue("nom", $row[1], PDO::PARAM_STR);
                        $stmt->execute();
                        $commJourn = $stmt->fetch()["Commentaire"];

                        $contenuTableau .= "<td><input name='".$dateDernierJour."|".$row[1]."' type='text' class='addCommInput form-control' alt='".$commJourn."' title='".$commJourn."' value='".$commJourn."' /></td></tr>";

                        $headOk = true;
                    }

                    if($contenuTableau == ""){
                        $contenuTableau .= "<tr><td>Aucune donnée</td><td>X</td>";
                        if($legendeReady){
                            $dateLegende = "<th class='text-center'>Données</th><th class='text-center'>Unité</th><th class='text-center'>Prov.</th>";
                        }
                        for($i = 0; $i < 9; $i++){
                            if($i < 7){
                                $dateToUse = date("Y-m-d", strtotime("+ ".($i + 1)." day", strtotime($datePremierJour)));
                                if($legendeReady){
                                    $dateLegende .= "<th class='text-center'>".strftime("%a", strtotime($dateToUse))."<br/> ".date("d/m/Y", strtotime($dateToUse))."</th>";
                                }
                            }
                            $contenuTableau .= "<td><input type='text' disabled='disabled' class='addDataInput form-control text-center' value='X'/></td>";
                        }
                        $contenuTableau .= "</tr>";
                    }
                    
                    if($legendeReady){
                        $dateLegende .= "<th>Total Semaine".date("W", strtotime($datePremierJour))."</th><th>Commentaire</th>";
                    }

                // Si tableau hebdo
                }else{

                    $datePremierJour = date("Y-m-d", strtotime("- 9 week", strtotime($_POST["date"])));
                    $dateDernierJour = date("Y-m-d", strtotime($_POST["date"]));
                    if($legendeReady){
                        $dateLegende .= "<th class='text-center'>Données</th><th class='text-center'>Unité</th><th class='text-center'>Prov.</th>";
                    }

                    foreach($listNomData as $row){

                        $contenuTableau .= "<tr><td>".$row[0]."</td>";
                        $contenuTableau .= "<td>".$row[2]."</td>";
                        $contenuTableau .= "<td>".$row[3]."</td>";
                        $cumulData = 0;

                        for($i = 0; $i <= 8; $i++){
                            $stmt = $PDO->prepare("SELECT Valeur FROM Valeur WHERE Donnees_id IN (SELECT id FROM Donnees WHERE Code = :nom) AND Date >= :dateDebut AND Date <= :dateFin AND Periodicite = 2 ORDER BY Date DESC");
                            $stmt->bindValue('dateDebut', date("Y-m-d", strtotime("+ ".($i + 1)." week", strtotime($datePremierJour))), PDO::PARAM_STR);
                            $stmt->bindValue('dateFin', date("Y-m-d", strtotime("+ ".($i + 1)." week", strtotime($datePremierJour))), PDO::PARAM_STR);
                            $stmt->bindValue('nom', $row[1], PDO::PARAM_STR);
                            $stmt->execute();
                            $dataJourn = $stmt->fetch()["Valeur"];
                            $dateToUse = date("Y-m-d", strtotime("+ ".($i + 1)." week", strtotime($datePremierJour)));
                            // Preparation du thead
                            if($headOk == false && $legendeReady){
                                $dateLegende .= "<th class='text-center'>Semaine".date("W", strtotime($dateToUse))."</th>";
                            }
                            if($dataJourn == ""){
                                $contenuTableau .= "<td><input type='text' class='addDataInput form-control' name='".$dateToUse."|".$row[1]."' value='0' /></td>";
                            }else{
                                $contenuTableau .= "<td><input type='text' class='addDataInput form-control' name='".$dateToUse."|".$row[1]."' value='".$dataJourn."' /></td>";
                            }
                        }

                        $headOk = true;
                    }
                    // Tableau par défaut si pas de matériel
                    if($contenuTableau == ""){
                        $contenuTableau .= "<tr><td>Aucune donnée</td>";
                        for($i = 0; $i <= 8; $i++){
                            $dateToUse = date("Y-m-d", strtotime("+ ".($i + 1)." week", strtotime($datePremierJour)));
                            if($legendeReady){
                                $dateLegende .= "<th class='text-center'>Semaine".date("W", strtotime($dateToUse))."</th>";
                            }
                            $contenuTableau .= "<td><input type='text' disabled='disabled' class='addDataInput form-control text-center' value='X'/></td>";
                        }
                        $contenuTableau .= "</tr>";
                    }
                }
                
                $legendeReady = false;
                
            }
        }else{
            
            $listNomData = getAllDataFromProvenance($PDO, $_POST["materiel"]);
            
            // Si tableau journalier
            if($_POST["periodicite"] == "on"){
                // Récupération des données via SQL
                $datePremierJour = date("Y-m-d", strtotime("previous monday", strtotime($_POST["date"])));
                $dateDernierJour = date("Y-m-d", strtotime("+ 6 day", strtotime($datePremierJour)));
                $dateLegende .= "<th class='text-center'>Données</th><th class='text-center'>Unité</th><th class='text-center'>Prov.</th>";

                //Préparation du <thead> du tableau et remplissage du tableau
                $headOK = false;
                foreach($listNomData as $row){

                    $contenuTableau .= "<tr><td>".$row[0]."</td>";
                    $contenuTableau .= "<td>".$row[2]."</td>";
                    $contenuTableau .= "<td>".$row[3]."</td>";
                    $cumulData = 0;

                    for($i = 0; $i < 7; $i++){
                        $stmt = $PDO->prepare("SELECT Valeur FROM Valeur WHERE Donnees_id IN (SELECT id FROM Donnees WHERE Code = :nom) AND Date >= :dateDebut AND Date <= :dateFin AND Periodicite = 1 ORDER BY Date DESC");
                        $stmt->bindValue('dateDebut', date("Y-m-d", strtotime("+ ".($i)." day", strtotime($datePremierJour))), PDO::PARAM_STR);
                        $stmt->bindValue('dateFin', date("Y-m-d", strtotime("+ ".($i)." day", strtotime($datePremierJour))), PDO::PARAM_STR);
                        $stmt->bindValue('nom', $row[1], PDO::PARAM_STR);
                        $stmt->execute();
                        $dataJourn = $stmt->fetch()["Valeur"];
                        $dateToUse = date("Y-m-d", strtotime("+ ".($i)." day", strtotime($datePremierJour)));
                        if($headOk == false){
                            $dateLegende .= "<th class='text-center'>".strftime("%a", strtotime($dateToUse))."<br/> ".date("d/m/Y", strtotime($dateToUse))."</th>";
                        }
                        $cumulData += $dataJourn;
                        if($dataJourn == ""){
                            $contenuTableau .= "<td><input type='text' class='addDataInput form-control' name='".$dateToUse."|".$row[1]."' value='0' /></td>";
                        }else{
                            $contenuTableau .= "<td><input type='text' class='addDataInput form-control' name='".$dateToUse."|".$row[1]."' value='".$dataJourn."' /></td>";
                        }
                    }

                    $contenuTableau .= "<td class='containTotal'><input type='text' disabled='disabled' class='addDataInput form-control' value='".$cumulData."' /></td>";

                    $stmt = $PDO->prepare("SELECT Commentaire FROM Valeur WHERE Date >= :dateDebut AND Date <= :dateFin AND Periodicite = 2 AND Donnees_id IN (SELECT id FROM Donnees WHERE Code = :nom)");
                    $stmt->bindValue("dateDebut", $datePremierJour, PDO::PARAM_STR);
                    $stmt->bindValue("dateFin", $datePremierJour, PDO::PARAM_STR);
                    $stmt->bindValue("nom", $row[1], PDO::PARAM_STR);
                    $stmt->execute();
                    $commJourn = $stmt->fetch()["Commentaire"];

                    $contenuTableau .= "<td><input name='".$dateDernierJour."|".$row[1]."' type='text' class='addCommInput form-control' alt='".$commJourn."' title='".$commJourn."' value='".$commJourn."' /></td></tr>";

                    $headOk = true;
                }

                if($contenuTableau == ""){
                    $contenuTableau .= "<tr><td>Aucune donnée</td><td>X</td>";
                    $dateLegende = "<th class='text-center'>Données</th><th class='text-center'>Unité</th><th class='text-center'>Prov.</th>";
                    for($i = 0; $i < 9; $i++){
                        if($i < 7){
                            $dateToUse = date("Y-m-d", strtotime("+ ".($i + 1)." day", strtotime($datePremierJour)));
                            $dateLegende .= "<th class='text-center'>".strftime("%a", strtotime($dateToUse))."<br/> ".date("d/m/Y", strtotime($dateToUse))."</th>";
                        }
                        $contenuTableau .= "<td><input type='text' disabled='disabled' class='addDataInput form-control text-center' value='X'/></td>";
                    }
                    $contenuTableau .= "</tr>";
                }

                $dateLegende .= "<th>Total Semaine".date("W", strtotime($datePremierJour))."</th><th>Commentaire</th>";

            // Si tableau hebdo
            }else{

                $datePremierJour = date("Y-m-d", strtotime("- 9 week", strtotime($_POST["date"])));
                $dateDernierJour = date("Y-m-d", strtotime($_POST["date"]));
                $dateLegende .= "<th class='text-center'>Données</th><th class='text-center'>Unité</th><th class='text-center'>Prov.</th>";

                foreach($listNomData as $row){

                    $contenuTableau .= "<tr><td>".$row[0]."</td>";
                    $contenuTableau .= "<td>".$row[2]."</td>";
                    $contenuTableau .= "<td>".$row[3]."</td>";
                    $cumulData = 0;

                    for($i = 0; $i <= 8; $i++){
                        $stmt = $PDO->prepare("SELECT Valeur FROM Valeur WHERE Donnees_id IN (SELECT id FROM Donnees WHERE Code = :nom) AND Date >= :dateDebut AND Date <= :dateFin AND Periodicite = 2 ORDER BY Date DESC");
                        $stmt->bindValue('dateDebut', date("Y-m-d", strtotime("+ ".($i + 1)." week", strtotime($datePremierJour))), PDO::PARAM_STR);
                        $stmt->bindValue('dateFin', date("Y-m-d", strtotime("+ ".($i + 1)." week", strtotime($datePremierJour))), PDO::PARAM_STR);
                        $stmt->bindValue('nom', $row[1], PDO::PARAM_STR);
                        $stmt->execute();
                        $dataJourn = $stmt->fetch()["Valeur"];
                        $dateToUse = date("Y-m-d", strtotime("+ ".($i + 1)." week", strtotime($datePremierJour)));
                        // Preparation du thead
                        if($headOk == false){
                            $dateLegende .= "<th class='text-center'>Semaine".date("W", strtotime($dateToUse))."</th>";
                        }
                        if($dataJourn == ""){
                            $contenuTableau .= "<td><input type='text' class='addDataInput form-control' name='".$dateToUse."|".$row[1]."' value='0' /></td>";
                        }else{
                            $contenuTableau .= "<td><input type='text' class='addDataInput form-control' name='".$dateToUse."|".$row[1]."' value='".$dataJourn."' /></td>";
                        }
                    }

                    $headOk = true;
                }
                // Tableau par défaut si pas de matériel
                if($contenuTableau == ""){
                    $contenuTableau .= "<tr><td>Aucune donnée</td>";
                    for($i = 0; $i <= 8; $i++){
                        $dateToUse = date("Y-m-d", strtotime("+ ".($i + 1)." week", strtotime($datePremierJour)));
                        $dateLegende .= "<th class='text-center'>Semaine".date("W", strtotime($dateToUse))."</th>";
                        $contenuTableau .= "<td><input type='text' disabled='disabled' class='addDataInput form-control text-center' value='X'/></td>";
                    }
                    $contenuTableau .= "</tr>";
                }
            }
        }
?>

<table class="table table-hover text-center">
    <thead class="row">
        <tr>
            <?php echo $dateLegende; ?>
        </tr>
    </thead>
    <tbody id="listValuetoAdd" class="text-center">
        <?php echo $contenuTableau; ?>
    </tbody>
</table>
<?php break; }

// Recupere toutes les données pour préparation du tableau
function getAllDataFromProvenance($PDO, $materiel){
    
    $stmt = $PDO->prepare("SELECT Nom, Code, Unite, Application FROM Donnees WHERE Provenance_id = :mat AND Visible = 1");
    $stmt->bindValue('mat', $materiel, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

// Recupere toutes les données pour préparation du tableau
function getAllProvenanceFromAtelier($PDO, $atelier){
    
    $stmt = $PDO->prepare("SELECT id, Nom FROM Provenance WHERE id_atelier = :atelier");
    $stmt->bindValue('atelier', $atelier, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

?>