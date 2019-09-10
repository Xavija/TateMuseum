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
		<title>index.php</title>
	</head>
	<body style="font-family: Arial; font-size: 125%; color: #444444;">
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

			$id = $_GET["artistID"];

			$general 		= $_GET["general"];

			$infos			= array(isset($_GET["artistInfo"]) ? 1 : 0, isset($_GET["artworkInfo"]) ? 1 : 0);

			$artist_name	= $_GET["name"];
			$places			= $_GET["places"];
			$artist_year	= strval($_GET["artistYear"]);
			$gender			= $_GET["gender"];

			$title			= $_GET["title"];
			$inscription	= $_GET["inscription"];
			$medium			= $_GET["medium"];
			$artwork_year	= strval($_GET["artworkYear"]);
			$artist_role	= $_GET["artistRole"];

			// options:
			$order			= $_GET["order"];
			
			if($id) {
				$fields1 = array('Title', 'Year', 'Medium', 'Inscription', 'ArtistRole', 'Name');
				$query = '
					SELECT DISTINCT Title, Year, Medium, Inscription, ArtistRole, Name, Artwork.ThumbnailUrl, Artwork.ID ID, Artist.ID IDA
					FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
					WHERE Artist.ID = ' .$id. '
					ORDER BY Title '.$order.'
				;';
				
				$query_count = 1;
				$fields = array($fields1);
			}
			else {
				if($general != '' and !$infos[0] and !$infos[1]) { // ricerca libera
					// query #1: artwork
					$query ='
						SELECT Title, Name, Medium, ArtistRole, Year, Artwork.ThumbnailUrl, Artwork.ID ID, Artist.ID IDA
						FROM Artist JOIN Artwork ON Artist.ID=Artwork.ArtistId
						WHERE Title LIKE "'.$general.'%"
						ORDER BY Title '.$order.';
					';	
					$fields1 = array('Title', 'Name', 'Medium', 'ArtistRole', 'Year',);
					
					// query #2: artista
					$query ='
						SELECT Name, Gender, PlaceOfBirth, PlaceOfDeath, YearOfBirth, YearOfDeath, Artist.ID IDA
						FROM Artist JOIN Artwork ON Artist.ID=Artwork.ArtistId
						WHERE Title LIKE "'.$general.'%"
						ORDER BY Title '.$order.'
					;';
					$fields2 = array('Name', 'Gender', 'PlaceOfBirth', 'PlaceOfDeath', 'YearOfBirth', 'YearOfDeath');
					
					$fields = array($fields1, $fields2);
					$query_count = 2; 
				}
				else {
					if($general == '' and !$infos[0] and !$infos[1]) { // vuoto
						$fields1 = array('Title', 'Year', 'Medium', 'Name', 'Gender');
						$query ='
							SELECT Artwork.Title, Artwork.Year, Artwork.Medium, Artist.Name, Artist.Gender, Artwork.ThumbnailUrl, Artwork.ID ID, Artist.ID IDA
							FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
							ORDER BY Artwork.Title '.$order.'
							LIMIT 200
						;';

						$query_count = 1;
						$fields = array($fields1);
					}
					else {
						if($infos[0]) {	// ricerca gudata, artista
							if($artist_name == '') $artist_name = '%';
							if($places == '') $places = '%';
							if($artist_year == '') $artist_year = '%';
							if($gender == 'all') $gender = '%';

							$fields1 = array('Name', 'Gender', 'YearOfBirth', 'YearOfDeath', 'PlaceOfBirth', 'PlaceOfDeath');
							$query = '
								SELECT DISTINCT Name, Gender, YearOfBirth, YearOfDeath, PlaceOfBirth, PlaceOfDeath, Artist.ID IDA
								FROM Artist
								WHERE Name LIKE "%'.$artist_name.'%"
								AND Gender LIKE "'.$gender.'"
								AND (PlaceOfBirth LIKE "%'.$places.'%" OR PlaceOfDeath LIKE "%'.$places.'%")
								AND (YearOfBirth LIKE "'.$artist_year.'" OR YearOfDeath LIKE "'.$artist_year.'")
								ORDER BY Name '.$order.'
							;';
							
							$query_count = 1;
							$fields = array($fields1);

						}
						if($infos[1]) {  // ricerca guidata, artwork
							if($title == '') $title = '%';
							if($inscription == '') $inscription = '%';
							if($medium == '') $medium = '%';
							if($artwork_year == '') $artwork_year = '%';
							if($artist_role == 'all') $artist_role = '%';

							$fields1 = array('Title', 'Year', 'Medium', 'Inscription', 'ArtistRole', 'Artist.Name');
							$query = '
								SELECT DISTINCT Title, Year, Medium, Inscription, ArtistRole, Name, Artwork.ThumbnailUrl, Artwork.ID ID, Artist.ID IDA
								FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
								WHERE Title LIKE "%'.$title.'%"
								AND Year LIKE "%'.$artwork_year.'%"
								AND Medium LIKE "%'.$medium.'%"
								AND Inscription LIKE "%'.$inscription.'%"
								AND ArtistRole LIKE "%'.$artist_role.'%"
								ORDER BY Title '.$order.'
							;';
							
							$query_count = 1;
							$fields = array($fields1);

						}
						if($infos[0] and $infos[1]) {  // ricerca guidata, artista + artwork
							if($artist_name == '') $artist_name = '%';
							if($places == '') $places = '%';
							if($artist_year == '') $artist_year = '%';

							if($title == '') $title = '%';
							if($inscription == '') $inscription = '%';
							if($medium == '') $medium = '%';
							if($artwork_year == '') $artwork_year = '%';

							if($gender == 'all') $gender = '%';

							$fields1 = array('Title', 'Year', 'Medium', 'Inscription', 'ArtistRole', 'Name', 'Gender', 'YearOfBirth', 'YearOfDeath', 'PlaceOfBirth', 'PlaceOfDeath');
							$query = '
								SELECT Title, Year, Medium, Inscription, ArtistRole, Name, Gender, YearOfBirth, YearOfDeath, PlaceOfBirth, PlaceOfDeath, Artwork.ThumbnailUrl, Artwork.ID ID, Artist.ID IDA
								FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
								WHERE Name LIKE "%'.$artist_name.'%"
								AND Gender LIKE "'.$gender.'"
								AND (PlaceOfBirth LIKE "%'.$places.'%" OR PlaceOfDeath LIKE "%'.$places.'%")
								AND (YearOfBirth LIKE "'.$artist_year.'" OR YearOfDeath LIKE "'.$artist_year.'")
								AND Title LIKE "%'.$title.'%"
								AND Year LIKE "%'.$artwork_year.'%"
								AND Medium LIKE "%'.$medium.'%"
								AND Inscription LIKE "%'.$inscription.'%"
								AND ArtistRole LIKE "%'.$artist_role.'%"
								ORDER BY Title '.$order.'
							;';
							
							$query_count = 1;
							$fields = array($fields1);
						}
					}
				}
			}
		?>

		<div class="split left">
			<form action="index.php" method="get">
				<br>
				<h3 class="title is-3" style="text-align: center;">Search</h3>
				<div class="tabs is-toggle is-centered">
					<ul>
						<li class="is-active" id="5">
							<a href="javascript:SearchTabsJS( 'guided', '5') ">
								<span class="icon is-small "><i class="fas "></i></span>
								<span>Guided</span>
							</a>
						</li>
						<li class="" id="4">
							<a href="javascript:SearchTabsJS( 'general', '4') ">
								<span class="icon is-small "><i class="fas "></i></span>
								<span>General</span>
							</a>
						</li>
					</ul>
				</div>

				<div id="SearchTabs">
					<div id="guided" style="display: block;">
						<b>Filters</b><br>
						<div style="text-align: left;">
							<input type="checkbox" id="ArtistInfo" name="artistInfo" value="true" checked> Artist
							<div id="ArtistDiv" style="margin-bottom: -5%;">
								<div class="control">
									<input class="input is-rounded is-focused" type="text" name="name" placeholder="Name" style="margin-top:4px; margin-bottom: 7px;">
									<input class="input is-rounded is-focused" type="text" name="places" placeholder="Places" style="margin-bottom: 7px;">
									<input class="input is-rounded is-focused" type="number" min=0 name="artistYear" placeholder="Year Of birth/death" style="margin-top:4px; margin-bottom: 7px;">
									<div class="field">
										<label class="label">Gender</label>
										<div class="control">
											<div class="select">
											<select name="gender">
												<option value="all">All</option>
												<option value="male">Male</option>
												<option value="female">Female</option>
											</select>
											</div>
										</div>
									</div>
								</div>
							</div>
							<br>
							<input type="checkbox" id="ArtworkInfo" name="artworkInfo" value="true"> Artwork
							<div id="ArtworkDiv" style="margin-bottom: -1%;" hidden>
								<div class="control">
									<input class="input is-rounded is-focused" type="text" name="title" placeholder="Title" style="margin-top:4px; margin-bottom: 7px;">
									<input class="input is-rounded is-focused" type="text" name="inscription" placeholder="Inscription" style="margin-top:4px; margin-bottom: 7px;">
									<input class="input is-rounded is-focused" type="text" name="medium" placeholder="Medium" style="margin-bottom: 7px;">
									<input class="input is-rounded is-focused" type="number" min=0 name="artworkYear" placeholder="Artwork year" style="margin-bottom: 7px;">
									<div class="field">
										<label class="label">Artist role<br></label>
										<div class="control">
											<div class="select">
											<select name="artistRole">
												<option value="all">All</option>
												<?php
													$roleHelper = 'SELECT DISTINCT ArtistRole FROM Artwork;';
													$result = $link->query($roleHelper);
													if($result->num_rows > 0) {
														while($row = $result->fetch_assoc()) 
															echo '<option value="'.$row["ArtistRole"].'">'.$row["ArtistRole"].'</option>';
													}
												?>
											</select>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div id="general" style="display: none;">
						<b>General Info</b>
						<input class="input is-rounded is-focused" type="text" name="general" placeholder="Author/Artwork generic info" style="margin-top:4px; margin-bottom: 25px;"><br>
					</div>
				</div>

				<b>Options</b><br>
				<div style="text-align: left;">
					<div class="field">
						<label class="label">Order by<br></label>
						<div class="control">
							<div class="select">
							<select name="order">
								<option value="asc">A - Z</option>
								<option value="desc">Z - A</option>
							</select>
							</div>
						</div>
					</div>
				</div>

				<br>
				<button class="button is-primary is-rounded is-medium" type="submit" style="margin-bottom: 5%;">Search</button>
			</form>
		</div>

		<div class="split right">
			<br>
			<?php
				for($j = 0; $j < $query_count; $j++) {
					$result = $link->query($query);
					echo '<h3 class="title is-3" style="text-align: center;">Search Results (Total: ' .$result->num_rows. ')</h3>';
					echo '<div id="tableContainer" class="tableContainer">';
					if($result->num_rows >= 15) {
						echo '<table cellpadding="0" cellspacing="0" width="100%" class="scrollTable"><thead class="fixedHeader"><tr>';
						for($i = 0; $i<count($fields[$j]); $i++) {
							echo '<th width="' .(100/count($fields[$j])). '%"><b>' .$fields[$j][$i]. '</b></th>';
						}
						echo '</tr></thead><tbody class="scrollContent">';

						for($k = 0; $row = $result->fetch_assoc(); $k++) {
							if($k % 2 != 0) {
								echo '<tr class="alternateRow">';
							}
							else {
								echo '<tr>';
							}
							
							for($i = 0; $i<count($fields[$j]); $i++) {
								if($fields[$j][$i] == "Title") {
									echo '<td width="' .(100/count($fields[$j])). '%"><a href="artwork.php?id=' .$row["ID"]. '">' .$row[$fields[$j][$i]]. '</a></td>';
								}
								else {
									if($fields[$j][$i] == "Name") {
										echo '<td width="' .(100/count($fields[$j])). '%"><a href="artist.php?id=' .$row["IDA"]. '">' .$row[$fields[$j][$i]]. '</a></td>';
									}
									else {
										echo '<td width="' .(100/count($fields[$j])). '%">' .$row[$fields[$j][$i]]. '</td>';
									}
								}
							}
							echo '</tr>';
						}
						echo '</tbody></table></div>';
					}
					else {
						if($result->num_rows > 0){
							echo '<table cellpadding="0" cellspacing="0" width="100%"><thead><tr>';
						for($i = 0; $i<count($fields[$j]); $i++) {
							echo '<th width="' .(100/count($fields[$j])). '%" style="text-align: center;"><b>' .$fields[$j][$i]. '</b></th>';
						}
						echo '</tr></thead><tbody>';

						for($k = 0; $row = $result->fetch_assoc(); $k++) {
							if($k % 2 != 0) {
								echo '<tr class="alternateRow">';
							}
							else {
								echo '<tr>';
							}
							
							for($i = 0; $i<count($fields[$j]); $i++) {
								if($fields[$j][$i] == "Title") {
									echo '<td width="' .(100/count($fields[$j])). '%"><a href="artwork.php?id=' .$row["ID"]. '">' .$row[$fields[$j][$i]]. '</a></td>';
								}
								else {
									if($fields[$j][$i] == "Name") {
										echo '<td width="' .(100/count($fields[$j])). '%"><a href="artist.php?id=' .$row["IDA"]. '">' .$row[$fields[$j][$i]]. '</a></td>';
									}
									else {
										echo '<td width="' .(100/count($fields[$j])). '%">' .$row[$fields[$j][$i]]. '</td>';
									}
								}
							}
							echo '</tr>';
						}
						echo '</tbody></table></div>';
						}
						else echo 'Internal Error OR Empty Result<br><br>' .$query;
					}
				}
				$link->close();
			?>
		</div>
	</body>
</html>
