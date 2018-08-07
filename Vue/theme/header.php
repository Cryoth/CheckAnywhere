<header>
    <div class="container-fullwidth header-background">
        <div class="row text-left">
            <a href="?C=accueil" class="col-lg-2">
                <img class="logo-size" src="Vue/images/logo/logo.gif" alt="IAASERVICES" >
            </a>
            <?php if(isset($_SESSION["Autorisation"])){ ?>
            <div class="col-lg-1 vcenter"><?php echo $_SESSION["Database"] ?></div>
            <div id='utilisateur' class="col-lg-4 col-lg-offset-4 vcenter text-right"><span class='glyphicon glyphicon-user'></span>
            <?php echo "Bienvenue ".$_SESSION["Nom"]." | <a href='?C=user'>Mon compte</a> | <a href='?C=deco'>Deconnexion</a>"; ?>
            </div>
        </div>
    </div>
    <div class="container-fullwidth">
        <div class="row">
            <div class="navbar navbar-default navbar-static-top">
                <div class="container-fluid">
                    <ul class="nav navbar-nav text-center">
                        <li class="active">
                            <a href="?C=accueil">Tableau de bord</a>
                        </li>
                        <li>
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle">Atelier<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <?php foreach(getAtelier($PDO) as $row){ ?>
                                
                                    <li><a href="?C=manualAdd<?php echo '&atelier='.$row["id"]; ?>"><?php echo $row["Nom"]; ?></a></li>
                                
                                <?php } ?>
                                
                            </ul>
                        </li>
                        <li>
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle">Configuration<b class="caret"></b></a>
                            <ul class="dropdown-menu">

                                <li><a href="?C=creation">Création Indicateurs</a></li>

                                <li><a href="?C=modif">Gestion Indicateurs</a></li>                             

                                <?php if($_SESSION["Autorisation"] < 3){ ?>

                                <li class="divider"></li>
                                
                                <li><a href="?C=prod">Gestion Produits</a></li>
                                
                                <li><a href="?C=atelier">Gestion Atelier</a></li>
                                
                                <?php } ?>
                                
                            </ul>
                        </li>
                        <?php if($_SESSION["Autorisation"] == 1){ ?>

                        <li>
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle">Administration<b class="caret"></b></a>
                            <ul class="dropdown-menu">

                                <li><a href="?C=admin_user">Utilisateurs</a></li>

                                <li><a href="?C=frequence">Gestion Fréquences</a></li>
                                
                                <li><a href="?C=data">Gestion Données</a></li>
                                
                            </ul>
                        </li>
                        
                        <?php } ?>
                        
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <br>
        
    <?php } ?>
</header>