<?php
session_id("associazione");
session_start();
require_once("connessione.php");
if(isset($_SESSION["logged"])){
    if($_SESSION["logged"]==1){ //yes
        $location= "Location: home.php";
        header($location);
        exit;
    }
} 


$preparata = $connessione->prepare("SELECT user,password,llivello FROM utenti");
$preparata->execute();
$utenti = $preparata->fetchAll();

//unset($_SESSION["loginerror"]);
if(isset($_POST["username"]) && isset($_POST["password"])){
    foreach($utenti as $utente){
        if($utente["user"]==$_POST["username"] && $utente["password"]==$_POST["password"]){
            $_SESSION["logged"]=1;
            $_SESSION["livello"]=$utente["livello"];
            $_SESSION["username"]=$utente["user"];
            header("Location: index.php");
            exit;
        }
    }
    $_SESSION["loginerror"]=1;    
    header("Location: index.php");
    exit;
}
$connessione = null;