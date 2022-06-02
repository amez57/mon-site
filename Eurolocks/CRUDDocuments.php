<?php
include_once "config.php";

$action=$_GET['action'];

 $doc_id = $_GET['ID'];
 $filename= $_GET['doc_nom'];

if($action=='affiche') {Affiche();}

function Affiche()
{
    global $mysqlClient;

$requ = "SHOW COLUMNS FROM documents";
$reponse =  $mysqlClient->query($requ);
          

  echo"<table border=1 width='100%'>";
// affiche les noms des colonnes
  $titre ="<tr>";
while ($donnees = $reponse->fetch())
          {
            $titre= $titre.'<th>'.$donnees['Field'].'</th>'; 
          }
$titre = $titre."</tr>";
echo $titre;
$sqlQuery = 'SELECT * FROM documents ORDER BY doc_nom';
$sth = $mysqlClient->prepare($sqlQuery);
$sth->execute();
// affiche les données des colonnes  
$nombreColonnes = $sth->columnCount();  
    while ($row = $sth->fetch(PDO::FETCH_NUM)) 
          { 
           $ligne ="";
            $datelue = new DateTime($row[5]);

            if($row[5]==null) 
                $datevisu='inconnue'; 
              else 
                {
                  $datevisu =$datelue->Format('d/m/Y');
                }
              $ligne ="<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td><td>".$row[4]."</td><td>".$datevisu."</td></tr>";
            echo $ligne;
          }

echo "</table>";  
    }
?>