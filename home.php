<?php
session_id("associazione"); 
session_start(); 
$_SESSION["currentpage"]="Home";
require_once("checklogged.php");
require_once("connessione.php");

?>
<!DOCTYPE HTML>
<html>
    <head>
        <link href="bootstrap/bootstrapdistr/css/bootstrap.min.css" rel="stylesheet">
        <link href="style.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <?php include("navbar.php"); ?>
        <div class="container mt-5">
            <h2 class="text-center mb-4">Elenco delle attività</h2>

            <div class="d-flex flex-row flex-wrap justify-content-between  mt-5" id="cards-container">
            <?php
                $sql1=("SELECT idatt, NomeAtt, imgfile FROM attivita");
                $preparata = $connessione->prepare($sql1);
                $preparata->execute();
                $attivita = $preparata->fetchAll();

                
                foreach($attivita as $att){
                    //echo "<div class='col-md-3'>";
                        echo "<div class='card mb-5' style='width: 17rem;'>";
                            echo "<div class='card-img-top'>";
                                echo "<img style='object-fit: cover;' src='img/".$att["imgfile"]."' class='card-img-top' alt='...'>";
                            echo "</div>";
                            echo "<div class='card-body'>";
                                echo "<center><h4 class='card-title'>".$att["NomeAtt"]."</h4></center>";
                                echo "<h5 class='card-text mt-3'>Animatori:</h5>";
                                $sql2=("SELECT id,Cognome,Nome FROM persone, anima WHERE persone.id=anima.Estidpers AND anima.Estidatt = ".$att["idatt"]);
                                $preparata = $connessione->prepare($sql2);
                                $preparata->execute();
                                $animatori = $preparata->fetchAll();
                                echo '<ul class="list-group list-group-flush">';
                                foreach($animatori as $animatore){
                                    echo "<li class='list-group-item'>".$animatore["Cognome"]." ".$animatore["Nome"]."</li>";
                                }
                                echo '</ul>';
                                $linkscheda="scheda.php?idatt=".$att["idatt"];

                                echo "</div>";
                                echo "<div class='card-footer bg-white py-3'>";
                                echo "<center><a href='".$linkscheda."' class='btn btn-primary'>Scheda</a></center>";
                            echo "</div>";
                        echo "</div>";
                    //echo "</div>";
                }
                ?>
                </div>



        </div>






    <script src="bootstrap/bootstrapdistr/js/bootstrap.min.js"></script>
    </body>
</html> 