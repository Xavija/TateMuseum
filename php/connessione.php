<?php
	
	// Connessione al database
	$server = "localhost";
	$user	= "";
	$pass 	= "";
	$db 	= "TATE";

	$link = mysqli_connect($server, $user, $pass, $db);
	if(!$link) {
		echo 'Errore di connessione al database.' . '<br>';
		echo 'Codice di errore: ' . mysqli_connect_errno() . '<br>';
		echo 'Messaggio di errore: ' . mysqli_connect_error() . PHP_EOL;
		exit;
	}

	// Recupero parametri GET
	// search 	-> 	text field di ricerca
	// author 	-> 	button, restrizione per autore
	// date 	->	button, restrizione per data generica (data di nascita/morte...)
	// artwork	->	button, restrizione per opera d'arte

	$keywords = $_GET["search"]; // testo di ricerca
	$restrictions = array($_GET["author"], $_GET["date"], $_GET["artwork"]);
	var_dump($restrictions);
	// NOTA: i tasti per "guidare" la ricerca in uno specifico dettaglio
	// (artwork etc.) vengono contati come input, quindi da trattare dopo il submit	
	
	// Query
	// TODO:
	// - sql injection prevention
	// DEFAULT - verrà eseguita per visualizzare tutto:
	// eseguita per la tab All (anche in caso di parametri di ricerca)
	$query ='   SELECT * 
				FROM Artist JOIN Artwork ON Artist.ID=Artwork.ArtistId;
			';
	// ARTWORKS - eseguita per visualizzare opere d'arte inerenti
	// eseguita per la tab Artworks
	$query ='	SELECT *
				FROM Artist JOIN Artwork ON Artist.ID=Artwork.ArtistId
				WHERE Artwork.Title LIKE '.$keywords.';
			';	
?>
