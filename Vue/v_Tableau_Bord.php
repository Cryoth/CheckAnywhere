<div class="container col-lg-10 col-lg-offset-1">
    <button id="export-png" class="btn btn-default" title="Télécharger le tableau de bord"><i class="glyphicon glyphicon-save-file" aria-hidden="true"></i></button>
    <div id="GraphContainer" class="row">
        <!-- Ajout des graphiques ici !-->
    </div>
</div>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Commentaire</h4>
      </div>
      <div class="modal-body" method="post">
        <form role="form" class='form-horizontal' method="post">
            <input type="hidden" id="input_indicateur" name="input_indicateur" value=""/>
            <input type="hidden" id="input_date_semaine" name="input_date_semaine" value=""/>
            <div class="form-group">
                <div class="col-sm-12">
                    <textarea class="form-control" rows="5" id="input_commentaire" name="input_commentaire"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12 btn-toolbar">
                    <button name="submit_commentaire" type="submit" class="btn btn-default pull-right">Commenter</button>
                    <button name="delete_commentaire" type="submit" class="btn btn-danger pull-right">Supprimer Commentaire</button>
                </div>
            </div>
        </form>
      </div>
    </div>

  </div>
</div>


<script type="text/javascript">

var json_graphiques = <?php echo json_encode($listGraph) ?>;

</script>