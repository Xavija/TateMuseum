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
			$user	= "phil";
			$pass 	= "";
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

				echo '<br><a href="index.php">Home</a>';
				if($result["url"])
					echo ' | <a href="' .$result["url"]. '">TATE page</a>';
			}

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
				else 
					echo '<br>L\'anno con più opere è il '.$result["Year"].' ('.$result[$fields4[0]].' opere).<br>';
			}

			$result = $link->query($query2);
			if($result->num_rows > 0) {
				echo '<table cellpadding="0" cellspacing="0" width="75%" class="scrollTable"><thead class="fixedHeader"><tr>';
				for($i = 0; $i<count($fields2); $i++) {
					echo '<th width="' .(100/count($fields2)). '%"><b>' .$fields2[$i]. '</b></th>';
				}
				echo '</tr></thead><tbody class="scrollContent">';

				for($k = 0; $row = $result->fetch_assoc(); $k++) {
					if($k % 2 != 0) {
						echo '<tr class="alternateRow">';
					}
					else {
						echo '<tr>';
					}
					
					for($i = 0; $i<count($fields2); $i++) {
						if($fields2[$i] == "Title") {
							echo '<td width="' .(100/count($fields2)). '%"><a href="artwork.php?id=' .$row["ID"]. '">' .$row[$fields2[$i]]. '</a></td>';
						}
						else {
							echo '<td width="' .(100/count($fields2)). '%">' .$row[$fields2[$i]]. '</td>';
						}
					}
					echo '</tr>';
				}
				echo '</tbody></table>';
			}
			$link->close();
		?>
	</body>
</html>
