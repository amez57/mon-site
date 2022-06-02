<?php
include_once "config.php";

$action=$_GET['action'];

 $id = $_GET['ID'];
 $nom= $_GET['nom_admin'];
 $mdp= $_GET['mdp_admin'];


if($action=='affiche') {Affiche();}

if($action=='modif')
{
$requete="UPDATE administrateur SET nom_admin=".$nom.", mdp_admin=".$mdp." WHERE id=".$id;
$sth = $mysqlClient->prepare($requete);
$sth->execute();
Affiche();
}

if($action=='suppr')
{
if($id=='')return;
$requete="DELETE FROM administrateur WHERE id=".$id;
$sth = $mysqlClient->prepare($requete);
$sth->execute();
Affiche();
}

if($action=='ajout')
{
  $rechercher= $mysqlClient->prepare('SELECT * FROM administrateur WHERE nom_admin=?');
  $rechercher->execute(array($nom));
  $trouver=$rechercher->rowCount();
  if($trouver>0){
    echo "Cet utilisateur existe déjà";
  }else{
    $sqlQuery = "INSERT INTO administrateur (nom_admin, mdp_admin) VALUES ('$nom', '$mdp')";
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
$titre= $titre.'<th>Administrateur</th><th>Mdp</th>'; 
$titre = $titre."</tr>";
echo $titre;
$sqlQuery = 'SELECT * FROM administrateur ORDER BY nom_admin';
$sth = $mysqlClient->prepare($sqlQuery);
$sth->execute();
// affiche les données des colonnes  
$nombreColonnes = $sth->columnCount();  
    while ($row = $sth->fetch(PDO::FETCH_NUM)) 
          { 
           $ligne ="";
           
              $ligne ="<tr><td><input type=text ID='tadmin".$row[0]."'  value='".$row[1]."'></td> <td><input type=text ID='tmdpadmin".$row[0]."'  value='".$row[2]."'></td> <td><img src='img\modif_petit.png' width=40 onclick='modifAdmin(".$row[0].")' ><img src='img\del_petit.png' width=40 onclick='supprAdmin(".$row[0].")' ></td></tr>";
            echo $ligne;
          }
          $ligne ="<tr><td><input type=text ID='tadminajout'></td> <td><input type=text ID='tmdpadminajout'></td> <td><img src='img\plus.png' width=40 onclick='createAdmin()' ></td></tr>";
          echo $ligne;


echo "</table>";  
    }
?>