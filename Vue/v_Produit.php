<div class='container'>
    <?php if($_SESSION["Autorisation"] < 3){ ?>
    <div id='produit_management'>
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#manage_prod" aria-controls="manage_prod" role="tab" data-toggle="tab">Création / Modification Produits</a></li>
          <li role="presentation"><a href="#link_prod" aria-controls="link_prod" role="tab" data-toggle="tab">Liaison Produit / Donnée Compteur</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane active row spacing-bottom" id="manage_prod">
                <div class="col-md-12 spacing-top-row">
                    <ul class="list-group spacing-top-row text-center">
                      <li class='list-group-item col-md-12 disabled'>Ajout d'un nouveau produit</li>
                    </ul>
                    <div class="col-md-5 spacing-top-row">
                        <input type='textbox' placeholder='Intitulé du produit' id='new-prod-nom' class='form-control text-center'/>
                    </div>
                    <div class="col-md-5 spacing-top-row">
                        <input type='number' placeholder='Prix unitaire du produit' id='new-prod-prix' class='form-control text-center'/>
                    </div>
                    <div class="col-md-2 spacing-top-row">
                        <button id="ajout-new-prod" class="btn btn-success">Ajouter</button>
                    </div>
                </div>
              <div class="col-md-12">
                  <ul class="list-group spacing-top-row text-center">
                      <li class='list-group-item col-md-12 disabled'>Liste des Produits</li>
                      <div id="list-all-prod" class="scrollable">
                      <?php
                        foreach($listGrpData as $row){
                      ?>
                        <li class='list-group-item col-md-12'>
                            <div class="col-md-5">
                                <input type='textbox' class='form-control text-center change-prod-nom' value='<?php echo $row["Nom"] ?>' />
                            </div>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <input type='textbox' class='form-control text-center change-prod-prix' value='<?php echo $row["Prix_Defaut"] ?>'/>
                                    <span class="input-group-addon">€</span>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-danger delProd" value='<?php echo $row["id"] ?>'>Supprimer</button>
                            </div>
                        </li>
                      <?php
                        }
                      ?>
                      </div>
                  </ul>
              </div>
          </div>
          <div role="tabpanel" class="tab-pane row spacing-bottom" id="link_prod">
            <!-- Zone de liaison des produits !-->
          </div>
        </div>
    </div>
    <?php } ?>
</div>

<script src="Vue/js/manage_produit.js"></script>