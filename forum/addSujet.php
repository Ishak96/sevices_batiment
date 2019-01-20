<?php session_start();


require_once"../classes/Data_Base.class.php";
require_once"../include/connexion.conf.inc.php";
include_once '../classes/addSujet.class.php';

$bdd = new Data_Base(SGBD,HOST,DBNAME,USER,PASSWORD);

if(isset($_POST['name']) AND isset($_POST['sujet'])){
    
    $addSujet = new addSujet($_POST['name'],$_POST['sujet'],$_POST['categorie']);
    $verif = $addSujet->verif();
    if($verif == "ok"){
        if($addSujet->insert()){
            header('Location: forum.php?sujet='.$_POST['name']);
        }
    }
    else {/*Si on a une erreur*/
        $erreur = $verif;
    }
    
}
?>
<!DOCTYPE html>
<head>
    <meta charset='utf-8' />
    <title>Mon super forum !</title>
    

       <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../style/styleforum.css">
<head>
<body>
     <div class="top-menu" >
       <ul>
            <li><a href="https://servicesbatiment.wordpress.com"> Acceuil </a></li>
            <li><a href="./connexion.php">Connexion</a></li>
            <li><a href="./inscription.php">Inscription</a></li>
            <li><a href="index.php">Forum</a></li>
       </ul>
    </div>
 <h1>Ajouter un sujet</h1>
    
            <div id="Cforum">
                <?php  echo 'Bienvenue : '.$_SESSION['username'].' :) - <a href="deconnexion.php">Deconnexion</a> '; ?>
                
                <form method="post" action="addSujet.php?categorie=<?php echo $_GET['categorie']; ?>">
                    <p>
                        <br><input type="text" name="name" placeholder="Nom du sujet..." required/><br>
                        <textarea name="sujet" placeholder="Contenu du sujet..."></textarea><br>
                        <input type="hidden" value="<?php echo $_GET['categorie']; ?>" name="categorie" />
                        <input type="submit" value="Ajouter le sujet" />
                        <?php 
                        if(isset($erreur)){
                            echo $erreur;
                        }
                        ?>
                    </p>
                </form>
            </div>
</body>
</html>
