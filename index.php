<!-- PAGINA DI LOGIN -->

<?php
    //require_once("connessione.php");
    //require('head_script.php');
    //session_start();
    require_once("auth.php");
    //echo print_r($users);
?>
<!DOCTYPE HTML>
<html>
    <head>
    <link href="bootstrap/bootstrapdistr/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container">
            <div class="loginform row justify-content-center mt-5">
                <div class="loginform col-md-5 p-4 rounded border">
                    <h1 class="text-center display-1">LOGIN</h1>
                    <form action="auth.php" method="post" class="justify-content-center">
                        <div class="mt-5 mb-3">
                            <label for="username" class="formlabel">Username</label>
                            <input type="text" id="username" name="username" class="form-control">
                        </div>
                        <div class="mt-1 mb-5">
                            <label for="password" class="formlabel">Password</label>
                            <input type="password" id="password" name="password" class="form-control">
                        </div>
                        <?php 
                            if(isset($_SESSION["loginerror"])){
                                ?>
                                <div class="errore alert alert-danger" role="alert">
                                Username o Password errati, riprova.
                                </div>
                                <?php
                                unset($_SESSION["loginerror"]);
                            }
                        ?>
                        <div class="d-flex justify-content-center mt-1 mb-5">
                            <button class="btn btn-primary">Entra</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <script src="bootstrap/bootstrapdistr/js/bootstrap.min.js"></script>
    </body>
</html>