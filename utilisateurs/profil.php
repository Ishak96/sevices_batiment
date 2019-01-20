<?php
session_start ();
    require_once"../classes/Data_Base.class.php";
    require_once"../include/connexion.conf.inc.php";
    require_once"../include/fonctions.inc.php";
    
    if(isset($_GET['id']))
    {
        $name = explode("_",$_GET['id'])[0];
        $sur_name = explode("_",$_GET['id'])[1];
    }

    $db = new Data_Base(SGBD,HOST,DBNAME,USER,PASSWORD);
    $pdo = $db->get_PDO();

    $query = $db->query("SELECT pic_dest,nom,prenom,no_util FROM utilisateurs WHERE mail ='".$_SESSION['username']."'");
    $row = $query->fetch();

    $query1 = $db->query("SELECT no_util,adresse,telephone,mail,pic_dest,date_up,metier,diplome,annees_exp,note FROM utilisateurs INNER JOIN                           professionnels ON utilisateurs.no_util=professionnels.no_util_pro
                                   WHERE nom ='".$name."' AND prenom ='".$sur_name."'");
    $row1 = $query1->fetch();

    $query2 = $pdo->prepare("INSERT INTO contacter VALUES (?,?,?,?,?,?)");

    if (isset($_POST['valid'])) 
    {
        $date_cont=$_POST['date_cont'];
        $motif=$_POST['motif'];

        $params=array(
                   $row1['no_util'],
                   $row['no_util'],
                   date("d-m-Y"),
                   $date_cont,
                   $motif,
                   0
        );
        $query2->execute($params);
    }
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
    <title>Profil</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../style/styleProfile.css">
    <style type="text/css">
        textarea
        {
            margin: auto;
            resize: none;
            border: 3px solid;
            height: 10%;
            width: 20%;
            margin-left: 10%;
            margin-top: 2%;
        }
        input
    </style>
    </head>
    <body>
    <div class="header1">
        <div id="img3" class="header1"><img src="../images/brico.PNG" id="img3"/></div>
        <div id="searcharea" class="header1"><input placeholder="search here..." type="text" id="searchbox"/></div>
        <div id="profilearea" class="header1">
        <?php
            if ($row[0]!=NULL) 
                echo "<a href='espace_client.php'><img src='".$row[0]."' height='30'/></div></a>";
            else
                echo "<a href='espace_client.php'><img src='../images/profile.PNG' height='30'/></div></a>";
        ?>
        <div id="profilearea1" class="header1"> <?php echo "<p>".$row[1]." ".$row[2]."</p>";?></div>
        <div id="profilearea2" class="header1">
            <ul>
                <li>
                    <a href="https://servicesbatiment.wordpress.com">Accueil</a>
                    <a href="../authentification/deconnexion.php">Déconnexion</a>
                </li>
            </ul>
        </div>
    </div>
    </div>
    <div style="width: 100%; height: 40%; margin-top: 5%;">
        <section class="sec_info">
        <div>
            <?php
                if ($row1['pic_dest']==NULL) 
                    echo "<img style='margin-left: 20%; margin-top: 2%; border: 3px solid;' src='../images/default_prof.JPEG' height='150px'/>\n";
                else
                    echo "<img style='margin-left: 20%; margin-top: 2%; border: 3px solid;' src='".$row1['pic_dest']."' height='150px'/>\n";
            ?>
            <div>
                <?php 
                     if ($row1['annees_exp']==0){
                         $annee="débutant";
                     }
                     else{
                        if ($row1['annees_exp']==1)
                            $annee="une année d'expérience";
                        else
                            $annee="années d'expérience: ".$row1['annees_exp'];
                     }
                    echo "<p>".$name."
                    ".$sur_name."</br>
                    ".$row1['adresse']."</br>"
                     ."telephone: ".$row1['telephone']."</br>"
                     ."mail: ".$row1['mail']."</br>"
                     .$annee."</br>"
                     ."métier :".$row1['metier']."</br>"
                     ."diplome :".$row1['diplome'];
                ?>
            </div>
        </div>  
        </section>
        <section class="blockRight" style="margin-top: 3%; margin-right: 1%;  width: 75%;">
            <p>laissez un motif et une date pour le randez-vous.</p>
            <p style="color: red;">prenez soin de bien vérifier les jours ou le professionnel est libre pour éviter que votre rendez-vous ne soit pas annulé.</p>
            <p style="color: red;">préciser bien le motif de votre rendez-vous pour que le professionnel sache ce que vous avez comme problème.</p>
            <div style="margin-right: 5%;">
                <form action=<?php echo "profil.php?id=".$_GET['id'];?> method="post">
                    <textarea maxlength="40" name="motif" required="required" placeholder="motif du rendez-vous..."></textarea>
                    <div style="margin-top: 1%;"><input style="margin-left: 10%;" type="date" name="date_cont" required="required"></div>
                    <input style="margin-top: 1%; margin-left: 10%; margin-bottom: 1%;" type="submit" name="valid">
                </form>
            </div>
        </section>
    </div>
    <div style="text-align: center; margin-top: 30%; margin-bottom: 44%;">
        <section class="blockResult" style="width: 75%; margin-right: 10px;">
            <p style="color: red; margin-top: 2%;">liste des jours déjà prises par d'autres personnes avec ce professionnel:</p>
            <?php
                echo $db->ParagrapheQuery("SELECT nom,prenom,rendez_vous,valider FROM utilisateurs INNER JOIN contacter 
                                ON utilisateurs.no_util=contacter.no_util_cli
                                WHERE no_util_pro='".$row1['no_util']."'");
            ?>
        </section>
    </div>
    </body>
</html>