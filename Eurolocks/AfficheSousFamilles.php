<?php
include_once "config.php";

$action=$_GET['action'];

 $id = $_GET['ID'];
 $lib= $_GET['lib'];

Affiche();


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
           
              $ligne ="<tr><td>$row[1]</td></tr>";
            echo $ligne;
          }


echo "</table>";  
    }
?>