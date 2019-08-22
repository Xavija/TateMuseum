<?php
	$server = "localhost";
	$user	= "";
	$pass 	= "";
	$db 	= "TATE";

	$link = mysqli_connect($server, $user, $pass, $db);
	if(!$link) {
		echo "Errore di connessione al database." . "<br>";
		echo "Codice di errore: " . mysqli_connect_errno() . "<br>";
		echo "Messaggio di errore: " . mysqli_connect_error() . PHP_EOL;
		exit;
	}
	
	$query = "SELECT * FROM Artist JOIN Artwork ON Artist.ID=Artwork.ArtistId;";
?>
