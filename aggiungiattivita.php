<?php 
session_id("associazione");
session_start(); 
require_once("checklogged.php");
$_SESSION["currentpage"]="Aggiungi associazione";
 

if(isset($_SESSION["livello"])){
    if($_SESSION["livello"]!=0){
        header("Location: home.php");
        exit;
    }
}
if(!isset($_SESSION["addattivita"])){
    $_SESSION["addattivita"]=false;
}

require_once("connessione.php");

?>
<!DOCTYPE HTML>
<html>
    <head>
        <link href="bootstrap/bootstrapdistr/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <?php include("navbar.php"); ?>

        <div class="container mt-5">
            <h2 class="text-center mb-4">Aggiungi una nuova attivita</h2>
            <br><br>
            <div class="row">
                <div class="col-md-3">
                <!--  -->
                </div>
                <div class="col-md-6 border rounded p-4">
                    <?php
                        if(isset($_SESSION["eaddattivita"])){
                            echo "<div class='alert alert-danger mt-3' role='alert'><b>Errore:</b> ".$_SESSION["eaddattivita"]."</div>";
                            unset($_SESSION["eaddattivita"]);
                        }
                        if($_SESSION["addattivita"]){
                            echo "<div class='alert alert-success mt-3' role='alert'><center><b>Attivita aggiunta correttamente.</b></center></div>";
                            $_SESSION["addattivita"]=false;
                        }
                    ?>
                    <form action="addattivita.php" method="post" class="justify-content-center" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome dell'attività</label>
                            <div class="input-group">
                                <input name="nome"  type="text" class="form-control" id="nome" aria-describedby="">
                            </div>
                        </div>
                        
                        <label for="descrizione" class="form-label">Descrizione</label>
                        <div class="input-grou mb-3">
                            <textarea name="descrizione"  class="form-control" id="descrizione" aria-label="With textarea"></textarea>
                        </div>
        
                        <label for="costo" class="form-label">Costo</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">€</span>
                            <input name="costo"  type="text" id="costo" class="form-control" aria-label="">
                        </div>
        

                        <label for="imgfile" class="form-label">Immagine</label>
                        <div class="input-group">
                            <input type="file" class="form-control" id="imgfile" name="imgfile">
                        </div>
                        <div id="emailHelp" class="form-text">I formati consentiti sono JPG, JPEG e PNG</div>
                        

                        <h3 class="mt-3">Aggiungi animatori:</h3>
                        <div class="d-flex flex-column gap-2">
                        <?php
                        $sql = "SELECT id,Cognome,Nome FROM persone";
                        $preparata = $connessione->prepare($sql);
                        $preparata->execute();
                        $animatori = $preparata->fetchAll();
                        foreach($animatori as $animatore){
                            echo "<div class='input-group'>
                                <div class='input-group-text'>
                                    <input class='form-check-input mt-0' type='checkbox' name=animatore[] id='check".$animatore["id"]."'value=".$animatore["id"].">
                                </div>
                                <label class='input-group-text' for='check".$animatore["id"]."'>".$animatore["Cognome"]." ".$animatore["Nome"]." </label>
                                </div>";
                            }
                            ?>
                        <div id='emailHelp' class='form-text'>È possibile non inserire alcun animatore</div>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4 mb-5">
                            <button class="btn btn-outline-primary">Aggiungi attivita</button>
                        </div>
                    </form>
                </div>
                    
                <div class="col-md-3">
                    
                </div>
            </div>
        </div>

        <br><br><br><br><br><br>
        <script src="bootstrap/bootstrapdistr/js/bootstrap.min.js"></script>
    </body>
</html>

