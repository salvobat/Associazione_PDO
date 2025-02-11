<?php 
session_id(id: "associazione");
session_start(); 

require_once("checklogged.php");

if(!isset($_GET["idatt"])){
    header("Location: home.php");
    exit;
}
require_once("connessione.php");
$preparata = $connessione->prepare('SELECT NomeAtt, Descrizione, CostoMensile, imgfile FROM attivita WHERE idatt=:idatt');
$preparata->execute([
    ":idatt"=>$_GET["idatt"]
]);
$attivita = $preparata->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE HTML>
<html>
    <head>
        <link href="bootstrap/bootstrapdistr/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <?php include("navbar.php"); 
        
        /////////////////////////////////////////////////////////////////////////
        if($attivita){ ?>
        <div class="container mt-5">
            <h1 class="text-center mb-4"><?php echo "<b>".$attivita["NomeAtt"]."</b></h1>"; ?>
            
            <div class="row mt-5">
                <div class="col-md-3">
                    <img src="img/<?php echo $attivita["imgfile"];?>" class="img-thumbnail">
                </div>
                <div class="col-md-9">
                    <h3 class="mt-4"><b>Descrizione</b></h3>
                    <p><?php echo ucfirst($attivita["Descrizione"]) ?></p>
                    <h3 class="mt-4"><b>Animatori: </b></h3>
                    <?php
                    $sql2=("SELECT id,Cognome,Nome FROM persone, anima WHERE persone.id=anima.Estidpers AND anima.Estidatt = ".$_GET["idatt"]);
                    $preparata = $connessione->prepare($sql2);
                    $preparata->execute();
                    $animatori = $preparata->fetchAll();
                    echo '<ul class="list-group list-group-flush w-50">';
                    foreach($animatori as $animatore){
                        echo "<li class='list-group-item'>".$animatore["Cognome"]." ".$animatore["Nome"]."</li>";
                    }
                    echo '</ul>';
                    ?>
                    
                </div>
            </div>
            
            
            
            
            
        </div>
        <?php } 
        ///////////////////////////////////////////////////////////////////////////
        else{?>

        <div class="container mt-5">
            <center>
            <div class="alert alert-danger w-50" role="alert">
                <h4 class="alert-heading">ERRORE 404  - ATTIVITA NOT FOUND</h4>
                <p>Ci dispiace, ma non siamo riusciti a trovare questa attività. <br> Questo attività potrebbe non esistere o non essere più disponibile.</p>
                <hr>
                <a href="home.php" class="btn btn-outline-danger" role="button">HOME</a>
            </div>
            </center>
        </div>
        <?php } ?>
        <script src="bootstrap/bootstrapdistr/js/bootstrap.min.js"></script>
    </body>
</html>

