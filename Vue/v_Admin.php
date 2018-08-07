<div class='container'>

<?php if($_SESSION["Autorisation"] == 1){ ?>
    
    <div class='CenterTab row'>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title">Création d'utilisateur</h2>
            </div>
            <div class="panel-body">
                <br>
                <form name='formCreateUser' method="post" autocomplete="off">
                    <!-- Empêche l'autocompletion sur les navigateur ignorant autocomplete="off" -->
                    <input type="text" style="display:none">
                    <input type="password" style="display:none">
                    Login : <input type='text' class="form-control" name='login' />
                    Mot de passe : <input type='text' class="form-control" name='password' />
                    Email : <input type='text' class="form-control" name='email' />
                    Droit : 
                    <select class="form-control" name='droit'>
                        <option value='1'>Admin</option>
                        <option value='2'>Gestionnaire</option>
                        <option value='3' selected='selected'>Utilisateur</option>
                    </select>
                    <br><br>
                    <div class="col-md-4 col-md-offset-4">
                        <input type='submit' class="btn btn-success btn-block" name='submitUser' value='Valider' />
                    </div>
                </form>
                <?php 
                if(isset($result)){ 
                    echo $result;
                }?>
            </div>
        </div>
        <br>
        <div class='CenterTab panel panel-default'>
            <div class="panel-heading">
                <h2 class="panel-title">Gestion des Utilisateurs</h2>
            </div>
            <div class="panel-body">
                <?php echo $listShow; } ?>
                <p id="MessageUpdate" style="color: green;"></p>
            </div>
        </div>
    </div>
</div>

<script src='Vue/js/manage_utilisateurs.js'></script>