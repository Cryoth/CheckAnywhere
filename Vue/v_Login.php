<?php ?>
<div class='container'>
    <div id="blocLogin" class='row'>
        <div class='col-md-8 col-md-offset-2'>
            <div class='panel panel-default text-center'>
                <div class='panel-heading'>
                    <h2 class='panel-title'>CHECK ANYWHERE</h2>
                </div>
                <div class='panel-body'>
                    <form name="formConnexion" method="post" class='text-center'>
                        <input class='form-control' type="text" name="login" placeholder="Identifiant"/><br>
                        <input  class='form-control' type="password" name="password" placeholder="Mot de passe"/><br>
                        <a href='?changeMyPass='>Mot de passe perdu ?</a><br><br>
                        <input class='btn btn-success col-md-8 col-md-offset-2' type="submit" name="submitConnexion" value="Entrer" /><br>
                    </form>
                    <?php if(isset($errorLog)){ echo $errorLog; } ?>
                </div>
            </div>
        </div>
    </div>
</div>
