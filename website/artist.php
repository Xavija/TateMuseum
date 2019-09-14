<!DOCTYPE html>
<html style="height: 100%;">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<!-- Bulma -->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.2/css/bulma.min.css">
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<!-- Local files -->
		<link rel="stylesheet" href="./res/style.css">
		<script type="text/javascript" src="./res/scripts.js"></script>

		<title>TATE Museum | Artist</title>
	</head>
	<body style="height: 100%;">
		<!-- Navbar (Title) -->
		<nav class="level" style="margin-bottom: 0; border-bottom: solid #bbb 5px;">
			<!-- left -->
			<div class="level-left">
				<div class="level-item">
				<p class="subtitle is-3">
					<strong>TATE</strong> Museum
				</p>
				</div>
			</div>
			<!-- right -->
			<div class="level-right">
				<p class="level-item"><a href="index.php">Home</a></p>
				<p class="level-item"><a href="https://tate.org.uk">Tate Official</a></p>
				<p class="level-item">
					<a href="https://bulma.io"><img src="res/made-with-bulma.png" alt="Bulma" width=128 height=30></a>
				</p>
			</div>
		</nav>

		<section class="section">
			<div class="container">
				<?php
					// Connessione al database
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
							echo 'Unable to connect to ' .$db. ' DB<br>';
							echo 'Error code: ' . $link->connect_error . '<br>';
							exit;
						}
					}
					
					$id = $_GET["id"]; 			// Ricezione ID artista

					if(!isset($id)) {
						echo 'Errore durante la ricezione dei dati.';
						exit;
					}
					?>
				<div class="container">
					<?php
					// Artist.IDs
					$queryArtistIDs = $link->prepare('			
						SELECT *
						FROM Artist
						WHERE ID = ?
					;');
					$queryArtistIDs->bind_param('i', $id);
					
					$queryArtistIDs->execute();
					$result = $queryArtistIDs->get_result();
					if($result->num_rows > 0) {
						$result = $result->fetch_assoc();

						echo '<h3 class="title is-3"><center>';
						if($result["Name"])
							echo '<a href="' .$result["url"]. '">
								<b style="font-size: larger">' .str_replace(", ", " ", $result["Name"]). '</b></a>';
					
						if($result["Gender"])
							echo ' (' .$result["Gender"]. ')';
						
						echo '</center></h3><br>';
						echo '<h5 class="title is-5" style="margin-top: -2%;"><center>';
						
						if(!$result["YearOfBirth"] and !$result["PlaceOfBirth"])
							echo 'No birth information - ';
						else {
							if($result["YearOfBirth"])
								echo '' .$result["YearOfBirth"];	
							else
								echo 'Missing birth year - ';

							if($result["PlaceOfBirth"])
								echo ' (in ' .$result["PlaceOfBirth"]. ') - ';
							else
								echo ' (missing place), ';
						}

						if(!$result["YearOfDeath"] and !$result["PlaceOfDeath"])
							echo ' no death information<br>';
						else {
							if($result["YearOfDeath"]) 
								echo '' .$result["YearOfDeath"];
							else
								echo 'missing death year';
		
							if($result["PlaceOfDeath"]) 
								echo ' (in ' .$result["PlaceOfDeath"]. ')<br>';	
							else 
								echo ' (missing place)<br>';
						}
						echo '</center></h5><br>';
					}
				?>
				</div>
				<div class="container" id="pre-footer">
				<?php
					// Numero di opere per artista
					$queryNumArtworks = $link->prepare('
						SELECT COUNT(Artwork.ID) AS Num
						FROM Artist JOIN Artwork ON Artist.ID=Artwork.ArtistId
						WHERE Artist.ID = ?
					;');
					$queryNumArtworks->bind_param('i', $id);
					
					$queryNumArtworks->execute();
					$result = $queryNumArtworks->get_result();
					if($result->num_rows > 0) {
						$result = $result->fetch_assoc();
						echo '<br>This artist has realized '.$result["Num"].' artworks. ';
						echo '(<a href="index.php?artistID=' .$id. '">Complete list</a>)';
					}
					echo '<br>';

					// Anno con più artworks
					$queryMostProductiveYear = $link->prepare('
						SELECT Artwork.Year Year, COUNT(Artwork.ID) N
						FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
						WHERE Artist.ID = ?
						GROUP BY Artwork.Year
						ORDER BY N DESC
					');
					$queryMostProductiveYear->bind_param('i', $id);
					
					$queryMostProductiveYear->execute();
					$result = $queryMostProductiveYear->get_result();
					if($result->num_rows > 1) {
						$row = $result->fetch_assoc();
						$maxCount = $row["N"];
						$maxList = array();
						do {
							array_push($maxList, $row["Year"]);
							$row = $result->fetch_assoc();
						} while($row["N"] == $maxCount);

						if(count($maxList) != $result->num_rows) {
							if(count($maxList) > 1) {
								echo 'The most productive years are: ' .$maxList[0];
								for($i = 1; $i < count($maxList); $i++) {
									echo ', ' .$maxList[$i];
								}
							}
							else {
								echo 'The most productive year is: ' .$maxList[0];
							}

							echo' - ' .$maxCount. ' artwork(s)<br>';
						}
					}
					// Medium più utilizzato dall'artista
					$queryMostUsedMedium = $link->prepare('
						SELECT Artwork.Medium Medium, COUNT(Artwork.ID) N
						FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
						WHERE Artist.ID = ?
						GROUP BY Artwork.Medium
						ORDER BY N DESC
					');
					$queryMostUsedMedium->bind_param('i', $id);

					$queryMostUsedMedium->execute();
					$result = $queryMostUsedMedium->get_result();
					if($result->num_rows > 1) {
						$row = $result->fetch_assoc();
						$maxCount = $row["N"];
						$maxList = array();
						do {
							array_push($maxList, $row["Medium"]);
							$row = $result->fetch_assoc();
						} while($row["N"] == $maxCount);

						if(count($maxList) != $result->num_rows) {
							if(count($maxList) > 1) {
								echo 'The most used medium are: ' .$maxList[0];
								for($i = 1; $i < count($maxList); $i++) {
									echo ', ' .$maxList[$i];
								}
							}
							else {
								echo 'The most used medium is: ' .$maxList[0];
							}

							echo' - ' .$maxCount. ' artwork(s)<br>';
						}
					}

					// Ruolo più frequente
					$queryMostFrequentRole = $link->prepare('
						SELECT Artwork.ArtistRole ArtistRole, COUNT(Artwork.ID) N
						FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
						WHERE Artist.ID = ?
						GROUP BY Artwork.ArtistRole
						ORDER BY N DESC
					');
					$queryMostFrequentRole->bind_param('i', $id);

					$queryMostFrequentRole->execute();
					$result = $queryMostFrequentRole->get_result();
					if($result->num_rows > 1) {
						$row = $result->fetch_assoc();
						$maxCount = $row["N"];
						$maxList = array();
						do {
							array_push($maxList, $row["ArtistRole"]);
							$row = $result->fetch_assoc();
						} while($row["N"] == $maxCount);

						if(count($maxList) != $result->num_rows) {
							if(count($maxList) > 1) {
								echo 'The most popular artist roles are: ' .$maxList[0];
								for($i = 1; $i < count($maxList); $i++) {
									echo ', ' .$maxList[$i];
								}
							}
							else
								echo 'The most popular artist role is: ' .$maxList[0];

							echo' - ' .$maxCount. ' artwork(s)<br>';
						}
					}
					
					$link->close();
				?>
			</div>
		</section>
		<footer class="footer" id="piedatore">
			<div class="content has-text-centered">
				<p>
					<a href="index.php">Home</a> | <a href="https://www.tate.org.uk/">TATE</a><br>
					Made with <a href="https://www.bulma.io"><b>Bulma</b></a>
				</p>
			</div>
		</footer>
	</body>
</html>
