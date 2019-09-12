<!DOCTYPE html>
<html>
	<head>
	<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.2/css/bulma.min.css">
		<link rel="stylesheet" href="bulma.min.css">
		<link rel="stylesheet" href="style.css">
		
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script type="text/javascript" src="scripts.js"></script>
		<title>artist.php</title>
	</head>
	<body>
		<section class="section">
			<div class="container">
				<?php
					$server = "localhost";
					$user	= "michele";
					$pass 	= "Aero";
					$db 	= "TATE";

					$link = new mysqli($server, $user, $pass, $db);
					if($link->connect_error) {
						$user	= "phil";
						$pass 	= "";
						$link = new mysqli($server, $user, $pass, $db);

						if($link->connect_error) {
							echo 'Errore di connessione al database.' . '<br>';
							echo 'Codice di errore: ' . $link->connect_error . '<br>';
							exit;
						}
					}
					
					$id = $_GET["id"];

					if(isset($id)) {			// Artist.IDs
						$query1 ='			
							SELECT *
							FROM Artist
							WHERE ID = '.$id.'
						;';	
						$fields1 = array('ID', 'Name', 'Gender', 'Dates', 'YearOfBirth', 'YearOfDeath', 'PlaceOfBirth', 'PlaceOfDeath');
										// Artist.IDs ordinati per anno crescente
						$query2 ='
							SELECT *
							FROM Artist JOIN Artwork ON Artist.ID=Artwork.ArtistId
							WHERE Artist.ID = '.$id.'
							ORDER BY Year ASC
							LIMIT 5
						;';
						$fields2 = array('Title', 'Year', 'Medium', 'Inscription', 'ArtistRole');
										// Numero di opere per artista
						$query3 = '
							SELECT COUNT(Artwork.ID) AS Num
							FROM Artist JOIN Artwork ON Artist.ID=Artwork.ArtistId
							WHERE Artist.ID = '.$id.'
						;';
						$fields3 = array('Num');
						
						$query4 = '
						SELECT COUNT(Artwork.ID) Num, Year
						FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
						WHERE Artwork.ArtistId = '.$id.'
						GROUP BY Year
						ORDER BY Num DESC
						LIMIT 1;
						';
						$fields4 = array('Num', 'Year');

						$fields = array($fields1, $fields2, $fields3, $fields4);
					}
					else {
						echo 'Errore durante la ricezione dei dati.';
					}
					?>
				<div class="container">
					<?php
					$result = $link->query($query1);
					if($result->num_rows > 0) {
						$result = $result->fetch_assoc();

						echo '<h3 class="title is-3"><center>';
						if($result["Name"]) {
							echo '<a href="' .$result["url"]. '">
								<b style="font-size: larger">' .str_replace(", ", " ", $result["Name"]). '</b></a>';
						}
					
						if($result["Gender"])
							echo ' (' .$result["Gender"]. ')';
						echo '</center></h3><br>';
					?>
				
					<?php
						echo '<h5 class="title is-5" style="margin-top: -2%;"><center>';
						if(!$result["YearOfBirth"] and !$result["PlaceOfBirth"]) {
							echo 'No birth information - ';
						}
						else {
							if($result["YearOfBirth"]) {
								echo '' .$result["YearOfBirth"];
							}
							else {
								echo 'Missing birth year - ';
							}
							if($result["PlaceOfBirth"]) {
								echo ' (in ' .$result["PlaceOfBirth"]. ') - ';
							}
							else {
								echo ' (missing place), ';
							}
						}

						if(!$result["YearOfDeath"] and !$result["PlaceOfDeath"]) {
							echo ' no death information<br>';
						}
						else {
							if($result["YearOfDeath"]) {
								echo '' .$result["YearOfDeath"];
							}
							else {
								echo 'missing death year';
							}
							if($result["PlaceOfDeath"]) {
								echo ' (in ' .$result["PlaceOfDeath"]. ')<br>';
							}
							else {
								echo ' (missing place)<br>';
							}
						}
						echo '</center></h5>';

						echo '<div style="margin-top: -1%;"><a href="index.php">Home</a>';
						if($result["url"])
							echo ' | <a href="' .$result["url"]. '">TATE page</a>';
						echo '</div><br>';
					}
				?>
				</div>
				<div class="container">
				<?php
					$result = $link->query($query3);
					if($result->num_rows > 0) {
						$result = $result->fetch_assoc();
						echo '<br>Questo artista ha realizzato '.$result[$fields3[0]].' opere. ';
						echo '(<a href="index.php?artistID=' .$id. '">Lista completa</a>)';
					}
					else {
						echo '<br>Vedi <a href="index.php?artistID=' .$id. '">tutte le opere</a>';
					}
					
					$result = $link->query($query4);
					if($result->num_rows > 0) {
						$result = $result->fetch_assoc();
						if($result["Year"] == '')
							echo '<br>L\'anno con più opere è sconosciuto ('.$result["Num"].' opere).<br>';
						else if($result->num_rows > 1 or $result["Num"] != 1)
							echo '<br>L\'anno con più opere è il '.$result["Year"].' ('.$result[$fields4[0]].' opere).<br>';
					}

					$link->close();
				?>
				</div>
		</section>
	</body>
</html>
