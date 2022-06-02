<?php
// Vérifier si le formulaire a été soumis
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Vérifie si le fichier a été uploadé sans erreur.
    if(isset($_FILES["doc"]) && $_FILES["doc"]["error"] == 0){
        //$allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png", "pdf" => "application/pdf", "docx" => "application/msword", "xlsx" => "application/msexcel");
        $filename = $_FILES["doc"]["name"];
        $filetype = $_FILES["doc"]["type"];
        $filesize = $_FILES["doc"]["size"];
        $file_type = $_FILES['doc']['type'];
        $commentaire = $_POST['commentaire'];
        $datecourante= new DateTime('NOW');

        
 // Stocker le nom du fichier dans une variable
  $file = "$filename"; 
    
  // Type de contenu de l'en-tête
  header('Content-type: $filetype'); 
    
  header('Content-Disposition: inline; filename="' . $file . '"'); 
    
  header('Content-Transfer-Encoding: binary'); 
    
  header('Accept-Ranges: bytes'); 
    
  // Lire le fichier
  @readfile($file); 


        //echo $file_type.'<br>'.$filesize.'<br>'.$filename.'<br>';

        // Vérifie la taille du fichier - 1024Mo maximum
        //$maxsize = 2048 * 1024 * 1024;
       // if($filesize > $maxsize) die("Error: La taille du fichier est superieure a la limite autorisee.");

            // Vérifie si le fichier existe avant de le télécharger.
            if(file_exists("documents/" . $_FILES["doc"]["name"])){
                echo $_FILES["doc"]["name"] . " existe deje.";
            } else{
                move_uploaded_file($_FILES["doc"]["tmp_name"], "documents/" . $_FILES["doc"]["name"]);
                echo "Votre fichier a ete telecharge avec succes.";
           
// la partie bdd 

            include_once "config.php";
            $comm= addslashes($commentaire);
            
            $req ="INSERT INTO documents (doc_nom, doc_type, doc_taille, doc_desc, doc_date) VALUES ('".$filename."','".$filetype."',".$filesize.",'".$comm. "','".$datecourante->Format('Y-m-d')."')";
            
  
            $result = $mysqlClient -> prepare($req);
            $result->execute();




            } 
        //} else{
            //echo "Error: Il y a eu un problème de téléchargement de votre fichier. Veuillez réessayer."; 
        //}
    } else{
        echo "Error: " . $_FILES["doc"]["error"];
    }
}
?>