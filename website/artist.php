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
		<?php
			$server = "localhost";
			$user	= "michele";
			$pass 	= "Aero";
			$db 	= "TATE";

			$link = new mysqli($server, $user, $pass, $db);
			if($link->connect_error) {
				echo 'Errore di connessione al database.' . '<br>';
				echo 'Codice di errore: ' . $link->connect_error . '<br>';
				exit;
			}
			$id = $_GET["id"];

			if($id) {			// Artist.IDs
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
				;';
				$fields2 = array('Title', 'ArtistRole', 'Medium', 'Year');
								// Numero di opere per artista
				$query3 = '
					SELECT COUNT(Artwork.ID) AS Num
					FROM Artist JOIN Artwork ON Artist.ID=Artwork.ArtistId
					WHERE Artist.ID = '.$id.'
				;';
				$fields3 = array("Num");
				
				$fields = array($fields1, $fields2, $fields3);
			}
			else {
				echo 'Errore durante la ricezione dei dati.';
			}

			$result = $link->query($query1);
			if($result->num_rows > 0) {
				$result = $result->fetch_assoc();

				if($result["Name"])
					echo '<a href="' .$result["url"]. '">
						<b style="font-size: larger">' .str_replace(", ", " ", $result["Name"]). '</b></a>';
				if($result["Gender"])
					echo ' (' .$result["Gender"]. ')';
				echo '<br>';

				if(!$result["YearOfBirth"] and !$result["PlaceOfBirth"]) {
					echo 'Birth: no information<br>';
				}
				else {
					if($result["YearOfBirth"]) {
						echo 'Birth info: ' .$result["YearOfBirth"];
					}
					else {
						echo 'Birth info: missing year';
					}
					if($result["PlaceOfBirth"]) {
						echo ' (in ' .$result["PlaceOfBirth"]. ')<br>';
					}
					else {
						echo ' (missing place)<br>';
					}
				}

				if(!$result["YearOfDeath"] and !$result["PlaceOfDeath"]) {
					echo 'Death: no information<br>';
				}
				else {
					if($result["YearOfDeath"]) {
						echo 'Death info: ' .$result["YearOfDeath"];
					}
					else {
						echo 'Death info: missing year';
					}
					if($result["PlaceOfDeath"]) {
						echo ' (in ' .$result["PlaceOfDeath"]. ')<br>';
					}
					else {
						echo ' (missing place)<br>';
					}
				}

				echo '<br>Vedi <a href="index.php?artistInfo=true
				&name=' .str_replace(", ", "%2C+", $result["Name"]). '
				&places=' .str_replace(", ", "%2C+", $result["PlaceOfBirth"]). '
				&artistYear=' .$result["YearOfBirth"]. '
				&gender=' .$result["Gender"]. '
				&artworkInfo=true&title=&inscription=&medium=&artworkYear=&artistRole=%25&general=
				">tutte le opere</a>';
				echo '<br>Torna alla <a href="index.php">home</a>';
				if($result["url"])
					echo '<br>Visita la pagina del <a href="' .$result["url"]. '">sito ufficiale</a>';
			}

			$result = $link->query($query3);
			if($result->num_rows > 0) {
				$result = $result->fetch_assoc();
				echo '<p>Questo artista ha realizzato '.$result[$fields3[0]].' opere.<br></p>';
			}
			$link->close();
		?>
	</body>
</html>
