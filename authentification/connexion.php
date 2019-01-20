<?php
    session_start();

    require_once"../classes/Data_Base.class.php";
    require_once"../include/connexion.conf.inc.php";

    $db = new Data_Base(SGBD,HOST,DBNAME,USER,PASSWORD);

    $authenticate_error = null;

    function authenticate()
    {
        global $db,$authenticate_error;
        $query = $db->query("SELECT no_util,mot_de_passe,confirme FROM utilisateurs WHERE mail ='".$_SESSION['username']."'");
        if ($query->rowCount()>0){
            $row = $query->fetch();
            if($row[2]==1){
                if(password_verify($_SESSION['password'],$row[1])){
                    if(preg_match("#^cli_[a-zA-Z0-9]#",$row[0])){
                        $_SESSION['userspace']="espace_client.php";
                        header('location: ../utilisateurs/espace_client.php');
                    }elseif(preg_match("#^pro_[a-zA-Z0-9]#",$row[0])){
                        $_SESSION['userspace']="espace_pro.php";
                        header('location: ../utilisateurs/espace_pro.php');
                    }                 
                    exit();
                }else{
                    $authenticate_error = "<p class='error_msg'>Nom d'utilisateur ou mot de passe incorrect.</p>";
                }            
            }else{
                $authenticate_error = "<p class='error_msg'>Votre compte n'est pas encore confirmé, merci de consulter votre mail.</p>";
            }
        }else{
            $authenticate_error = "<p class='error_msg'>Nom d'utilisateur ou mot de passe incorrect.</p>";
        }
    }

    if(isset($_POST['LOGIN']) && !empty($_POST['username']) && !empty($_POST['password'])){
        $_SESSION['username']=$_POST['username'];
        $_SESSION['password']=$_POST['password'];
        authenticate();
    }elseif(isset($_SESSION['username']) && isset($_SESSION['password'])){
        authenticate();
    }  
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Connexion</title>
        <link rel="stylesheet" href="../bootstrap-4.0.0-dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../style/main_style.css">
    </head>
    <body>
        <header>
            <h1>Services-Batiment.com</h1>
            <h2>Bienvenue</h2>
        </header>
        <div class="wrapper" style="display: flex;">
            <nav id="sidebar">
                <a href="https://servicesbatiment.wordpress.com">Accueil</a>
                <a href="./inscription.php">Inscription</a>
                <a href="http://servicesbatiment.wordpress.com/a-propos/">A propos</a>
            </nav>
            <div id="content">
                <button type="button"  class="btn" id="menu_btn">
                    <span>MENU</span>
                </button> 
                <div id="main">

                <form id="connect_form" action="./connexion.php" method="post"> 
                        <h2>Connexion</h2> 
                        <label><b>Adresse mail:</b></label>
                        <input type="text" placeholder="Entrez votre mail" id="mail" name="username" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" maxlength="30" required/><br/>
                        <label><b>Mot de passe:</b></label>
                        <input type="password" placeholder="Entrer votre mot de passe" name="password" minlength="5" maxlength="20" required>
                        <?php
                            if($authenticate_error != null){
                                echo($authenticate_error);
                            }
                        ?>
                        <input type="submit" id='submit' name="LOGIN" value='se connecter' >
                </form>
                </div>  
            </div>            
        </div>

        <footer>            
            <p>Site créé par HACHOUD Rassem & METIDJI Fares</p>
        </footer>

        <script src="../js/jquery-1.12.1.js"></script>
        <script type="text/javascript">
             $(document).ready(function () {
                 $('#menu_btn').on('click', function () {
                     $('#sidebar').toggleClass('active');
                 });
             });
        </script>
        <?php
            $db->close_connection();
        ?>
    </body>
</html>
