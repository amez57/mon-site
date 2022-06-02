<?php
include_once "config.php";

$action=$_GET['action'];

 $id = $_GET['ID'];
 $lib= $_GET['lib'];


if($action=='affiche') {Affiche();}

if($action=='modif')
{

$requete="UPDATE SousFamille SET Lib=".$lib." WHERE id=".$id;
$sth = $mysqlClient->prepare($requete);
$sth->execute();
Affiche();
}
if($action=='suppr')
{
   
if($id=='')return;
$requete="DELETE FROM SousFamille WHERE id=".$id;
$sth = $mysqlClient->prepare($requete);
$sth->execute();
Affiche();
}

if($action=='ajoutSF')
{
  $rechercher= $mysqlClient->prepare('SELECT * FROM SousFamille WHERE lib=?');
  $rechercher->execute(array($lib));
  $trouver=$rechercher->rowCount();
  if($trouver>0){
    $texte='Cette sous famille existe déjà';
    echo '<center>'.$texte.'</center>';
  }else{
    $sqlQuery = "INSERT INTO SousFamille (Lib) VALUES ('$lib')";
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
$titre= $titre.'<th>Sous famille</th>'; 
$titre = $titre."</tr>";
echo $titre;
$sqlQuery = 'SELECT * FROM SousFamille ORDER BY lib';
$sth = $mysqlClient->prepare($sqlQuery);
$sth->execute();
// affiche les données des colonnes  
$nombreColonnes = $sth->columnCount();  
    while ($row = $sth->fetch(PDO::FETCH_NUM)) 
          { 
           $ligne ="";
           
              $ligne ="<tr><td><input type=text ID='tlib".$row[0]."'  value='".$row[1]."'></td><td><img src='img\modif_petit.png' width=40 onclick='modifSousFamilles(".$row[0].")' ><img src='img\del_petit.png' width=40 onclick='supprSousFamilles(".$row[0].")' ></td></tr>";
            echo $ligne;
          }
          $ligne ="<tr><td><input type=text ID='tlibajoutSF'></td><td><img src='img\plus.png' width=40 onclick='createSousFamilles()' ></td></tr>";
          echo $ligne;


echo "</table>";  
    }
?>