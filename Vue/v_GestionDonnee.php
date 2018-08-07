<div class='container'>
    <?php if($_SESSION["Autorisation"] < 3){ ?>
    <div id='donnee_management'>
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#manage_donnee" aria-controls="manage_donnee" role="tab" data-toggle="tab">Création / Modification données</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane active row spacing-bottom" id="manage_donnee">
              <div class="col-md-12">
                  <ul class="list-group spacing-top-row text-center">
                      <li class='list-group-item disabled'>Liste des données existantes</li>
                  </ul>
                  <ul class="list-group spacing-top-row text-center">
                        <li class='list-group-item col-md-3 disabled'>Code</li>
                        <li class='list-group-item col-md-3 disabled'>Nom affiché</li>
                        <li class='list-group-item col-md-3 disabled'>Provenance</li>
                        <li class='list-group-item col-md-2 disabled'>Application</li>
                        <li class='list-group-item col-md-1 disabled'>Visible</li>
                  </ul>
                  <ul class="list-group spacing-top-row text-center col-md-12" id="scrollable">
                      <div id="list-all-donnees" class="scrollable">
                      <?php
                        foreach($listDonnees as $row){
                      ?>
                        <li class='list-group-item col-md-12'>
                            <div class="col-md-3">
                                <input type='textbox' class='form-control text-center change-donnee-code' value='<?php echo $row["Code"] ?>' />
                            </div>
                            <div class="col-md-3">
                                <input type='textbox' class='form-control text-center change-donnee-nom' value='<?php echo $row["Nom"] ?>' />
                            </div>
                            <div class="col-md-3">
                                <input type='textbox' disabled="disabled" class='form-control text-center change-donnee-provenance' value='<?php echo $row["Provenance"] ?>' />
                            </div>
                            <div class="col-md-2">
                                <select type='textbox' class='form-control text-center change-donnee-application'>
                                    <option value="<?php echo $row["Application"] ?>"><?php echo $row["Application"] ?></option>
                                    <?php if($row["Application"] == "CIP"){ ?>
                                        <option value="CHECK">CHECK</option>
                                    <?php }else{ ?>
                                        <option value="CIP">CIP</option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-1">
                                    <?php if($row["Visible"] == 1){ ?>
                                       <input type='checkbox' class='change-donnee-visibilite' value='<?php echo $row["id"]; ?>' checked/>
                                    <?php }else{ ?>
                                       <input type='checkbox' class='change-donnee-visibilite' value='<?php echo $row["id"]; ?>'/>
                                    <?php } ?>
                            </div>
                        </li>
                      <?php
                        }
                      ?>
                      </div>
                  </ul>
              </div>
              <div class="col-md-12 spacing-top-row">
                <ul class="list-group spacing-top-row text-center">
                  <li class='list-group-item col-md-12 disabled'>Ajout d'une nouvelle donnee</li>
                </ul>
                <div class="col-md-2 spacing-top-row">
                    <input type='textbox' placeholder="Code donnee" id='new-donnee-code' class='form-control text-center'/>
                </div>
                <div class="col-md-2 spacing-top-row">
                    <input type='textbox' placeholder="Nom donnee" id='new-donnee-nom' class='form-control text-center'/>
                </div>
                <div class="col-md-3 spacing-top-row">
                    <input type='textbox' placeholder="Adresse donnée" id='new-donnee-adresse' class='form-control text-center'/>
                </div>
                <div class="col-md-3 spacing-top-row">
                  <select id='new-donnee-provenance' class='form-control text-center col-md-12'>
                
                    <?php
                        foreach($listProvenance as $row){
                    ?>
                        <option value="<?php echo $row["id"] ?>"><?php echo $row["Nom"] ?></option>
                    <?php
                        }
                    ?>
                  </select>
                </div>
                  <div class="col-md-2 spacing-top-row">
                      <select id='new-donnee-application' class='form-control text-center'>
                          <option value="CIP">CIP</option>
                          <option value="CHECK">CHECK</option>
                      </select>
                </div>
              </div>
              <div class="col-md-12">
                <div class="col-md-4 col-md-offset-4 spacing-top-row">
                    <button id="ajout-new-donnee" class="btn btn-success col-md-12">Ajouter</button>
                </div>
              </div>
          </div>
        </div>
    </div>
    <?php } ?>
</div>

<script src="Vue/js/manage_data.js"></script>