<?php
session_start();
if(isset($_POST['username']) && isset($_POST['password']))
{
    // connexion à la base de données
    include_once "config.php";

    $username = $_POST['username']; 
    $password = $_POST['password'];
    
    if($username !== "" && $password !== "")
    {
      $rechercher= $mysqlClient->prepare('SELECT COUNT(*) as nb FROM utilisateur WHERE nom_utilisateur = ? and mot_de_passe = ?');
      $rechercher->execute(array($username, $password));
      $trouver=$rechercher->fetch();
      if($trouver['nb'] == 0){
      header('Location: loginuser.html'); // utilisateur ou mot de passe incorrect
      }else{
         $_SESSION['username'] = $username;
         header('Location: MenuVisu.php');
      }
    }
}
else
{
   header('Location: loginuser.html');
}
mysqli_close($db); // fermer la connexion
?>