<!DOCTYPE html>
<html style="height: 100%;">
	<head>
	<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.2/css/bulma.min.css">
		<link rel="stylesheet" href="bulma.min.css">
		<link rel="stylesheet" href="style.css">
		
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script type="text/javascript" src="scripts.js"></script>
		<title>artwork.php</title>
	</head>
	<body style="height: 100%;">
		<nav class="level" style="margin-bottom: 0; border-bottom: solid #bbb 5px;">
			<div class="level-left">
				<div class="level-item">
				<p class="subtitle is-3">
					<strong>TATE</strong> Museum
				</p>
				</div>
			</div>

			<div class="level-right">
				<p class="level-item"><a href="index.php">Home</a></p>
				<p class="level-item"><a href="https://tate.org.uk">Tate Official</a></p>
				<p class="level-item">
					<a href="https://bulma.io"><img src="res/made-with-bulma.png" alt="Bulma" width=128 height=30></a>
				</p>
			</div>
		</nav>
		<section class="section">
			<div class="container is-center">
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
						echo 'Unable to connect to ' .$db. ' DB<br>';
						echo 'Error code: ' . $link->connect_error . '<br>';
						exit;
					}
				}
				
				$id = $_GET["id"];

				if($id) {
					$query ='
						SELECT *
						FROM Artwork
						WHERE ID = ' .$id. '
						ORDER BY Title ASC;
					';	
					// $fields = array('Artist', 'ArtistRole', 'Title', 'Medium', 'Year', 'AcquisitionYear', 'Width', 'Height', 'Depth', 'Units', 'Inscription', 'ThumbnailUrl', 'url');
				}
				else {
					echo 'Errore durante la ricenzione dei dati.';
				}

				$result = $link->query($query);
				if($result->num_rows > 0){
					$result = $result->fetch_assoc();
					
					if($result["Title"]) {
						echo '<h3 class="title is-3"><center>';
						echo '<b style="font-size: larger">' .$result["Title"]. '</b><br>';
						echo '</center></h3>';
					}

					echo '<h5 class="title is-5" style="margin-top: -2%;"><center>';
					if($result["Artist"])
						echo 'By <a href=artist.php?id=' .$result["ArtistId"]. '>' .str_replace(", ", " ", $result["Artist"]). '</a>';

					if($result["ArtistRole"])
						echo ' (role: ' .$result["ArtistRole"]. ')<br>';
					else
						echo '<br>';
					echo '</center></h5><br>';
					
				?>
				</div>
				<div class="container" id="pre-footer">
				<?php
					if($result["ThumbnailUrl"])
						/* echo '<figure>';
						echo '<img src="' .$result["ThumbnailUrl"]. '" style="float: left; border: solid 2px darkgrey; margin-right: 2px;">';
						echo '</figure>'; */
					?>
						<div class="column">
							<figure>
						<?php echo '<img src="' .$result["ThumbnailUrl"]. '" style="border: solid 2px darkgrey; margin-right: 2px;">'; ?>
							</figure>
						</div>
					<?php

					if($result["Medium"])
						echo 'Medium: ' .$result["Medium"] .'<br>';
					
					if($result["Year"])
						echo 'Year of creation: ' .$result["Year"] .'<br>';
					if($result["AcquisitionYear"])
						echo 'Year of acquisition: ' .$result["AcquisitionYear"] .'<br>';
					if($result["Width"] and $result["Height"]) {
						echo 'Dimensions: ' .$result["Width"]. ' x ' .$result["Height"];
						if($result["Depth"])
							echo ' x ' .$result["Depth"];
						if($result["Units"])
							echo ' ' .$result["Units"]. '<br>';
					}
					if($result["Inscription"])
						echo 'Inscription: "' .$result["Inscription"]. '"<br>';

					if($result["url"])
						echo 'Link for the <a href="' .$result["url"]. '">official page</a> of this artwork';
				}
				else echo 'Internal Error OR Empty Result<br><br>';

				$link->close();
				?>
				</div>
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
