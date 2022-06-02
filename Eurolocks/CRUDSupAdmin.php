<?php
include_once "config.php";

$action=$_GET['action'];

 $id = $_GET['ID'];
 $nom= $_GET['nom_sadmin'];
 $mdp= $_GET['mdp_sadmin'];


if($action=='affiche') {Affiche();}

if($action=='modif')
{
$requete="UPDATE superadministrateur SET nom_sadmin=".$nom.", mdp_sadmin=".$mdp." WHERE id=".$id;
$sth = $mysqlClient->prepare($requete);
$sth->execute();
Affiche();
}
if($action=='suppr')
{

if($id=='')return;
$requete="DELETE FROM superadministrateur WHERE id=".$id;
$sth = $mysqlClient->prepare($requete);
$sth->execute();
Affiche();
}

if($action=='ajout')
{
  $rechercher= $mysqlClient->prepare('SELECT * FROM superadministrateur WHERE nom_sadmin=?');
  $rechercher->execute(array($nom));
  $trouver=$rechercher->rowCount();
  if($trouver>0){
    echo "Cet utilisateur existe déjà";
  }else{
    $sqlQuery = "INSERT INTO superadministrateur (nom_sadmin, mdp_sadmin) VALUES ('$nom', '$mdp')";
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
$titre= $titre.'<th>Super Administrateur</th><th>Mdp</th>'; 
$titre = $titre."</tr>";
echo $titre;
$sqlQuery = 'SELECT * FROM superadministrateur ORDER BY nom_sadmin';
$sth = $mysqlClient->prepare($sqlQuery);
$sth->execute();
// affiche les données des colonnes  
$nombreColonnes = $sth->columnCount();  
    while ($row = $sth->fetch(PDO::FETCH_NUM)) 
          { 
           $ligne ="";
           
              $ligne ="<tr><td><input type=text ID='tsadmin".$row[0]."'  value='".$row[1]."'></td> <td><input type=text ID='tmdpsadmin".$row[0]."'  value='".$row[2]."'></td> <td><img src='img\modif_petit.png' width=40 onclick='modifSupAdmin(".$row[0].")' ><img src='img\del_petit.png' width=40 onclick='supprSupAdmin(".$row[0].")' ></td></tr>";
            echo $ligne;
          }
          $ligne ="<tr><td><input type=text ID='tsadminajout'></td> <td><input type=text ID='tmdpsadminajout'></td> <td><img src='img\plus.png' width=40 onclick='createSupAdmin()' ></td></tr>";
          echo $ligne;


echo "</table>";  
    }
?>