<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');

// Appel de PHPExcel
include('PHPExcel/PHPExcel.php');
include('PHPExcel/PHPExcel/Writer/Excel2007.php');

$engine = "mysql"; 
$host = "localhost"; 
$database = "BaseSrv1"; 
$user = "root"; 
$passwd = "My\$QL_\$3rv3r";

// Connexion PDO à la BDD
try{
    $PDO = new PDO($engine.':dbname='.$database.';host='.$host, $user, $passwd);
    $PDO->query("SET NAMES 'utf8'");
}  
catch(PDOException $e){
    echo "Erreur de connexion :".$e->getMessage();
}


$workbook = new PHPExcel;

$sheet = $workbook->getActiveSheet();

if(isset($_POST["exportExcelCurrent"])){
    $stmt = $PDO->prepare("SELECT Nom FROM Provenance WHERE id = :id");
    $stmt->bindValue('id', $_POST["exportExcelCurrent"], PDO::PARAM_INT);
    $stmt->execute();
    $listNomMat = $stmt->fetchAll();
}else{
    $stmt = $PDO->prepare("SELECT Nom FROM Provenance");
    $stmt->execute();
    $listNomMat = $stmt->fetchAll();
}

$actualDate = date("Y-m-d");
$countLine = 1;

foreach($listNomMat as $row1){
    $countCol = 0;
    $countLine++;
    $dateOK = false;
    $sheet->setCellValueByColumnAndRow($countCol, $countLine, $row1[0]);
    
    $stmt = $PDO->prepare("SELECT Nom, Unite, id FROM Donnees WHERE Provenance_id = (SELECT id FROM Provenance WHERE Nom = :mat) AND Visible = 1");
    $stmt->bindValue('mat', $row1[0], PDO::PARAM_STR);
    $stmt->execute();
    $listNomData = $stmt->fetchAll();
    
    $sheet->setCellValueByColumnAndRow($countCol + 1, $countLine, "Nom Donnée");
    $sheet->setCellValueByColumnAndRow($countCol + 2, $countLine, "Unité");
    
    foreach($listNomData as $row2){
        $countCol = 1;
        $sheet->setCellValueByColumnAndRow($countCol, $countLine + 1, $row2["Nom"]);
        $countCol++;
        $sheet->setCellValueByColumnAndRow($countCol, $countLine + 1, $row2["Unite"]);
        for($i=52; $i >= 0; $i--){
            $countCol++;
            $stmt = $PDO->prepare("SELECT Valeur, Date FROM Valeur WHERE Donnees_id IN (SELECT id FROM Donnees WHERE Nom = :nom AND id = :id) AND Date >= :dateDebut AND Date <= :dateFin AND Periodicite = 2");
            $stmt->bindValue('dateDebut', date("Y-m-d", strtotime("- ".($i + 1)." week", strtotime($actualDate))), PDO::PARAM_STR);
            $stmt->bindValue('dateFin', date("Y-m-d", strtotime("- ".$i." week", strtotime($actualDate))), PDO::PARAM_STR);
            $stmt->bindValue('nom', $row2["Nom"], PDO::PARAM_STR);
            $stmt->bindValue('id', $row2["id"], PDO::PARAM_STR);
            $stmt->execute();
            $dataHebdo = $stmt->fetch();
            if($dateOK == false){
                $sheet->setCellValueByColumnAndRow($countCol, $countLine, "Sem ".date("W", strtotime("- ".$i." week", strtotime($actualDate))));
            }
            if($dataHebdo["Valeur"] != ""){
                $sheet->setCellValueByColumnAndRow($countCol, ($countLine + 1), $dataHebdo["Valeur"]);
            }
        }
        $countLine++;
        $dateOK = true;
    }    
    
    $countLine++;
}

$writer = new PHPExcel_Writer_Excel2007($workbook);
if(isset($_POST["exportExcelCurrent"])){
    header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition:inline;filename=Checkanywhere_'.$listNomMat[0]["Nom"].'.xlsx ');
}else{
    header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition:inline;filename=Checkanywhere.xlsx ');
}
ob_end_clean();
$writer->save("php://output");
exit();
?>