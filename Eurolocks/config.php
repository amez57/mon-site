<?php

try
{
	// On se connecte à MySQL
$mysqlClient = new PDO('mysql:host=127.0.0.1;dbname=euro-locks;charset=utf8', 'root');
}
catch(Exception $e)
{
	// En cas d'erreur, on affiche un message et on arrête tout
        die('Erreur : '.$e->getMessage());
}
?>