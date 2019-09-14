<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>index.php</title>
	</head>
	<body style="font-family: Arial; color: #444444;">
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

			// ID DA RICEVERE
			$id = 622;

			// DA QUI SI PUÒ COPIAINCOLLARE, CONTROLLARE SOLO LE PARTI COMMENTATE
			$query = '
				SELECT Artwork.Year Year, COUNT(Artwork.ID) N
				FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
				WHERE Artist.ID = ' .$id. '
				GROUP BY Artwork.Year
				ORDER BY N DESC
			';
			$result = $link->query($query);
			if($result->num_rows > 1) {
				$row = $result->fetch_assoc();
				$maxCount = $row["N"];
				$maxList = array();
				do {
					array_push($maxList, $row["Year"]);
					$row = $result->fetch_assoc();
				} while($row["N"] == $maxCount);

				if(count($maxList) > 1 and count($maxList) != $result->num_rows) {
					echo 'Gli anni con il maggior numero di opere sono:';					// CAMBIARE LE PAROLE, IL RESTO È FATTO
					for($i = 0; $i < count($maxList); $i++) {
						echo ', ' .$maxList[$i];
					}
				}
				else {
					echo 'L\'anno con il maggior numero di opere è il ' .$maxList[0];		// CAMBIARE LE PAROLE, IL RESTO È FATTO
				}

				echo'<br>';
			}
			
			$query = '
				SELECT Artwork.Medium Medium, COUNT(Artwork.ID) N
				FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
				WHERE Artist.ID = ' .$id. '
				GROUP BY Artwork.Medium
				ORDER BY N DESC
			';
			$result = $link->query($query);
			if($result->num_rows > 1) {
				$row = $result->fetch_assoc();
				$maxCount = $row["N"];
				$maxList = array();
				do {
					array_push($maxList, $row["Medium"]);
					$row = $result->fetch_assoc();
				} while($row["N"] == $maxCount);

				if(count($maxList) > 1 and count($maxList) != $result->num_rows) {
					echo 'I medium con il maggior numero di opere sono:';					// CAMBIARE LE PAROLE, IL RESTO È FATTO
					for($i = 0; $i < count($maxList); $i++) {
						echo ', ' .$maxList[$i];
					}
				}
				else {
					echo 'Il medium con il maggior numero di opere è ' .$maxList[0];		// CAMBIARE LE PAROLE, IL RESTO È FATTO
				}

				echo'<br>';
			}

			$query = '
				SELECT Artwork.ArtistRole ArtistRole, COUNT(Artwork.ID) N
				FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
				WHERE Artist.ID = ' .$id. '
				GROUP BY Artwork.ArtistRole
				ORDER BY N DESC
			';
			$result = $link->query($query);
			if($result->num_rows > 1) {
				$row = $result->fetch_assoc();
				$maxCount = $row["N"];
				$maxList = array();
				do {
					array_push($maxList, $row["ArtistRole"]);
					$row = $result->fetch_assoc();
				} while($row["N"] == $maxCount);

				if(count($maxList) > 1 and count($maxList) != $result->num_rows) {
					echo 'Gli ArtistRole con il maggior numero di opere sono:';				// CAMBIARE LE PAROLE, IL RESTO È FATTO
					for($i = 0; $i < count($maxList); $i++) {
						echo ', ' .$maxList[$i];
					}
				}
				else {
					echo 'L\'ArtistRole con il maggior numero di opere è il ' .$maxList[0];	// CAMBIARE LE PAROLE, IL RESTO È FATTO
				}

				echo'<br>';
			}

			$link->close();
		?>
	</body>
</html>
