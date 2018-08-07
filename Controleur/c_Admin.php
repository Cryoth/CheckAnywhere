<?php

include_once('Modele/BDD/Requetes/requete_admin.php');
include_once('Modele/BDD/Requetes/requete.php');
require('Controleur/PHPMailer/PHPMailerAutoload.php');


//Création de l'utilisateur
if(isset($_POST["submitUser"], $_POST["login"], $_POST["password"], $_POST["email"])){
    
    $result = addUser($PDOAuth, $PDO, $_POST["login"], sha1($_POST["password"]), $_POST["email"], $_POST["droit"]); 
    echo "<script>console.log('".$controleur."');</script>";
    if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
        
        try{
            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->Host = 'smtp.audiv.orange-business.com';
            $mail->SMTPAudiv = true;
            $mail->Port = 587;
            $mail->CharSet = "UTF-8";

            $mail->Username   = "cipanywhere@iaaservices.com";
            $mail->Password   = "C1pAnywh3r3";

            //Expéditeur
            $mail->SetFrom('cipanywhere@iaaservices.com', 'Check Anywhere');
            
            // Destinataire
            $mail->AddAddress($_POST["email"], $_POST["login"]);
            
            // Objet
            $mail->Subject = 'Votre compte Check Anywhere';

            // Votre message
            $mail->MsgHTML("Madame, Monsieur,<br><br>Veuillez trouver dans ce courrier vos identifiants CheckAnywhere ainsi que votre mot de passe temporaire :<br><br>Login : ".$_POST["login"]."<br>Mot de passe : ".$_POST["password"]."<br><br>Pour la sécurité de votre compte, rappelez-vous de ne pas communiquer vos identifiants.<br>Cordialement,<br>IAASERVICES");

            // Envoi du mail
            $mail->Send();
        } catch (phpmailerException $e) {
            echo $e->errorMessage();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

$users = getAllUsers($PDO);
$listShow = "<div id='showUser' class='text-center'>"
        . "<div class='col-md-1'>Numéro</div>"
        . "<div class='col-md-3'>Nom</div>"
        . "<div class='col-md-3'>Email</div>"
        . "<div class='col-md-2'>Droit</div>"
        . "</div></div>";

foreach($users as $u){
    $listShow .= "<div class='row text-center'>"
            . "<div class='col-md-1'>".$u['id']."</div>"
            . "<div class='col-md-3'><input class='form-control' type='text' name='modifLog' disabled='disabled' value='".$u['Login']."'/></div>"
            . "<div class='col-md-3'><input class='form-control' type='text' name='modifMail' disabled='disabled' value='".$u['Email']."'/></div>";
    if($u["Droit"] == 1){
        $listShow .= "<div class='col-md-2'><select class='form-control' name='modifDroit' disabled='disabled'>"
                . "<option value='1' selected='selected'>Admin</option>"
                . "<option value='2'>Gestionnaire</option>"
                . "<option value='3'>Utilisateur</option>"
                . "</select></div>";
    }elseif($u["Droit"] == 2){
        $listShow .= "<div class='col-md-2'><select class='form-control' name='modifDroit' disabled='disabled'>"
                . "<option value='1'>Admin</option>"
                . "<option value='2' selected='selected'>Gestionnaire</option>"
                . "<option value='3'>Utilisateur</option>"
                . "</select></div>";
    }else{
        $listShow .= "<div class='col-md-2'><select class='form-control' name='modifDroit' disabled='disabled'>"
                . "<option value='1'>Admin</option>"
                . "<option value='2'>Gestionnaire</option>"
                . "<option value='3' selected='selected'>Utilisateur</option>"
                . "</select></div>";
    }
    $listShow .= "<div class='col-md-3'><button class='modUser btn btn-primary'>Modifier</button>"
            . "<button class='deleteUser btn btn-warning'>Supprimer</button></div></div>";
}

$listShow .= "<br></div></div>";