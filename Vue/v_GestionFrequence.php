<div class='container'>
    <?php if($_SESSION["Autorisation"] < 3){ ?>
    <div id='donnee_management'>
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#manage_frequence" aria-controls="manage_frequence" role="tab" data-toggle="tab">Création / Modification Fréquences</a></li>
          <li role="presentation"><a href="#link_frequence" aria-controls="link_frequence" role="tab" data-toggle="tab">Liaison Données/Fréquences</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane active row spacing-bottom" id="manage_frequence">
              <div class="col-md-12">
                  <ul class="list-group spacing-top-row text-center">
                      <li class='list-group-item disabled'>Liste des fréquences existantes</li>
                  </ul>
                  <ul class="list-group spacing-top-row text-center" id="scrollable">
                      <div id="list-all-frequences" class="scrollable">
                      <?php
                        foreach($listFrequences as $row){
                      ?>
                        <li class='list-group-item col-md-12'>
                            <div class="col-md-3">
                                <input type='textbox' class='form-control text-center change-frequence-code' value='<?php echo $row["Code"] ?>' />
                            </div>
                            <div class="col-md-3">
                                <input type='textbox' class='form-control text-center change-frequence-nom' value='<?php echo $row["Nom"] ?>' />
                            </div>
                            <div class="col-md-2">
                                <input type='textbox' disabled="disabled" class='form-control text-center change-frequence-provenance' value='<?php echo $row["Provenance"] ?>' />
                            </div>
                            <div class="col-md-2">
                                <select type='textbox' class='form-control text-center change-frequence-application'>
                                    <option value="<?php echo $row["Application"] ?>"><?php echo $row["Application"] ?></option>
                                    <?php if($row["Application"] == "CIP"){ ?>
                                        <option value="CHECK">CHECK</option>
                                    <?php }else{ ?>
                                        <option value="CIP">CIP</option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-danger delFrequence" value='<?php echo $row["id"] ?>'>Supprimer</button>
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
                  <li class='list-group-item col-md-12 disabled'>Ajout d'une nouvelle frequence</li>
                </ul>
                <div class="col-md-2 spacing-top-row">
                    <input type='textbox' placeholder="Code frequence" id='new-frequence-code' class='form-control text-center'/>
                </div>
                <div class="col-md-2 spacing-top-row">
                    <input type='textbox' placeholder="Nom frequence" id='new-frequence-nom' class='form-control text-center'/>
                </div>
                <div class="col-md-3 spacing-top-row">
                    <input type='textbox' placeholder="Adresse fréquence" id='new-frequence-adresse' class='form-control text-center'/>
                </div>
                <div class="col-md-3 spacing-top-row">
                  <select id='new-frequence-provenance' class='form-control text-center col-md-12'>
                
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
                      <select id='new-frequence-application' class='form-control text-center'>
                          <option value="CIP">CIP</option>
                          <option value="CHECK">CHECK</option>
                      </select>
                </div>
              </div>
              <div class="col-md-12">
                <div class="col-md-4 col-md-offset-4 spacing-top-row">
                    <button id="ajout-new-frequence" class="btn btn-success col-md-12">Ajouter</button>
                </div>
              </div>
          </div>
          <div role="tabpanel" class="tab-pane row spacing-bottom" id="link_frequence">
              <div class="col-md-12 spacing-top-row">
                <select class="form-control" id="provenance_frequence">
                    <?php foreach($listProvenance as $key => $row){ ?>
                        <option value="<?php echo $row["id"]; ?>"><?php echo $row["Nom"]; ?></option>
                    <?php } ?>
                </select>
              </div>
              <div id="link_frequence_content">
                  <!-- Zone de liaison des frequences !-->
              </div>
          </div>
        </div>
    </div>
    <?php } ?>
</div>

<script src="Vue/js/manage_frequence.js"></script>