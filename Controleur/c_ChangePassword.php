<?php
include_once('Modele/BDD/Requetes/requete.php');
require('Controleur/PHPMailer/PHPMailerAutoload.php');

if(checkCodePass($PDO, $_GET["changeMyPass"]) == ""){
    $valRand1 = rand(0, 100);
    $valRand2 = rand(0, 100);
    if(isset($_POST["Valider"])){
        if(isset($_POST["soluce"], $_POST["reponse"], $_POST["login"])){
            if($_POST["soluce"] == $_POST["reponse"]){
                $email = getMail($PDO, $_POST["login"]);
                $hashCode = sha1("IAASERVICE".$email.strtotime(date("Y-m-d-s")));
                setResetCode($PDO, $hashCode, $email);
                $link = "http://178.170.68.39/srv1/?changeMyPass=".$hashCode."";
                try{
                        $mail = new PHPMailer();
                        $mail->IsSMTP();
                        $mail->Host = 'smtp.auth.orange-business.com';
                        $mail->SMTPAuth = true;
                        $mail->Port = 587;
                        $mail->CharSet = "UTF-8";

                        $mail->Username   = "cipanywhere@iaaservices.com";
                        $mail->Password   = "C1pAnywh3r3";

                        //Expéditeur
                        $mail->SetFrom('cipanywhere@iaaservices.com', 'CheckAnywhere');
                        // Destinataire
                        $mail->AddAddress($email, $_POST["login"]);
                        // Objet
                        $mail->Subject = 'Demande de nouveau mot de passe CheckAnywhere';

                        // Votre message
                        $mail->MsgHTML("Madame, Monsieur,<br><br>Veuillez trouver ci-dessous le lien vous permettant de réinitialiser votre mot de passe:<br><a href='".$link."'>Cliquez ici</a><br>Si vous n'êtes pas l'auteur de cette demande veuillez ne pas tenir compte de ce message.<br><br>Cordialement,<br>IAASERVICES");

                        // Envoi du mail
                        $mail->Send();

                        echo "<script type='text/javascript'>alert('Un mail de réinitialisation de votre mot de passe vous sera remis à l\'adresse de votre compte CheckAnywhere d\'ici quelques instants...');</script>";
                    } catch (phpmailerException $e) {
                        echo $e->errorMessage();
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
            }else{
                $error = "<p>Votre réponse n'est pas correct !</p>";
            }
        }else{
            $error = "<p>Vous n'avez pas remplie tous les champs nécéssaires !</p>";
        }
    }
    $affiche = "<h3>Identification</h3>
        <table>
            <tr><td>Votre login :</td><td><input type='text' name='login' placeholder='Login'/></td></tr>
            <tr><td>".$valRand1." + ".$valRand2." = </td><td><input type='hidden' name='soluce' value='". ($valRand1 + $valRand2) ."'/><input type='text' name='reponse' placeholder='Résultat' /></td></tr>
        </table>";
    if(isset($error)){
        $affiche .= "<div id='errorCreation'>".$error."</div>";
    }
    $affiche .= "<input type='submit' name='Valider' /><br>";
}else{
    if(isset($_POST["password"], $_POST["confirm"], $_POST["Valider"])){
        if($_POST["password"] == $_POST["confirm"]){
            setNewPasswordbyCode($PDO, sha1($_POST["password"]), $_POST["code"]);
            resetCodeToNull($PDO, $_POST["code"]);
        }
    }
    
    $affiche = "<h3>Nouveau mot de passe</h3>
        <table>
            <tr><td>Mot de passe :</td><td><input type='password' name='password' placeholder='Mot de passe'/></td></tr>
            <tr><td>Confirmation :</td><td><input type='password' name='confirm' placeholder='Mot de passe'/><input type='hidden' name='code' value='".$_GET["changeMyPass"]."'/></td></tr>
        </table>";
        $affiche .= "<input type='submit' name='Valider' /><br>";
}