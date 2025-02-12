<?php
session_id("associazione");
session_start();
require_once("checklogged.php");
require_once("connessione.php");
unset($_SESSION["eaddattivita"]);
$_SESSION["addattivita"]=false;


if(isset($_SESSION["livello"])){
    if($_SESSION["livello"]!=0){
        header("Location: home.php");
        exit;
    }
}
if(!isset($_SESSION["addattivita"])){
    $_SESSION["addattivita"]=false;
}


//CODICE PER VERIFICARE LA VALIDITÀ DEGLI ALTRI DATI INSERITI DALL'UTENTE

//Nome
if(!isset($_POST["nome"]) || $_POST["nome"]==""){
    $_SESSION["eaddattivita"] .= " Nome attività non valido. ";
}
else {
    $sql = "SELECT nomeAtt FROM attivita;";
    $preparata = $connessione->prepare($sql);
    $preparata->execute();
    $attivita = $preparata->fetchAll();

    foreach ($attivita as $att) {
        if($att["nomeAtt"] == $_POST["nome"]) {
            $_SESSION["eaddattivita"] .= " Nome attività non valido o già in uso. ";
        }
    }
}

//COSTO
if(!isset($_POST["costo"]) || $_POST["costo"]=="" || !ctype_digit($_POST["costo"])){ //ctype_digit restituisce true se la stringa è composta solo da numeri interi positivi
    $_SESSION["eaddattivita"] .= " Costo non valido. ";
}

//DESCRIZIONE
if(!isset($_POST["descrizione"]) || $_POST["descrizione"]==""){
    $_SESSION["eaddattivita"] .= " Descrizione non valida. ";
}

// //IMMAGINE    
//CODICE PER VERIFICARE LA VALIDITÀ DELL'IMMAGINE CARICATA DALL'UTENTE
if(!isset($_FILES["imgfile"]) || $_FILES["imgfile"]["name"]==""){
    $_SESSION["eaddattivita"] .= " Nessuna immagine caricata. ";
}
else{
    $dir = "img/";
    $file = $dir . basename($_FILES["imgfile"]["name"]);
    $imageFileType = strtolower(pathinfo($file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["imgfile"]["tmp_name"]);
    if(!$check) {
        $_SESSION["eaddattivita"] .= " Il file non è un'immagine. ";
    }
    else if (file_exists($file)){
        $_SESSION["eaddattivita"] .= " L'immagine con questo nome esiste già. ";
    }
    else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $_SESSION["eaddattivita"] .= " Il file caricato non è un'immagine. ";
    }
    else if(!isset($_SESSION["eaddattivita"])){
        if (!move_uploaded_file($_FILES["imgfile"]["tmp_name"], $file)) {
            $_SESSION["eaddattivita"] .= " Errore sconosciuto nel caricamento dell'immagine. ";
        }
    }   //TO-DO RIPRISTINARE QUESTO CODICE
}
// Fine codice per verificare la validità dell'immagine caricata dall'utente


//CHECK FINALE
if(isset($_SESSION["eaddattivita"])){
    header("Location: aggiungiattivita.php");
    exit;
}

//-------------------------------------------------------------
//FINE CODICE PER VERIFICARE LA VALIDITÀ DEGLI ALTRI DATI INSERITI DALL'UTENTE
//-------------------------------------------------------------



$sql1 = 'INSERT INTO attivita (idatt, NomeAtt, CostoMensile, Descrizione, imgfile) 
        VALUES (NULL, :nome, :costo, :descrizione, :imgfile)';

$preparata = $connessione->prepare($sql1);
$preparata->execute([
    ':nome' => $_POST["nome"],
    ':costo' => $_POST["costo"],
    ':descrizione' => $_POST["descrizione"],
    ':imgfile' => $_FILES["imgfile"]["name"] // Qui aggiungi l'estensione correttamente
]);

//ANIMATORI
$sql2 = "SELECT DISTINCT idatt FROM attivita WHERE NomeAtt=:nome";
$preparata2 = $connessione->prepare($sql2);
$preparata2->execute([":nome"=>$_POST["nome"]]);
$idatt = $preparata2->fetch();
foreach($idatt as $id){
    $idatt = $id;
}
$selanimatori=$_POST["animatore"];
foreach($selanimatori as $sel){
    $sql3 = "INSERT INTO anima (Estidatt, Estidpers) VALUES (:idatt, :sel)";
    $preparata3 = $connessione->prepare($sql3);
    $preparata3->execute([":idatt"=>$idatt, ":sel"=>$sel]);
}

$_SESSION["addattivita"]=true;
header("Location: aggiungiattivita.php");


$connessione = null;