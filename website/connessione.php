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
	
	/* Recupero parametri GET
	Ricerca libera:
	- general 		--> informazioni generiche da ricercare un po' ovunque
	Artist:
	- artistInfo 	--> par. di controllo,		bool
	- name			-->	nome,					text
	- places		--> luogo nascita/morte,	text
	Dates:
	- datesInfo		--> par. di controllo,		bool
	- artistYear	--> anno nascita/morte,		int	(to text)
	- artworkYear	--> anno dell'opera,		int (to text)
	Artwork:
	- artworkInfo	--> par. di controllo, 		bool
	- inscription	--> inscrizione,			text
	- medium		--> media,					text
	- artistRole	--> ruolo artista,			text
	*/

	$general 		= $_GET["general"];
	$infos			= array(isset($_GET["artistInfo"]), isset($_GET["datesInfo"]), isset($_GET["artworkInfo"]));
	$artist_name	= $_GET["name"];
	$places			= $_GET["places"];
	$artist_year	= strval($_GET["artistYear"]);
	$artwork_year	= strval($_GET["artworkYear"]);
	$inscription	= $_GET["inscription"];
	$medium			= $_GET["medium"];
	$artist_role	= $_GET["artistRole"];
	
	echo 'general: ' 		. $general 										. '<br>';
	echo 'infos: ' 			. $infos[0] . ' ' . $infos[1] . ' ' . $infos[2] . '<br>';
	echo 'artist_name: ' 	. $artist_name 									. '<br>';
	echo 'artist_year: ' 	. $artist_year 									. '<br>';
	echo 'artwork_year: ' 	. $artwork_year 								. '<br>';
	echo 'inscription: ' 	. $inscription 									. '<br>';
	echo 'medium: ' 		. $medium 										. '<br>';
	echo 'artist_role: ' 	. $artist_role 									. '<br>';

	// Query
	// TODO:
	// - sql injection prevention

	// SELECTED ARTWORKS - eseguita per visualizzare opere d'arte inerenti
	// eseguita per la tab Artworks
	$query ='	SELECT Title, Name, Medium, DateText
				FROM Artist JOIN Artwork ON Artist.ID=Artwork.ArtistId
				WHERE Title LIKE "%'.$keywords.'%"
			;';	

	echo '<br><br><h2> Selected artworks </h2><br><br>';
	$result = $link->query($query);
	if($result->num_rows > 0) {
		echo '<table><tr><th>Title</th><th>Name</th><th>Medium</th><th>DateText</th></tr>';
		while($row = $result->fetch_assoc()) {
			echo '<tr><td>'.$row["Title"].'</td><td>'.$row["Name"].'</td><td>'.$row["Medium"].'</td><td>'.$row["DateText"].'</td></tr>';
		}
		echo '</table>';
	} else {
		echo "no results";
	}
	
	// DEFAULT - verrà eseguita per visualizzare tutto:
	// eseguita per la tab All (anche in caso di parametri di ricerca)
	$query ='	SELECT Title, Name, Medium, DateText
				FROM Artist JOIN Artwork ON Artist.ID=Artwork.ArtistId
				GROUP BY Name
			;';
	
	echo '<br><br><h2> Available artworks </h2><br><br>';
	$result = $link->query($query);
	if($result->num_rows > 0) {
		echo '<table><tr><th>Title</th><th>Name</th><th>Medium</th><th>DateText</th></tr>';
		while($row = $result->fetch_assoc()) {
			echo '<tr><td>'.$row["Title"].'</td><td>'.$row["Name"].'</td><td>'.$row["Medium"].'</td><td>'.$row["DateText"].'</td></tr>';
		}
		echo '</table>';
	} else {
		echo "no results";
	}

	// ALL ARTISTS
	$query ='   SELECT Name, Gender, PlaceOfBirth, PlaceOfDeath, YearOfBirth, YearOfDeath 
				FROM Artist 
			';
	echo '<br><br><h2> All artists </h2>';
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
	
	
	$link->close();
?>
</body>