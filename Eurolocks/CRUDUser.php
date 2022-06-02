<?php
include_once "config.php";

$action=$_GET['action'];

 $id = $_GET['ID'];
 $nom= $_GET['nom_utilisateur'];
 $mdp= $_GET['mot_de_passe'];


if($action=='affiche') {Affiche();}

if($action=='modif')
{
$requete="UPDATE utilisateur SET nom_utilisateur=".$nom.", mot_de_passe=".$mdp." WHERE id=".$id;
$sth = $mysqlClient->prepare($requete);
$sth->execute();
Affiche();
}
if($action=='suppr')
{

if($id=='')return;
$requete="DELETE FROM utilisateur WHERE id=".$id;
$sth = $mysqlClient->prepare($requete);
$sth->execute();
Affiche();
}

if($action=='ajout')
{
  $rechercher= $mysqlClient->prepare('SELECT * FROM utilisateur WHERE nom_utilisateur=?');
  $rechercher->execute(array($nom));
  $trouver=$rechercher->rowCount();
  if($trouver>0){
    echo "Cet utilisateur existe déjà";
  }else{
    $sqlQuery = "INSERT INTO utilisateur (nom_utilisateur, mot_de_passe) VALUES ('$nom', '$mdp')";
    $sth = $mysqlClient->prepare($sqlQuery);
    $sth->execute();
  }
  Affiche();
}

function Affiche()
{
    global $mysqlClient;


          

  echo"<table border=1 width='100%'>";
// affiche les noms des colonnes
  $titre ="<tr>";
$titre= $titre.'<th>Utilisateur</th><th>Mdp</th>'; 
$titre = $titre."</tr>";
echo $titre;
$sqlQuery = 'SELECT * FROM utilisateur ORDER BY nom_utilisateur';
$sth = $mysqlClient->prepare($sqlQuery);
$sth->execute();
// affiche les données des colonnes  
$nombreColonnes = $sth->columnCount();  
    while ($row = $sth->fetch(PDO::FETCH_NUM)) 
          { 
           $ligne ="";
           
              $ligne ="<tr><td><input type=text ID='tuser".$row[0]."'  value='".$row[1]."'></td> <td><input type=text ID='tmdpuser".$row[0]."'  value='".$row[2]."'></td> <td><img src='img\modif_petit.png' width=40 onclick='modifUser(".$row[0].")' ><img src='img\del_petit.png' width=40 onclick='supprUser(".$row[0].")' ></td></tr>";
            echo $ligne;
          }
          $ligne ="<tr><td><input type=text ID='tuserajout'></td> <td><input type=text ID='tmdpuserajout'></td> <td><img src='img\plus.png' width=40 onclick='createUser()' ></td></tr>";
          echo $ligne;


echo "</table>";  
    }
?>