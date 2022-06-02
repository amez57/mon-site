<?php
include_once "config.php";

$action=$_GET['action'];

 $id = $_GET['ID'];
 $nom= $_GET['NomReference'];



if($action=='affiche') {Affiche();}
if($action=='filtrer') {Filtre();}
if($action=='combo') {ComboRech();}


if($action=='modif')
{
$famille=$_GET['Fam'];
$sousfamille=$_GET['SFam'];
$requete="UPDATE Produit SET NomReference=".$nom.", SousFamilleID=".$sousfamille.", FamilleID=".$famille." WHERE id=".$id;
$sth = $mysqlClient->prepare($requete);
$sth->execute();
Affiche();
}
if($action=='suppr')
{
   
if($id=='')return;
$requete="DELETE FROM Produit WHERE id=".$id;
$sth = $mysqlClient->prepare($requete);
$sth->execute();
Affiche();
}

if($action=='ajout')
{
$famille=$_GET['FamA'];
$sousfamille=$_GET['SFamA'];
$sqlQuery = "INSERT INTO Produit (SousFamilleID, FamilleID, NomReference) VALUES ( '$sousfamille', '$famille', '$nom')";
$sth = $mysqlClient->prepare($sqlQuery);
$sth->execute();
Affiche();
}

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
           $ligne ="";
          /// requete pour remplir le select famille
          $r2 ="select id, lib from famille";
          $sth2 = $mysqlClient->prepare($r2);
            $sth2->execute();
            // on lit la value dans la table globale
            
          $combof='<SELECT id=cbFam'.$row[0].' onchange=montre("cbFam'.$row[0].'")>';
          while ($row2 = $sth2->fetch(PDO::FETCH_NUM)) 
            {
              if($row2[0]== $row[4])
              {
                $combof = $combof."<option value=".$row2[0]." selected>". $row2[1]."</option>";
              }else
              $combof = $combof."<option value=".$row2[0].">". $row2[1]."</option>";
            }
            $combof = $combof."</select>";
/// sous-famille
$r2 ="select id, lib from sousfamille";
          $sth2 = $mysqlClient->prepare($r2);
            $sth2->execute();
            // on lit la value dans la table globale
            
          $combosf='<SELECT id=cbSFam'.$row[0].' onchange=montre("cbSFam'.$row[0].'")>';
          while ($row2 = $sth2->fetch(PDO::FETCH_NUM)) 
            {
              if($row2[0]== $row[5])
              {
                $combosf = $combosf."<option value=".$row2[0]." selected>". $row2[1]."</option>";
              }else
              $combosf = $combosf."<option value=".$row2[0].">".$row2[1]."</option>";
            }
            $combosf = $combosf."</select>";
           
              $ligne ="<tr><td>".$row[0]."</td><td>".$combosf."</td><td>".$combof."</td><td><input type=text ID='tnom".$row[0]."'  value='".$row[1]."'></td><td><img src='img\modif_petit.png' width=40 onclick='modifProduits(".$row[0].")' ><img src='img\del_petit.png' width=40 onclick='supprProduits(".$row[0].")' ></td></tr>";
            echo $ligne;
          }

                    /// requete pour remplir le select famille
                    $r3 ="select id, lib from famille";
                    $sth3 = $mysqlClient->prepare($r3);
                      $sth3->execute();
                      // on lit la value dans la table globale
                      
                    $combofa='<SELECT id=cbFamA'.$row[0].' onchange=montre("cbFamA'.$row[0].'")>';
                    $combofa=$combofa."<option value=''>--Selectionner une Famille--</option>";
                    while ($row3 = $sth3->fetch(PDO::FETCH_NUM)) 
                      {
                        $combofa = $combofa."<option value=".$row3[0].">". $row3[1]."</option>";
                      }
                      $combofa = $combofa."</select>";
          /// sous-famille
          $r3 ="select id, lib from sousfamille";
                    $sth3 = $mysqlClient->prepare($r3);
                      $sth3->execute();
                      // on lit la value dans la table globale
                      
                    $combosfa='<SELECT id=cbSFamA'.$row[0].' onchange=montre("cbSFamA'.$row[0].'")>';
                    $combosfa=$combosfa."<option value=''>--Selectionner une SousFamille--</option>";
                    while ($row3 = $sth3->fetch(PDO::FETCH_NUM)) 
                      {
                        $combosfa = $combosfa."<option value=".$row3[0].">".$row3[1]."</option>";
                      }
                      $combosfa = $combosfa."</select>";

          $ligne ="<tr>
          <td></td>
          <td>".$combosfa."</td>
          <td>".$combofa."</td>
          <td><input type=text ID='tnomajout'></td>
          <td><img src='img\plus.png' width=40 onclick='createProduits()' ></td>
          </tr>";
          echo $ligne;


echo "</table>";  
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
      $requ = "DESC Produit";
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
               $ligne ="";
              /// requete pour remplir le select famille
              $r2 ="select id, lib from famille";
              $sth2 = $mysqlClient->prepare($r2);
                $sth2->execute();
                // on lit la value dans la table globale
                
              $combof='<SELECT id=cbFam'.$row[0].' onchange=montre("cbFam'.$row[0].'")>';
              while ($row2 = $sth2->fetch(PDO::FETCH_NUM)) 
                {
                  if($row2[0]== $row[4])
                  {
                  $combof = $combof."<option value=".$row2[0]." selected>". $row2[1]."</option>";
                  }else
                  $combof = $combof."<option value=".$row2[0].">". $row2[1]."</option>";
                }
                $combof = $combof."</select>";
    /// sous-famille
    $r2 ="select id, lib from sousfamille";
              $sth2 = $mysqlClient->prepare($r2);
                $sth2->execute();
                // on lit la value dans la table globale
                
              $combosf='<SELECT id=cbSFam'.$row[0].' onchange=montre("cbSFam'.$row[0].'")>';
              while ($row2 = $sth2->fetch(PDO::FETCH_NUM)) 
                {
                  if($row2[0]== $row[5])
                  {
                    $combosf = $combosf."<option value=".$row2[0]." selected>". $row2[1]."</option>";
                  }else
                  $combosf = $combosf."<option value=".$row2[0].">".$row2[1]."</option>";
                }
                $combosf = $combosf."</select>";
               
                  $ligne ="<tr><td>".$row[0]."</td><td>".$combosf."</td><td>".$combof."</td><td><input type=text ID='tnom".$row[0]."'  value='".$row[1]."'></td><td><img src='img\modif_petit.png' width=40 onclick='modifProduits(".$row[0].")' ><img src='img\del_petit.png' width=40 onclick='supprProduits(".$row[0].")' ></td></tr>";
                echo $ligne;
              }
    
                        /// requete pour remplir le select famille
              $r3 ="select id, lib from famille";
                        $sth3 = $mysqlClient->prepare($r3);
                          $sth3->execute();
                          // on lit la value dans la table globale 
                          $combofa='<SELECT id=cbFamA'.$row[0].' onchange=montre("cbFamA'.$row[0].'")>';
                          $combofa=$combofa."<option value=''>Choisir une famille</option>";
                          while ($row3 = $sth3->fetch(PDO::FETCH_NUM)) 
                          {
                            $combofa = $combofa."<option value=".$row3[0].">". $row3[1]."</option>";
                          }
                          $combofa = $combofa."</select>";
                         
              /// sous-famille
              $r3 ="select id, lib from sousfamille";
                        $sth3 = $mysqlClient->prepare($r3);
                          $sth3->execute();
                          // on lit la value dans la table globale
                          
                        $combosfa='<SELECT id=cbSFamA'.$row[0].' onchange=montre("cbSFamA'.$row[0].'")>';
                        $combosfa=$combosfa."<option value=''>Choisir une sous famille</option>";
                        while ($row3 = $sth3->fetch(PDO::FETCH_NUM)) 
                          {
                            $combosfa = $combosfa."<option value=".$row3[0].">".$row3[1]."</option>";
                          }
                          $combosfa = $combosfa."</select>";    
    
              $ligne ="<tr><td></td>
              <td>".$combosfa."</td>
              <td>".$combofa."</td>
              <td><input type=text ID='tnomajout'></td>
              <td><img src='img\plus.png' width=40 onclick='createProduits()' ></td>
              </tr>";
              echo $ligne;
    
    echo "</table>";  
                        } 
?>