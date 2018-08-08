<div class="container">
    <div class='panel panel-default'>
        <div class='panel-heading'>
            <div class='panel-title row' >
                <div class='text-center col-lg-4 col-lg-offset-4'>Ajout de valeurs de consommation</div>
                <div class="text-right col-lg-2 col-lg-offset-2">
                    <input id='togglePeriod' type="checkbox" data-toggle="toggle" data-on="Journalier" data-off="Hebdomadaire" data-onstyle="success" data-offstyle="info" <?php if($_SESSION["Periodicite"] == 0){ echo "checked"; } ?> />
                </div>
            </div>
        </div>
        <div class="panel-body text-center">
            <div class="row text-center">
                <div class='col-lg-12'>
                    <select id='select-Materiels' name="select-materiel" class='form-control select2'>
                        <?php echo $listMaterielToReturn; ?>
                    </select>
                </div>
            </div>
            <div class="row spacing-top-row">
                <form method="post" name="formAddData" class="form-inline col-lg-4 col-lg-offset-4">
                    <div class="form-group has-feedback">
                        <input type="text" class="datepicker form-control text-center"/>
                        <i class="glyphicon glyphicon-calendar form-control-feedback"></i>
                    </div>
                </form>
            </div>
            <!-- Non aligné avec date pour préserver l'aspect responsive design !-->
            <div class="row spacing-top-row">
                <button name="precedent" type="button" class="btn-change-date btn btn-default col-lg-2 col-lg-offset-2">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                </button>
                <button name="suivant" type="button" class="btn-change-date btn btn-default col-lg-2 col-lg-offset-4">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                </button>
            </div>
            <div class="text-center panel panel-default col-lg-12 top-buffer">
                <div class="panel-body row tab-hebdo-journ table-responsive">
                    <table class="table">
                        <!-- Chargement AJAX du tableau !-->
                    </table>
                </div>
            </div>
            <form action='' method='post' id='formGetExcel'>
                <button type='submit' name='exportExcelAll' class='btn btn-secondary'>Export Données Usine Excel</button>
                <button type='submit' name='exportExcelCurrent' class='btn btn-secondary'>Export Données Matériel Excel</button>
            </form>
        </div>
    </div>
</div>

<script src="Vue/js/manualAdd_data.js"></script>