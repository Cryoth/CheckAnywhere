<?php

if(isset($_POST["url"]) && isset($_POST["idModele"])){
    
    $dir = '../../Vue/images/graphiques';
    $imgpath = $dir.'/'.$_POST["client"].'_'.$_POST["idModele"].'.png';
    $image = file_get_contents($_POST["url"]);

    // Vérifie l'existence du fichier de destination
    if (!file_exists($dir)) {
        echo "Le fichier de destination des images graphique n'existe pas.";
    }
    
    // Vérifie que le fichier soit accessible en écriture
    if (!is_writable($dir)){
        
        echo 'Le fichier n\'est pas accessible en écriture'; 
        
    }else{
        
        // Supprime l'image du graphique si il existe deja dans le fichier
        if(file_exists($dir.'/'.$_POST["client"].'_'.$_POST["idModele"].'.png')){
            unset($imgpath);
        }
        
    }
    
    // Télécharge l'image à l'url donnée
    try{
        
        file_put_contents($imgpath, $image);
        
    }catch(Exception $e){
        
        echo $e;
        
    }

}