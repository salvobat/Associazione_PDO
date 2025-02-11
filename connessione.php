<?php
    $host = "localhost";
    $db = "associazione";
    $user = "root";
    $password = "";
    try {
        $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
        $connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "<tr><td colspan='3' class='text-center text-danger'>Errore nella gestione del database: " . $e->getMessage() . "</td></tr>";
    }
?>