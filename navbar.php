<?php 

require_once("checklogged.php");

?>
<nav class="navbar navbar-expand bg-body-secondary navbar-fixed-top border-bottom border-black">
            <div class="container">
                <div class="container-fluid">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <a class="navbar-brand" href="home.php"><b>Bentornato</b> <?php  echo($_SESSION["username"]) ?></a>
                        
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a href="home.php" id="Home" class="nav-link">Home</a>
                            </li>
                            <?php if($_SESSION["livello"]==0){?>
                                <li class="nav-item">
                                    <a href="aggiungiattivita.php" id="Aggiungi attivita" class="nav-link ">Aggiungi attivita</a>
                                </li>
                                <li class="nav-item">
                                    <a href="aggiungimarca.php" id="Aggiungi marca" class="nav-link ">Aggiungi marca</a>
                                </li>
                            <?php }?>
                        </ul>
                        <form method="post" class="d-flex">
                            <div class="nav-item">
                                <button class="btn btn-outline-danger" type="submit" name="esci">Esci</button>
                            </div>
                            <?php
                                if(isset($_POST["esci"])){
                                    $_POST = array();
                                    $_SESSION["logged"]=0;
                                    session_destroy();
                                    $_POST = array();
                                    header("Location: index.php");
                                }
                            ?>
                        </form>
                    </div>
                </div>



                
            </div>
</nav>
<script>
    var currentpage = "<?php echo($_SESSION["currentpage"]); ?>";
    var navlinks = document.getElementsByClassName("nav-link");
    for(var i=0;i<navlinks.length;i++){
        if(navlinks[i].id==currentpage){
            navlinks[i].classList.add("active");
        }
        else{
            navlinks[i].classList.remove("active");
        }
    }
</script>