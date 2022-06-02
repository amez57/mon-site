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
      $rechercher= $mysqlClient->prepare('SELECT COUNT(*) as nb FROM superadministrateur WHERE nom_sadmin = ? and mdp_sadmin = ?');
      $rechercher->execute(array($username, $password));
      $trouver=$rechercher->fetch();
      if($trouver['nb'] == 0){
      header('Location: loginsupadmin.html'); // utilisateur ou mot de passe incorrect
      }else{
         $_SESSION['username'] = $username;
         header('Location: MenuALL.php');
      }
    }
}
else
{
   header('Location: index.html');
}
mysqli_close($db); // fermer la connexion
?>