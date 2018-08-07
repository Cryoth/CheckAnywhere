<div class="container">
    <div class='row text-center'>
        <table id="gestionModele" class="display table table-striped table-bordered">
            <thead>
                <th>Ordre</th>
                <th>N°</th>
                <th>Visible</th>
                <th>Nom Modèle</th>
                <th>Créateur</th>
                <th>Format</th>
                <th>Durée</th>
                <th class="no-sort"></th>
                <th class="no-sort"></th>
            </thead>
            <tbody>
                <?php foreach ($modeles as $key => $modele) { ?>
                    <tr>
                        <td><?php echo $key; ?></td>
                        <td><?php echo $modele["id"]; ?></td>
                        <?php if($modele["Actif"] == 1){ ?>
                            <td><input type="checkbox" class="checkActif" value="<?php echo $modele["id"]; ?>" checked="checked"></td>
                        <?php }else{ ?>
                            <td><input type="checkbox" class="checkActif" value="<?php echo $modele["id"]; ?>"></td>
                        <?php } ?>
                        <td><?php echo $modele["Nom"]; ?></td>
                        <td><?php echo getNomCreateurById($PDO, $modele["idCreateur"]); ?></td>
                        <td><?php echo $modele["FormeGraph"]; ?></td>
                        <td><?php echo $modele["Periode"]; ?> Semaines</td>
                        <td>
                            <button class='modifModele btn btn-primary' value='<?php echo $modele["id"]; ?>'  data-toggle="modal" data-target="#modal-modif-modele<?php echo $modele["id"]; ?>" 
                            <?php 
                                if($_SESSION['Autorisation'] !== '1' and $_SESSION['UserId'] !== $modele["idCreateur"]){
                                    echo "disabled";
                                } 
                            ?>>
                            Modifier
                            </button>
                        </td>
                        <td>
                            <button class='delModele btn btn-warning' value='<?php echo $modele["id"]; ?>' data-toggle="modal" data-target="#confirm-delete"
                            <?php 
                                if($_SESSION['Autorisation'] !== '1' and $_SESSION['UserId'] !== $modele["idCreateur"]){
                                    echo "disabled";
                                } 
                            ?>>
                            Supprimer
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Fenetre de suppression !-->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Suppression du modèle
            </div>
            <div class="modal-body">
                Etes-vous sûr de vouloir supprimer ce modèle ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                <a class="btn btn-danger btn-ok" id="btn-supprimer-modele">Supprimer</a>
            </div>
        </div>
    </div>
</div>

<!-- Generation des fenetres  !-->
<?php foreach ($modeles as $modele) { ?>
    <div class="modal fade" id="modal-modif-modele<?php echo $modele["id"]; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    MODIFICATION DU MODELE
                </div>
                <div class="modal-body modal-modifier">
                    <label class="control-label">Nom Modèle :</label>
                    <input type="text" id="ModeleName<?php echo $modele["id"]; ?>" class="form-control" value="<?php echo $modele["Nom"]; ?>">
                    <br>
                    <div class="row">
                        <div class="col-sm-6 text-center">
                            <label class="control-label">Période :</label>
                            <input type="number" id="ModelePeriode<?php echo $modele["id"]; ?>" min="6" max="52" class="form-control" value="<?php echo $modele["Periode"]; ?>">
                        </div>
                        <div class="col-sm-6 text-center">
                            <label class="control-label">Type du graphique :</label>
                            <select class="form-control" id="ModeleFormat<?php echo $modele["id"]; ?>">
                            <?php if($modele["FormeGraph"] == 'histogramme'){ ?>
                                <option value="histogramme" selected >histogramme</option>
                                <option value="courbe">courbe</option>
                            <?php }else{ ?>
                                <option value="histogramme">histogramme</option>
                                <option value="courbe" selected >courbe</option>
                            <?php } ?>
                            </select>
                        </div>
                    </div>
                    <br>
                    <hr>
                    <h4 class="text-center">INDICATEURS</h4>
                    <?php foreach(getAllIndicateursFromGraph($PDO, $modele["id"]) as $key => $indicateur){ ?>
                        <input type="hidden" id="IdIndic<?php echo $modele["id"].($key+1); ?>" value="<?php echo $indicateur["id"]; ?>">
                        <div class="row">
                            <div class="col-sm-10">
                                <label class="control-label">Nom de l'indicateur n° <?php echo $key+1; ?> :</label>
                                <input type="text" class="form-control" id="NomIndic<?php echo $modele["id"].($key+1); ?>" value="<?php echo $indicateur["Nom"]; ?>">
                            </div>
                            <div class="col-sm-2">
                                <label class="control-label">Couleur :</label>
                                <input type="color" class="form-control" id="CouleurIndic<?php echo $modele["id"].($key+1); ?>" value="<?php echo $indicateur["Couleur"]; ?>">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="control-label">Nom de l'objectif :</label>
                                <input type="text" class="form-control" id="NomObjIndic<?php echo $modele["id"].($key+1); ?>" value="<?php echo $indicateur["Objectif_Libelle"]; ?>">
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Valeur de l'objectif :</label>
                                <input type="text" class="form-control" id="ValObjIndic<?php echo $modele["id"].($key+1); ?>" value="<?php echo $indicateur["Objectif_Val"]; ?>">
                            </div>
                        </div>
                        <br>
                        <hr>
                    <?php } ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                    <button class="btn btn-success btn-ok btn-modifier-modele" value="<?php echo $modele["id"]; ?>">Valider Modification</button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>