<?php 
session_id("associazione");
session_start(); 
require_once("checklogged.php");
$_SESSION["currentpage"]="Modifica attivita";
 

if(isset($_SESSION["livello"])){
    if($_SESSION["livello"]!=0){
        header("Location: home.php");
        exit;
    }
}
if(!isset($_SESSION["modattivita"])){
    $_SESSION["modattivita"]=false;
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
            <h2 class="text-center">Modifica una attività</h2>
            <br><br>
            <?php if(!isset($_GET["attivita"])){?>

                <form class="d-flex flex-row gap-2 justify-content-center" role="search" method="get" action="modificaattivita.php">
                    <div class="align-content-center">
                        <select name="attivita" class="form-select" aria-label="Default select example">
                            <option value="">Seleziona una attività</option>
                            <?php
                                $preparata = $connessione->prepare("SELECT idatt, NomeAtt FROM attivita");
                                $preparata->execute();
                                $attivita = $preparata->fetchAll();

                                foreach($attivita as $att){
                                    echo "<option value='".$att["idatt"]."'>".$att["NomeAtt"]."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="">
                        <button class="btn btn-outline-success" type="submit">Seleziona</button>
                    </div>
                </form>


            <?php } 
            else{
                $_SESSION["idatt"]=$_GET["attivita"];
                $preparata = $connessione->prepare("SELECT * FROM attivita WHERE idatt=:idatt");
                $preparata->execute([":idatt"=>$_GET["attivita"]]);
                $attivita = $preparata->fetch();
                if($attivita){
                    ?>
                    <h3 class="text-center mb-4"><?php echo $attivita["NomeAtt"]; ?></h3>
                    
                    <div class="row">
                    <div class="col-md-3">
                    <!--  -->
                    </div>
                    <div class="col-md-6 border rounded p-4">
                        <?php
                        if(isset($_SESSION["emodattivita"])){
                            echo "<div class='alert alert-danger mt-3' role='alert'><b>Errore:</b> ".$_SESSION["emodattivita"]."</div>";
                            unset($_SESSION["emodattivita"]);
                        }?>
                        <br><br>
                        <form action="modattivita.php" method="post" class="justify-content-center" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome dell'attività</label>
                                <div class="input-group">
                                    <input placeholder="<?php echo $attivita["NomeAtt"];?>" name="nome"  type="text" class="form-control" id="nome" aria-describedby="">
                                </div>
                            </div>
                            
                            <label for="descrizione" class="form-label">Descrizione</label>
                            <div class="input-grou mb-3">
                                <textarea placeholder="<?php echo $attivita["Descrizione"];?>"name="descrizione"  class="form-control" id="descrizione" aria-label="With textarea"></textarea>
                            </div>
            
                            <label for="costo" class="form-label">Costo mensile</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">€</span>
                                <input placeholder="<?php echo $attivita["CostoMensile"];?>"name="costo"  type="text" id="costo" class="form-control" aria-label="">
                            </div>
            

                            <label for="imgfile" class="form-label">Immagine</label>
                            <div class="input-group">
                                <input placeholder="<?php echo $attivita["imgfile"];?>"type="file" class="form-control" id="imgfile" name="imgfile">
                            </div>
                            <div id="emailHelp" class="form-text">I formati consentiti sono JPG, JPEG e PNG</div>

                            <h3 class="mt-3">Modifica animatori:</h3>
                            <div class="d-flex flex-column gap-2">
                            <?php
                            $sql = "SELECT id,Cognome,Nome FROM persone";
                            $preparata = $connessione->prepare($sql);
                            $preparata->execute();
                            $animatori = $preparata->fetchAll();
                            $sql2 = "SELECT Estidpers FROM anima WHERE Estidatt=:idatt";
                            $preparata2 = $connessione->prepare($sql2);
                            $preparata2->execute([":idatt"=>$_GET["attivita"]]);
                            $selanimatori = $preparata2->fetchAll();
                            foreach($animatori as $animatore){
                                echo "<div class='input-group'>
                                    <div class='input-group-text'>";
                                        $check=false;
                                        foreach($selanimatori as $sel){
                                            if($sel["Estidpers"]==$animatore["id"]){
                                                echo "<input class='form-check-input mt-0' type='checkbox' name=animatore[] id='check".$animatore["id"]."'value=".$animatore["id"]." checked>";
                                                $check=true;
                                            }
                                        }
                                        if(!$check){
                                            echo "<input class='form-check-input mt-0' type='checkbox' name=animatore[] id='check".$animatore["id"]."'value=".$animatore["id"].">";
                                        }
                                echo "</div>
                                    <label class='input-group-text' for='check".$animatore["id"]."'>".$animatore["Cognome"]." ".$animatore["Nome"]." </label>
                                    </div>";
                            }
                            ?>
                            </div>
                            
                            <div class="d-flex justify-content-center mt-4 mb-5">
                                <button class="btn btn-outline-primary">Modifica attività</button>
                            </div>
                        </form>
                    </div>
                        
                    <div class="col-md-3">
                        
                    </div>
                </div>
            <?php 
                }
                else{
                    echo "<center><div class='alert alert-danger w-50 mt-3' role='alert'><b>Errore:</b> Attività non trovata.</div></center>";
                }
            } ?>
        </div>
        <br><br>
        <?php 
        if($_SESSION["modattivita"]){
            echo "<div class='alert alert-success mt-3' role='alert'><center><b>Attività modificata correttamente.</b></center></div>";
            $_SESSION["modattivita"]=false;    
        }
        ?>
        <br><br><br><br><br><br>
        <script src="bootstrap/bootstrapdistr/js/bootstrap.min.js"></script>
    </body>
</html>

