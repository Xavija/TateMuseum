<!DOCTYPE html>
<head>
	<meta charset="utf-8">
</head>
<body>
<?php
	
	// Connessione al database
	$server = "localhost";
	$user	= "phil";
	$pass 	= "";
	$db 	= "TATE";

	$link = new mysqli($server, $user, $pass, $db);
	if($link->connect_error) {
		echo 'Errore di connessione al database.' . '<br>';
		echo 'Codice di errore: ' . $link->connect_error . '<br>';
		exit;
	}

	// Recupero parametri GET
	// search 	-> 	text field di ricerca
	// author 	-> 	checkbox, restrizione per autore
	// date 	->	checkbox, restrizione per data generica (data di nascita/morte...)
	// artwork	->	button, restrizione per opera d'arte

	$keywords = $_GET["search"]; // testo di ricerca
	$restrictions = array($_GET["author"], $_GET["date"], $_GET["artwork"]);
	//var_dump($restrictions);

	// Stampa parametri
	echo 'search: ' 	. $keywords . '<br>';
	echo 'author: ' 	. $restrictions[0] . '<br>';
	echo 'date: ' 		. $restrictions[1] . '<br>';
	echo 'artwork: ' 	. $restrictions[2] . '<br>';
	echo '<br>';
	// NOTA: i tasti per "guidare" la ricerca in uno specifico dettaglio
	// (artwork etc.) vengono contati come input, quindi da trattare dopo il submit	
	
	// Query
	// TODO:
	// - sql injection prevention
	// DEFAULT - verrà eseguita per visualizzare tutto:
	// eseguita per la tab All (anche in caso di parametri di ricerca)
	$query ='	SELECT *
				FROM Artist JOIN Artwork ON Artist.ID=Artwork.ArtistId;
			';
	
	// ARTISTS
	$query ='   SELECT Name, Gender, PlaceOfBirth, PlaceOfDeath, YearOfBirth, YearOfDeath 
				FROM Artist 
			';

	$result = $link->query($query);
	if($result->num_rows > 0) {
		echo '<table><tr><th>Name</th><th>Gender</th><th>PlaceOfBirth</th><th>PlaceOfDeath</th><th>YearOfBirth</th><th>YearOfDeath</th></tr>';
		while($row = $result->fetch_assoc()) {
			echo '<tr><td>'.$row["Name"].'</td><td>'.$row["Gender"].'</td><td>'.$row["PlaceOfBirth"].'</td><td>'.$row["PlaceOfDeath"].'</td><td>'.$row["YearOfBirth"].'</td><td>'.$row["YearOfDeath"].'</td></tr>';
		}
		echo '</table>';
	} else {
		echo "no results";
	}

	// ARTWORKS - eseguita per visualizzare opere d'arte inerenti
	// eseguita per la tab Artworks
	$query ='	SELECT *
				FROM Artist JOIN Artwork ON Artist.ID=Artwork.ArtistId
				WHERE Artwork.Title LIKE '.$keywords.';
			';	

	$link->close();
?>
</body>