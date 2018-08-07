<div class="container">
    <br>
    <div class='col-md-4'>
        <div class='panel panel-default height-filter'>
            <div class='panel-heading'>
                <h2 class='panel-title text-center'>Filtre à données</h2>
            </div>
            <br>
            <div class='row text-center'>
                <label class='control-label'>Application</label>
            </div>
            <div class='row text-center'>
                <div class='radioSource btn-group'>
                    <label id='CIP' class='btn btn-success active'>
                        cip anywhere
                        <input class='collapse' type="radio" name="SelectSource" checked="checked" value="CIP" />
                    </label>
                    <label id='CHECK' class='btn btn-success'>
                        check anywhere
                        <input class='collapse' type="radio" name="SelectSource" value="CHECK" />
                    </label>
                </div>
            </div>
            <br>
            <div class='row text-center'>
                <label class='control-label'>Format des données</label>
            </div>
            <div class='row'>
                <div id='checkboxFrequenceEuro' class='form-group'>
                    <div class='col-md-8 col-md-offset-2 input-group'>
                        <label class=' form-control'>
                            Valeurs en Euros
                        </label>
                        <span class='input-group-addon'>
                            <input type="checkbox" name="SelectEuros" />
                        </span>
                    </div>
                    <div class='col-md-8 col-md-offset-2 input-group'>
                        <label class=' form-control'>
                            Valeurs en Ratio
                        </label>
                        <span class='input-group-addon'>
                            <input type="checkbox" name="SelectFrequence"/>
                        </span>
                    </div>
                </div>
            </div>
            <div class='row text-center'>
                <label class='control-label'>Provenance de la donnée :</label>
            </div>
            <div id="TableFiltre" class='row form-group text-center'>
                <div class='col-md-8 col-md-offset-2 input-group'>
                    <span class='input-group-addon label-selector'>Materiel :</span>
                    <select name="SelectMateriel" class='form-control'><option value = "aucun">Tous</option></select>
                </div>
                <div class='col-md-8 col-md-offset-2 input-group'>
                    <span class='input-group-addon label-selector'>Unite:</span>
                    <select name="SelectUnite" class='form-control'><option value="" selected>Tous</option></select>
                </div>                
            </div>
            <br>
            <div class='row'>
                <div class='col-md-10 col-md-offset-1'>
                    <select class="valueSelect form-control" id="valueSelector" size="5"></select>
                </div>
            </div>
            <div class='row text-center'>
                <div class='col-md-10 col-md-offset-1'>
                    <br>
                    <label class='control-label'>Valeurs à cumuler :</label>
                    <select class="valueSelect form-control" id="valueCumul" size="5"></select>
                </div>
            </div>
            <br>
            <div class='row'>
                <div class='col-md-8 col-md-offset-2'>
                    <button id='ValidCumul' class='btn btn-success btn-block'>Ajouter</button>
                </div>
            </div>
            <br>
        </div>
    </div>
    <form name="ConfigChoix" method="post">
    <div class='col-md-8'>
        <div class='panel panel-default  height-filter'>
            <div class='panel-heading'>
                <h2 class='panel-title text-center'>Information du Modèle</h2>
            </div>
            
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 form-group text-center">
                        <input type="text" class="form-control" name="nomGraph" placeholder="Nom du Modele" style="text-transform:uppercase" />
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12 form-inline">
                                <p>Indicateurs :</p>
                                <input type="hidden" name="hiddenResult1" class="hiddenResult" />
                                <div class="input-group">
                                    <input type="text" name="prod1" class="form-control" placeholder="Nom indicateur 1"/>
                                    <span class="input-group-btn">
                                        <input type="color" name="color1" class="form-control" style="width: 50px;"/>
                                        <button submit="none" value="1" class="btn btn-default clearData">Effacer</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-inline">
                                <input type="hidden" name="hiddenResult2" class="hiddenResult" />
                                <div class="input-group">
                                    <input type="text" name="prod2" class="form-control" placeholder="Nom indicateur 2"/>
                                    <span class="input-group-btn">
                                        <input type="color" name="color2" class="form-control" style="width: 50px;"/>
                                        <button submit="none" value="2" class="btn btn-default clearData">Effacer</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-inline">
                                <input type="hidden" name="hiddenResult3" class="hiddenResult" />
                                <div class="input-group">
                                    <input type="text" name="prod3" class="form-control" placeholder="Nom indicateur 3"/>
                                    <span class="input-group-btn">
                                        <input type="color" name="color3" class="form-control" style="width: 50px;"/>
                                        <button submit="none" value="3" class="btn btn-default clearData">Effacer</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p>Objectif :</p>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="objectifNom1" class="form-control" placeholder="Titre Objectif 1"/>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="objectifVal1" class="form-control" placeholder="Valeur Objectif 1"/>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="objectifNom2" class="form-control" placeholder="Titre Objectif 2"/>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="objectifVal2" class="form-control" placeholder="Valeur Objectif 2"/>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="objectifNom3" class="form-control" placeholder="Titre Objectif 3"/>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="objectifVal3" class="form-control" placeholder="Valeur Objectif 3"/>
                            </div>
                    </div>
                </div>

            <div id="zoneGraph" class="row text-center">
                <div id="GraphContainer" class="col-md-12">
                    <!-- Ajout du graphique ici !-->
                </div>
            </div>
            <div id="selectChart" class="row">
                <label class="blocChart col-md-1 col-md-offset-5">
                    <img src="Vue/images/chart-hist.png" class="btn btn-default radio"/><br>
                    <input id="radio-histogramme" type="radio" class="collapse" name="chart" value="histogramme" checked="checked" />
                </label>
                <label class="blocChart col-md-1">
                    <img src="Vue/images/chart-courbe.png" class="btn btn-default radio"/><br>
                    <input id="radio-courbe" type="radio" class="collapse" name="chart" value="courbe" />
                </label>
            </div>
            <div class='row'>
                <div class='col-md-4 col-md-offset-4'>
                    <input type="range" name="Date" max="52" min="4" value="4"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-md-offset-1">
                    <div class="GraphInfo row">
                        <p>Periode d'affichage :</p>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" name="DateText" value="4" class="form-control" />
                                <span class="input-group-addon">Semaines</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-md-offset-1">
                    <div class="GraphInfo row">
                        <p>Priorité :</p>
                        <input type='number' class="form-control bfh-number" name='Position' min="1" value='1' />
                    </div>
                </div>
                <div class="col-md-3 col-md-offset-1">
                    <div class="GraphInfo row form-group">
                        <p>Privatiser le modèle :</p>
                        <div class='input-group'>
                            <span class='input-group-addon'>
                                <input type='checkbox' class="checkbox" />
                            </span>
                            <input type='text' value='Privé' disabled='disabled' class='form-control' />
                        </div>
                    </div>
                </div>
            </div>
            <br>
                <div class='row text-center'>
                    <div class='col-md-4 col-md-offset-4'>
                        <input type="submit" class='btn btn-success btn-block' name="enregMod" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
</div>