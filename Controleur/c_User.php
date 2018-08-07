<?php
include_once('Modele/BDD/Requetes/requete.php');

if(isset($_POST["submitChangePass"])){
    if(sha1($_POST["pastPassword"]) == getUserPass($PDO, $_SESSION["UserId"])){
        if($_POST["pastPassword"] !=  $_POST["newPassword"]){
            if($_POST["newPassword"] == $_POST["confirmPassword"]){
                setNewPassword($PDO, sha1($_POST["confirmPassword"]), $_SESSION["UserId"]);
                $message = "<p style='color : green'>Le mot de passe a bien été changé.</p>";
            }else{
                $message = "<p style='color : red'>Mot de passe érroné.</p>";
            }
        }else{
            $message = "<p style='color : red'>Le nouveau mot de passe doit être différent de l'ancien.</p>";
        }
    }else{
        $message = "<p style='color : red'>Le mot de passe de confirmation ne correspond pas.</p>";
    }
}

if(isset($_POST["submitChangeMail"])){
    if(filter_var($_POST["newMail"], FILTER_VALIDATE_EMAIL)){
        setNewMail($PDO, $_POST["newMail"], $_SESSION["UserId"]);
        $messageMail = "<p style='color: green'>L'adresse email a bien été changé.</p>";
    }else{
        $messageMail = "<p style='color: red'>L'adresse email donnée n'est pas valide.</p>";
    }
}

$user = getUser($PDO, $_SESSION["UserId"]);
$showUser = "<div class='CenterTab row'>"
        . "<div class='panel panel-default'>"
        . "<div class='panel-heading'>"
        . "<h2 class='panel-title'>Informations Utilisateur</h2>"
        . "</div>"
        . "<div class='panel-body form-group'>"
        . "<div class='input-group col-md-8 col-md-offset-2'>"
        . "<span class='input-group-addon'style='min-width: 100px;'>Nom</span><input class='form-control text-center' value='".$user['Login']."' readonly />"
        . "</div>"
        . "<div class='input-group col-md-8 col-md-offset-2'>"
        . "<span class='input-group-addon' style='min-width: 100px;'>Email</span><input class='form-control text-center' value='".$user['Email']."' readonly />"
        . "</div>"
        . "</div>"
        . "</div>";

$sendDashboard = checkIfSendDashboard($PDO, $_SESSION["UserId"]);