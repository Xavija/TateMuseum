<?php
/*
 *	Test connessione al database, query e codice per lista completa (in caso non vengano forniti parametri)
 */


$server = "localhost";
$user 	= "phil";
$password = "";
$database = "TATE";

$conn = new mysqli($server, $user, $password, $database);
if($conn->connect_error)
	echo "Errore durante la connessione al database: ".$conn->connect_error;
else
	echo "Connessione avvenuta con successo."
?>
