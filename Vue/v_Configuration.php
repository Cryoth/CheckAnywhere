<h1>Création de Modèle</h1>
<div class="blocContainer">
    <br>
    <div id='blocFiltre'>
        <table id="TableFiltre">
            
            <h2>Filtre</h2>
            
                <tr><th>Site :</th><td><select name="SelectSite"></select></td></tr>
                <tr><th>Atelier :</th><td><select name="SelectAtelier"><option value = "aucun">Aucun</option></select></td></tr>
                <tr><th>Secteur :</th><td><select name="SelectSecteur"><option value = "aucun">Aucun</option></select></td></tr>
                <tr><th>NEP :</th><td><select name="SelectNEP"><option value = "aucun">Aucune</option></select></td></tr>
                <tr><th>Matériel :</th><td><select name="SelectMateriel"><option value = "aucun">Aucun</option></select></td></tr>
                <tr><th>Type :</th><td><select name="SelectType"><option value="" selected>Aucun</option></select></td></tr>
                    
        </table>
        <br><br>

        <button id="submitFiltre" value="subfiltre" >Filtrer</button>
    </div>
    <form name="ConfigChoix" method="post">
    <div id='blocChoix'>
        <h2>Modèle</h2>

            <table>
                <tr>
                    <th>Titre du Modèle :</th>
                    <td><input type ="text" name="nomGraph" /></td>
                </tr>
            </table>
            <br>
            
            <table id="selectChart">
                <tr>
                    <td>
                        <p>Histogramme</p><img src="Vue/images/chart-hist.png"/>
                    </td>
                    <td>
                        <p>Camembert</p><img src="Vue/images/chart-camembert.png"/>
                    </td>
                    <td>
                        <p>Donut</p><img src="Vue/images/chart-donut.png"/>
                    </td>
                    <td>
                        <p>Courbe</p><img src="Vue/images/chart-courbe.png"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="radio" name="chart" value="histogramme" checked="checked" />
                    </td>
                    <td>
                        <input type="radio" name="chart" value="camembert" />
                    </td>
                    <td>
                        <input type="radio" name="chart" value="donut" />
                    </td>
                    <td>
                        <input type="radio" name="chart" value="courbe" />
                    </td>
                </tr>
            </table>
            <br>
                   
            <table>
                <tr><th>Période :</th>
                    <td><select name="Date">
                        <option value ="4">1 Mois</option>
                        <option value ="8">2 Mois</option>
                        <option value ="13">3 Mois</option>
                        <option value ="26">6 Mois</option>
                        <option value ="52">1 An</option>
                        </select>
                    </td>
                    <th>
                        Priorité de placement :
                    </th>
                    <td>
                        <input type='number' name='Position' value='1' />
                    </td>
                </tr>
            </table>
    </div>
    <div id="BlocValueModele">
        <h2>Données</h2>
            <img id='add' src='Vue/images/addButton.png'/>
            <div id="dataplace"></div>
            <br>
            <div id="errorCreation"></div>
            <input type="submit" value="Enregistrer" name="enregMod"/>

        
        <br><br>
    </div>
    </form>
    <br><br>
    
    <h1>Gestion des Modèles</h1>
    <div id="gestionModele">
        <div class='TitleModeleListe'>
            <table>
                <tr>
                    <td class="trie">Actif<img src='Vue/images/fleche_tri.png' /></td>
                    <td class="trie">Place<img src='Vue/images/fleche_tri.png' /></td>
                    <td class="trie">Créateur<img src='Vue/images/fleche_tri.png' /></td>
                    <td class="trie">Nom Modèle<img src='Vue/images/fleche_tri.png' /></td>
                    <td class="trie">Forme<img src='Vue/images/fleche_tri.png' /></td>
                    <td class="trie">Période<img src='Vue/images/fleche_tri.png' /></td>
                </tr>
            </table>
        </div>
        <?php echo $Modeles ?>
        <br>
    </div>
</div>