   <link rel="stylesheet" media="screen and (min-width: 200px) and (max-width: 640px)" />
   <head>
   <meta charset="UTF-8">
   <title>Eurolocks</title>
   <meta name="viewport" content="width=device-width"/>
 </head>

 <?php
require "verif_session.php";
if($_SESSION['username'] !== ""){
    $user = $_SESSION['username'];
    // afficher un message
    echo "Bonjour $user, vous êtes connecté";
}
?>
	  
 <body>

 <a href="deco.php"><br>Deconnexion</a>
<div class="wb-tabs ignore-session">
        <div style="margin-top:20px;">
	      <h1><FONT size="8pt"><u><b> Accueil des gestions</FONT></u></b></h1><br></div>	
	   <div style="margin-left:65px;">

<h1><a href="GestionFamilles_SousFamilles.html">Gestion des FAMILLES et des SOUS FAMILLES</a></h1>
<h1><a href="GestionProduits.html">Gestion des PRODUITS</a></h1>
<h1><a href="UploadDocuments.html">Upload des DOCUMENTS</a></h1>
<h1><a href="AffichageDocuments.html">Affichage des DOCUMENTS</a></h1>

</body>
</html>