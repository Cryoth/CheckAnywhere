<?php

include_once('/var/www/html/Controleur/PHPMailer/class.phpmailer.php');
include_once('/var/www/html/Controleur/PHPMailer/class.smtp.php');

date_default_timezone_set('Europe/Paris');

$date = strtotime("now");
$week = date("W", $date);
$dir = '/var/www/html/Vue/images/graphiques/';

//Connexion BDD Authentification
$access = parse_ini_file("/var/www/html/Modele/BDD/Config/configBDD.ini");

$databases = array("INGREDIA", "ELVIR", "STLOUP", "STSAVIOL");

foreach($databases as $database){

    try{
        $PDO = new PDO($access['engine'].':dbname='.$database.';host='.$access['host'], $access['user'], $access['pass']);
        $PDO->query("SET NAMES 'utf8'");
    }  
    catch(PDOException $e){
        echo "Erreur de connexion : ".$e->getMessage();
    }

    $clients = getClientWithMailOK($PDO);

    foreach($clients as $client){
        
        $content = '<h1>Tableau de bord Checkanywhere '.$database.' : Données de la semaine ' . $week . '</h1>';
        $content .= '<p>Tableau de bord de la semaine ' . $week . ' débutant le lundi ' . date("d/m/y", $date) . '</p>';

        $modeles = getModeleToSendByClient($PDO, $client["id"]);
        
        //mailer config
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->IsHTML(true);
        $mail->CharSet = "UTF-8";
        $mail->SMTPAuth = true;
        $mail->Host = "smtp.auth.orange-business.com";
        $mail->Port = 587;
        $mail->Username = "cipanywhere@iaaservices.com";
        $mail->Password = "C1pAnywh3r3";

        $mail->SetFrom("cipanywhere@iaaservices.com", "Checkanywhere");

        $mail->Subject = 'Checkanywhere '.$database.' : Tableau de bord';
        
        foreach($modeles as $modele){
            if(file_exists($dir.$database."_".$modele["idModele"].".png")){
                $content .= "<img src = 'cid:".$modele["idModele"]."'/>";
                $mail->AddEmbeddedImage($dir.$database."_".$modele["idModele"].".png", $modele["idModele"]);
            }
        }

        $mail->MsgHTML($content);

        $mail->AddAddress($client['Email'], $client['Email']);

        //envoi du mail
        $mail->Send();
    }

}


function getClientWithMailOK($PDO){
    $stmt = $PDO->prepare("SELECT Client.id, Client.Email FROM Client WHERE SendDashboard = 1");
    $stmt->execute();
    $res = $stmt->fetchall();
    
    return $res;
}

function getModeleToSendByClient($PDO, $client){
    $stmt = $PDO->prepare("SELECT idModele FROM Client_Modele WHERE idClient = :client AND Actif = 1");
    $stmt->bindValue('client', $client, PDO::PARAM_INT);
    $stmt->execute();
    $res = $stmt->fetchall();
    
    return $res;
}

?>