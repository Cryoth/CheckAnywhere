<div class='container'>
    <div id="blocSelectDatabase" class='row'>
        <div class='col-md-12'>
            <div class='panel panel-default text-center'>
                <div class='panel-heading'>
                    <h2 class='panel-title'>REDIRECTION CHECK ANYWHERE</h2>
                </div>
                <div class='panel-body'>
                	<form method="post" class="row">
                		<?php foreach($listDatabase as $database){ ?>
                		<div class="col-sm-6 col-md-4">
						    <div class="thumbnail">
						    	<br>
							    <i class="fa fa-database fa-7x"></i>
							    <div class="caption">
							        <h3><?php echo $database["nom"]; ?></h3>
							        <p><button  href="#" class="btn btn-primary" name="submit_database" value="<?php echo $database["id"]; ?>">Connexion</button></p>
							    </div>
						    </div>
						 </div>
						<?php } ?>
                	</form>
                </div>
            </div>
        </div>
    </div>
</div>