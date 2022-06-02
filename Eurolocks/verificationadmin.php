<?php
session_start();
if(isset($_POST['username']) && isset($_POST['password'])) //On vérifie si il y a bien un nom et mdp qui est récupéré
{
    // connexion à la base de données
    include_once "config.php";

    // on récupère le nom et le mot de passe du formulaire de la page loginadmin dans des variables
    $username = $_POST['username']; 
    $password = $_POST['password'];
    
    if($username !== "" && $password !== "")
    {
       //verification si le nom et le mot de passe sont dans la table de la base de données
      $rechercher= $mysqlClient->prepare('SELECT COUNT(*) as nb FROM administrateur WHERE nom_admin = ? and mdp_admin = ?');
      $rechercher->execute(array($username, $password));
      $trouver=$rechercher->fetch(); //recherche dans toute la table
      if($trouver['nb'] == 0){ //si il trouve le nom et le mot de passe
      header('Location: loginadmin.html'); // utilisateur ou mot de passe incorrect
      }else{
         $_SESSION['username'] = $username; //récupération du nom dans la variable de session
         header('Location: MenuCRUD.php');// l'utilisateur est inscrit dans la bdd donc on l'envoie sur le bon menu
      }
    }
}
else
{
   header('Location: index.html');
}
mysqli_close($db); // fermer la connexion
?>