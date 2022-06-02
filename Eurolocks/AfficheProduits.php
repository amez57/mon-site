<?php
include_once "config.php";

$action=$_GET['action'];

 $id = $_GET['ID'];
 $nom= $_GET['NomReference'];



if($action=='affiche'){Affiche();}
if($action=='combo'){ComboRech();}
if($action=='filtrer'){Filtre();}

function Affiche()
{
    global $mysqlClient;

$requ = "SHOW COLUMNS FROM Produit";
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
$sqlQuery = 'SELECT produit.id, produit.nomreference, famille.lib, sousfamille.lib, famille.id, sousfamille.id FROM Produit INNER JOIN famille ON famille.id=produit.familleid INNER JOIN sousfamille ON sousfamille.id=produit.sousfamilleid ORDER BY NomReference';
$sth = $mysqlClient->prepare($sqlQuery);
$sth->execute();
// affiche les données des colonnes  
$nombreColonnes = $sth->columnCount();  
    while ($row = $sth->fetch(PDO::FETCH_NUM)) 
          { 

              $ligne ="<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>$row[3]</td></tr>";
            echo $ligne;
          }


 
    }




    function ComboRech()
    {
      global $mysqlClient;
      ///famille  
      $texte= "<SELECT id=combo name=combo onchange='FiltreComboF(this)'>";
      $texte .="<OPTION VALUE=''>Choisir une famille</OPTION>";
      $reqRech = "select * from famille"; //requête SQL qui le
      $sthRech =  $mysqlClient->query($reqRech);
      while ($rowRech = $sthRech->fetch(PDO::FETCH_NUM))
      {
        $texte .= "<OPTION VALUE=".$rowRech[0].">".$rowRech[1]."</OPTION>";
      }
      $texte .="</SELECT>";
      //////Sous famille
      $texte2= "<SELECT id=combo name=combo onchange='FiltreComboSF(this)'>";
      $texte2 .="<OPTION VALUE=''>Choisir une sous famille</OPTION>";
      $reqRech = "select * from sousfamille";
      $sthRech =  $mysqlClient->query($reqRech);
      while ($rowRech = $sthRech->fetch(PDO::FETCH_NUM))
      {
        $texte2 .= "<OPTION VALUE=".$rowRech[0].">".$rowRech[1]."</OPTION>";
      }
      $texte2 .="</SELECT>";
      $chaine = $texte."    ".$texte2;
      echo $chaine;
    }  





    function Filtre()
    {
        global $mysqlClient;
    
    $requ = "SHOW COLUMNS FROM Produit";
    $reponse =  $mysqlClient->query($requ);
              
    if(isset($_GET['filtreIDF']))
    {
      $filtreFid = $_GET['filtreIDF'];
      if($filtreFid ==""){Affiche();} 
      $sqlQuery = "SELECT produit.id, produit.nomreference, famille.lib, sousfamille.lib, famille.id, sousfamille.id FROM Produit INNER JOIN famille ON famille.id=produit.familleid INNER JOIN sousfamille ON sousfamille.id=produit.sousfamilleid";
      $sqlQuery .= " WHERE famille.id=$filtreFid";
      if(isset($_GET['filtreIDSF']))
      {
        $sqlQuery .= " AND sousfamille.id=$filtreSFid";
      }
      $sqlQuery .= " ORDER BY NomReference";
    }
    if(isset($_GET['filtreIDSF']))
    {
      $filtreSFid = $_GET['filtreIDSF'];
      if($filtreSFid ==""){Affiche();}
      $sqlQuery = "SELECT produit.id, produit.nomreference, famille.lib, sousfamille.lib, famille.id, sousfamille.id FROM Produit INNER JOIN famille ON famille.id=produit.familleid INNER JOIN sousfamille ON sousfamille.id=produit.sousfamilleid";
      $sqlQuery .= " WHERE sousfamille.id=$filtreSFid";
      if(isset($_GET['filtreIDF']))
      {
        $sqlQuery .= " AND famille.id=$filtreFid";
      }
      $sqlQuery .= " ORDER BY NomReference";
    }
    
      echo"<table border=1 width='100%'>";
    // affiche les noms des colonnes
      $titre ="<tr>";
    while ($donnees = $reponse->fetch())
              {
                $titre= $titre.'<th>'.$donnees['Field'].'</th>'; 
              }
    $titre = $titre."</tr>";
    echo $titre;
  $sth = $mysqlClient->prepare($sqlQuery);
    $sth->execute();
    // affiche les données des colonnes  
    $nombreColonnes = $sth->columnCount();  
        while ($row = $sth->fetch(PDO::FETCH_NUM)) 
              { 
    
                  $ligne ="<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>$row[3]</td></tr>";
                echo $ligne;
              }
    
    
     
        }    





?>