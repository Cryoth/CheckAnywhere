<!DOCTYPE html>
<html>
    
    <?php 
        include('Controleur/c_load.php');
        
        // Page par défaut
        $vue = 'Vue/v_Login.php';

        // Permet la connexion / deconnexion des utilisateurs
        if(isset($_POST["login"], $_POST["password"], $_POST["submitConnexion"])){
            include_once('Controleur/c_Login.php');
        }elseif($_GET["C"] == "deco"  && !isset($_POST["submit_database"])){
            include_once('Controleur/c_deconnexion.php');
        }

        // Si un utilisateur est connecté, permet la répartition des urls
        if(isset($_SESSION["UserId"])){
            if(isset($_SESSION["Autorisation"]) && !isset($_POST["submit_database"])){
                include('Controleur/c_PageSelect.php');
            }else if($_SESSION["Droit"] == 1){
                $vue = 'Vue/v_Select_Database.php';
                include_once('Controleur/c_Select_Database.php');
            }
        }elseif(isset($_GET["changeMyPass"])){
            include_once('Controleur/c_ChangePassword.php');
            $vue = 'Vue/v_ChangePassword.php';
        }

    ?>
    
    <body>
        
        <?php  
            include('Vue/theme/head.php');
            include_once('Vue/theme/header.php');
            include($vue);
            include_once('Vue/theme/footer.php'); 
        ?>
        
        <script src="Vue/js/bootstrap.min.js"></script>
        <script src="Vue/js/bootstrap-switch.js"></script>
        <script src='Vue/js/bootstrap-datepicker.min.js'></script>
        <script src='Vue/js/bootstrap-datepicker.fr.min.js'></script>
        <script src='Vue/js/bootstrap-toggle.min.js'></script>
        <script defer src="https://use.fontawesome.com/releases/v5.0.8/js/solid.js" integrity="sha384-+Ga2s7YBbhOD6nie0DzrZpJes+b2K1xkpKxTFFcx59QmVPaSA8c7pycsNaFwUK6l" crossorigin="anonymous"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.0.8/js/fontawesome.js" integrity="sha384-7ox8Q2yzO/uWircfojVuCQOZl+ZZBg2D2J5nkpLqzH1HY0C1dHlTKIbpRz/LG23c" crossorigin="anonymous"></script>
        <script src='Vue/js/select2.min.js'></script>
        <script src='Vue/js/jq-traitement.js'></script>
        <script src='Vue/js/jq-GraphShow.js'></script>

        <?php  
            if(isset($script)){
               echo $script;
            }
        ?>
        
    </body>
</html>
