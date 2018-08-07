<div class='container'>
    <?php if($_SESSION["Autorisation"] < 3){ ?>
    <div id='atelier_management'>
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#manage_atelier" aria-controls="manage_atelier" role="tab" data-toggle="tab">Création / Modification Atelier</a></li>
          <li role="presentation"><a href="#link_atelier" aria-controls="link_atelier" role="tab" data-toggle="tab">Liaison Atelier / Matériel</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane active row spacing-bottom" id="manage_atelier">
              <div class="col-md-12">
                  <ul class="list-group spacing-top-row text-center">
                      <li class='list-group-item col-md-12 disabled'>Liste des Ateliers</li>
                      <div id="list-all-atelier" class="scrollable">
                           <!-- Ajout en ajax de la liste des ateliers !-->
                      </div>
                  </ul>
              </div>
              <div class="col-md-12 spacing-top-row">
                <ul class="list-group spacing-top-row text-center">
                  <li class='list-group-item col-md-12 disabled'>Ajout d'un nouvel atelier</li>
                </ul>
                <div class="col-md-7 col-md-offset-2 spacing-top-row">
                    <input type='textbox' placeholder="Nom de l'atelier" id='new-atelier-nom' class='form-control text-center'/>
                </div>
                <div class="col-md-2 spacing-top-row">
                    <button id="ajout-new-atelier" class="btn btn-success">Ajouter</button>
                </div>
              </div>
          </div>
          <div role="tabpanel" class="tab-pane row spacing-bottom" id="link_atelier">
            <!-- Zone de liaison des ateliers !-->
          </div>
        </div>
    </div>
    <?php } ?>
</div>

<script src="Vue/js/manage_atelier.js"></script>