<?php
if (isset($_SESSION["logged"]) ) {
    if($_SESSION["logged"]==0){
        header("Location: index.php");
        exit;
    }
}
else{
    header("Location: index.php");
    exit;
}
?>