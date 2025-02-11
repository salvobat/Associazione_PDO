<?php
session_id(id: "associazione");
session_start();
require_once("checklogged.php");
require_once("connessione.php");
unset($_SESSION["emodattivita"]);
$_SESSION["modattivita"]=false;

$sql = "SELECT * FROM attivita";
$preparata = $connessione->prepare($sql);
$preparata->execute();
$attivita = $preparata->fetchAll();


//CODICE PER VERIFICARE LA VALIDITÀ DEGLI ALTRI DATI INSERITI DALL'UTENTE

//NOME
$modnome=true;
if(isset($_POST["nome"]) && $_POST["nome"]!="" && !empty($_POST["nome"])){
    foreach($attivita as $a){
        if($_POST["nome"]==$a["NomeAtt"]){
            $_SESSION["emodattivita"] .= "Nome dell'attivita già esistente";
        }
    }
}
else{$modnome=false;}

//DESCRIZIONE
$moddescrizione=true;
if(isset($_POST["descrizione"]) && $_POST["descrizione"]!="" && !empty($_POST["descrizione"])){

}
else{$moddescrizione=false;}

//COSTO MENSILE
$modcostomensile=true;
if(isset($_POST["costo"]) && $_POST["costo"]!=""){ 
    if($_POST["costo"]<0 || !is_float($_POST["costo"])){ //ctype_digit restituisce true se la stringa è composta solo da numeri interi positivi
        $_SESSION["emodattivita"] .= " Costo mensile non valido. ";
    }
}
else{$modcostomensile=false;}



//CODICE PER VERIFICARE LA VALIDITÀ DELL'IMMAGINE CARICATA DALL'UTENTE
$modimage=true;
if(!isset($_FILES["imgfile"]) || $_FILES["imgfile"]["name"]==""){
    $modimage=false;
}
else{
    $dir = "img/";
    $file = $dir . basename($_FILES["imgfile"]["name"]);
    $imageFileType = strtolower(pathinfo($file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["imgfile"]["tmp_name"]);
    if(!$check) {
        $_SESSION["emodattivita"] .= " Il file non è un'immagine. ";
    }
    else if (file_exists($file)){
        $_SESSION["emodattivita"] .= " L'immagine con questo nome esiste già. ";
    }
    else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $_SESSION["emodattivita"] .= " Il file caricato non è un'immagine. ";
    }
    else if(!isset($_SESSION["emodattivita"])){ //CARICO L'IMMAGINE
        if (!move_uploaded_file($_FILES["imgfile"]["tmp_name"], $file)) {
            $_SESSION["emodattivita"] .= " Errore sconosciuto nel caricamento dell'immagine. ";
        }
    }   
}
// Fine codice per verificare la validità dell'immagine caricata dall'utente
//CHECK FINALE
if(isset($_SESSION["emodattivita"])){
    header("Location: modificaattivita.php?attivita=".$_SESSION["idatt"]);
    exit;
}




//-------------------------------------------------------------
//CODICE PER VERIFICA LA VALIDITÀ DEI DATI INSERITI DALL'UTENTE
//-------------------------------------------------------------

//-------------------------------------------------------------
//FINE CODICE PER VERIFICARE LA VALIDITÀ DEGLI ALTRI DATI INSERITI DALL'UTENTE
//-------------------------------------------------------------

$sql = 'SELECT * FROM attivita WHERE idatt=:idatt';
$preparata = $connessione->prepare($sql);
$preparata->execute([':idatt'=> $_SESSION["idatt"]]);
$attivita = $preparata->fetch();



if($modnome){$nome=$_POST["nome"];}
else{$nome=$attivita["NomeAtt"];}

if($moddescrizione){$descrizione=$_POST["descrizione"];}
else{$descrizione=$attivita["Descrizione"];}

if($modcostomensile){$costomensile=$_POST["costo"];}
else{$costomensile=$attivita["CostoMensile"];}

if($modimage){$imgfile=$_FILES["imgfile"]["name"];}
else{$imgfile=$attivita["imgfile"];}

$sql1 = 'UPDATE attivita SET
    NomeAtt = "'.$nome.'", 
    Descrizione = "'.$descrizione.'", 
    CostoMensile = "'.$costomensile.'", 
    imgfile = "'.$imgfile.'" 
    WHERE idatt = '.$_SESSION["idatt"];
$preparata = $connessione->prepare($sql1);
$preparata->execute();

//ANIMATORI
//$modanimatori=true;
$selanimatori=$_POST["animatore"];
$sql2 = "SELECT Estidpers FROM anima WHERE Estidatt=:idatt";
$preparata2 = $connessione->prepare($sql2);
$preparata2->execute([":idatt"=>$_SESSION["idatt"]]);
$oldanimatori = $preparata2->fetchAll();
// foreach ($oldanimatori as $old){
//     echo $old["Estidpers"]."\n";
// }
foreach($selanimatori as $sel){
    $check=false;
    foreach($oldanimatori as $old){
        if($sel==$old["Estidpers"]){
            $check=true;
        }
    }
    if(!$check){
        $sql3 = "INSERT INTO anima (Estidatt, Estidpers) VALUES (".$_SESSION["idatt"].", ".$sel.")";
        $preparata3 = $connessione->prepare($sql3);
        $preparata3->execute();
    }
}
$preparata2->execute([":idatt"=>$_SESSION["idatt"]]);
$newanimatori = $preparata2->fetchAll();
foreach($newanimatori as $new){
    $check=false;
    foreach($selanimatori as $sel){
        if($sel==$new["Estidpers"]){
            $check=true;
        }
    }
    if(!$check){
        $sql4 = "DELETE FROM anima WHERE Estidatt=".$_SESSION["idatt"]." AND Estidpers=".$new["Estidpers"];
        $preparata4 = $connessione->prepare($sql4);
        $preparata4->execute();
    }
}


$_SESSION["modattivita"]=true;
header("Location: modificaattivita.php");
//DEBUG
// echo $_SESSION["modattivita"];
// echo $nome.$descrizione.$costomensile.$imgfile.$_SESSION["idatt"];
// echo $_POST["nome"].$modnome;
// if(!$modnome){echo "PORCO";}




$connessione = null;