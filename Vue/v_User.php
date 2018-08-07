<div class="container">
    <div class='row'>
        <br><br>
            <?php echo $showUser; ?>
        <br>
        <div class='blocPassword panel panel-default'>
            <div class="panel-heading">
                <h2 class="panel-title">Changer de mot de passe</h2>
            </div>
            <div class="panel-body">
                <form method="post" name="changePassword" class='col-md-6 col-md-offset-3'>
                    <div class='form-group'>
                        <div class='input-group'>
                            <span class='input-group-addon' style="min-width: 250px;">Ancien mot de passe :</span><input class='form-control' type="password" name="pastPassword" />
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class='input-group'>
                            <span class='input-group-addon' style="min-width: 250px;">Nouveau mot de passe :</span><input class='form-control' type="password" name="newPassword" />
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class='input-group'>
                            <span class='input-group-addon' style="min-width: 250px;">Confirmation mot de passe :</span><input class='form-control' type="password" name="confirmPassword" />
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-4 col-md-offset-4'>
                            <input type="submit" name="submitChangePass" value="Valider" class='btn btn-success btn-block' />
                        </div>
                    </div>
                    <?php if(isset($message)){ echo $message; } ?>
                </form>
            </div>
        </div>
        <div class='blocMail panel panel-default'>
            <div class="panel-heading">
                <h2 class="panel-title">Changer d'adresse Email</h2>
            </div>
            <br>
            <form method="post" name="changePassword">
                <div class="col-md-6 col-md-offset-3">
                    <div class='form-group'>
                        <div class='input-group'>
                            <span class='input-group-addon'  style="min-width: 250px;">Nouvelle adresse email :</span><input class='form-control' type="text" name="newMail" />
                        </div>
                    </div>
                </div>
                <div class='row'>
                    <div class='col-md-2 col-md-offset-5'>
                        <input type="submit" name="submitChangeMail" value="Valider" class='btn btn-success btn-block' />
                    </div>
                </div>
                <div class='row'>
                    <?php if(isset($messageMail)){ echo $messageMail; } ?>
                </div>
            </form>
            <br>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title">Envoi par mail de mon tableau de bord en début de semaine</h2>
            </div>
            <div class="panel-body">
                <div class='row'>
                    <div class="col-md-offset-1 col-md-10 text-center">
                        <?php if($sendDashboard['SendDashboard'] == 1){ ?>
                            <input id="toggle-send-dashboard" value="<?php echo $_SESSION["UserId"]; ?>" type="checkbox" checked data-on="Oui je souhaite recevoir des mails" data-off="Non je ne souhaite pas recevoir de mails" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-width="300">
                        <?php }else{ ?>
                            <input id="toggle-send-dashboard" value="<?php echo $_SESSION["UserId"]; ?>"  type="checkbox" data-on="Oui je souhaite recevoir des mails" data-off="Non je ne souhaite pas recevoir de mails" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-width="300">
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>