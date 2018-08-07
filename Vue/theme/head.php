<?php

// Empêche l'utilisation de caches sur les navigateurs et force à recharger les fichiers JS/CSS
// Ce code annonce au navigateur que la version est expirée et lui demande de recharger pour être à jour
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    
    <!-- Les balise meta ci-dessous empêchent le chargement en cache des fichiers js (permet un refresh permanent du js en cas correctif urgent ...) !-->
    <meta http-equiv='cache-control' content='no-cache'>
    <meta http-equiv='expires' content='0'>
    <meta http-equiv='pragma' content='no-cache'>
    
    <?php if(isset($_SESSION["Database"])){ ?>
        <title>Check <?php echo $_SESSION["Database"] ?></title>
    <?php }else{ ?>
        <title>Check Anywhere</title>
    <?php } ?>
    
    <link href="Vue/css/bootstrap.css" rel="stylesheet"> 
    <link href="Vue/css/bootstrap-switch.css" rel="stylesheet"> 
    <link href="Vue/css/bootstrap-toggle.min.css" rel="stylesheet">
    <link href="Vue/css/select2.min.css" rel="stylesheet">
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.2.3/css/rowReorder.bootstrap.min.css"/>
    <link rel="stylesheet" href="Vue/css/custom.css" />

    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="http://code.highcharts.com/highcharts.js"></script>
    <script type="text/javascript" src="http://code.highcharts.com/modules/exporting.js"></script>
    <script type="text/javascript" src="http://code.highcharts.com/modules/offline-exporting.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/rowreorder/1.2.3/js/dataTables.rowReorder.min.js"></script>

</head>
