<?php session_start();

include_once '../classes/addPost.class.php';

    require_once"../classes/Data_Base.class.php";
    require_once"../include/connexion.conf.inc.php";
   

    $bdd = new Data_Base(SGBD,HOST,DBNAME,USER,PASSWORD);


if(!isset($_SESSION['username'])){

    header('Location: ../authentification/inscription.php');
}
else {
    
    if(isset($_POST['name']) AND isset($_POST['sujet'])){
    
    $addPost = new addPost($_POST['name'],$_POST['sujet']);
    $verif = $addPost->verif();
    if($verif == "ok"){
        if($addPost->insert()){
            
        }
    }
    else {/*Si on a une erreur*/
        $erreur = $verif;
    }
    
}
$query = $bdd->query("SELECT nom,prenom,adresse,mail,telephone,no_util,pic_dest,date_up 
                                FROM utilisateurs WHERE mail ='".$_SESSION['username']."'");
$row = $query->fetch();
    
?>
<!DOCTYPE html>
<head>
      <title>Connexion</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../style/styleforum.css">
        <link rel="stylesheet" type="text/css" href="../style/styleProfile.css">
<head>
<body>
    <div class="header1">
        <div id="img3" class="header1"><img src="../images/brico.PNG" id="img3"/></div>
        <div id="searcharea" class="header1"><input placeholder="search here..." type="text" id="searchbox"/></div>
        <div id="profilearea" class="header1">
        <?php
            if ($row[0]!=NULL) 
                echo "<a href='../utilisateurs/espace_client.php'><img src='".$row['pic_dest']."' height='30'/></div></a>";
            else
                echo "<a href='../utilisateurs/espace_client.php'><img src='../images/profile.PNG' height='30'/></div></a>";
        ?>
        <div id="profilearea1" class="header1"> <?php echo "<p>".$row['nom']." ".$row['prenom']."</p>";?></div>
        <div id="profilearea2" class="header1">
            <ul>
                <li>
                    <a href="../services/materiel.php">Location</a>
                    <a href="https://servicesbatiment.wordpress.com">Accueil</a>
                    <a href="../authentification/deconnexion.php">Déconnexion</a>
                </li>
            </ul>
        </div>
    </div>
 <h1>Forum</h1>
    
            <div id="Cforum">
                <?php 
                
                 echo 'Bienvenue : '.$_SESSION['username'].' - <a href="../authentification/deconnexion.php">Deconnexion</a> ';
                if(isset($_GET['categorie'])){ /*SI on est dans une categorie*/
                    $_GET['categorie'] = htmlspecialchars($_GET['categorie']);
                    ?>
                    <div class="categories">
                      <h1> <?php echo $_GET['categorie']; ?> </h1>
                    </div>
                <a href="addSujet.php?categorie=<?php echo $_GET['categorie']; ?>">Ajouter un sujet</a>
                <?php 
                $requete = $bdd->prepare('SELECT * FROM sujet WHERE categorie = :categorie ');
                $requete->execute(array('categorie'=>$_GET['categorie']));
                while($reponse = $requete->fetch()){
                    ?>
                     <div class="categories">
                         <a href="forum.php?sujet=<?php echo $reponse['name'] ?>"><h1><?php echo $reponse['name'] ?></h1></a>
                    </div>
                    <?php
                }
                ?>
                
                    
                    <?php
                }
                
                else if(isset($_GET['sujet'])){ /*SI on est dans une categorie*/
                    $_GET['sujet'] = htmlspecialchars($_GET['sujet']);
                    ?>
                    <div class="categories">
                      <h1><?php echo $_GET['sujet']; ?></h1>
                    </div>
                
                <?php 
                $query = $bdd->prepare('SELECT nom,prenom,contenu FROM utilisateurs INNER JOIN 
                    postsujet ON utilisateurs.mail=postsujet.propri WHERE sujet = ?');
                $query->execute(array($_GET['sujet']));
                while($row = $query->fetch()){
                    ?>
                <div class="post">
                    <?php 
                            echo $row['nom'].'_'.$row['prenom'].': <br>';
                     
                            echo $row['contenu'];
                    ?>
                 </div> 
                <?php  
                }
                ?>
                
                 <form method="post" action="forum.php?sujet=<?php echo $_GET['sujet']; ?>">
                        <textarea name="sujet" placeholder="Votre message..." ></textarea>
                        <input type="hidden" name="name" value="<?php echo $_GET['sujet']; ?>" />
                        <input type="submit" value="Ajouter à la conversation" />
                        <?php 
                        if(isset($erreur)){
                            echo $erreur;
                        }
                        ?>
                    </form>
                <?php
                }
                else { /*Si on est sur la page normal*/
                    
                       
                
                        $requete = $bdd->query('SELECT * FROM categories');
                        while($reponse = $requete->fetch()){
                        ?>
                            <div class="categories">
                                <a href="forum.php?categorie=<?php echo $reponse['name']; ?>"><?php echo $reponse['name']; ?></a>
                              </div>
                
                    <?php 
                    }
                    
                }
                 ?>
                
                
                
                
                
            </div>
</body>
</html>
    <?php
}
?>

    
