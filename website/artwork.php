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
		<title>artwork.php</title>
	</head>
	<body>
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
				
				if($result["ThumbnailUrl"])
				echo '<img src="' .$result["ThumbnailUrl"]. '" width="500px" style="float: left; border: solid 2px darkgrey; margin-right: 10px;">';

				if($result["Title"])
					echo '<h3 class="title is-3"><b style="font-size: larger">' .$result["Title"]. '</b></h3><br>';
				
				echo '<h4 class="title is-4" style="margin-top: -2%;">';
				if($result["Artist"])
					echo 'Artist: <a href=artist.php?id="' .$result["ArtistId"]. '">' .str_replace(", ", " ", $result["Artist"]). '</a>';
				if($result["ArtistRole"])
					echo ' (Artist role: ' .$result["ArtistRole"]. ')<br>';
				else
					echo '<br>';

				echo '</h4><div style="clear: left"><h5 class="title is-5">';

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
				echo '</h5><br>';

				echo '<a href="index.php">Home</a>';
				if($result["url"])
					echo ' | <a href="' .$result["url"]. '">TATE page</a>';
				echo '</div>';
			}
			else echo 'Internal Error OR Empty Result<br><br>';

			$link->close();
		?>
	</body>
</html>
