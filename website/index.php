<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.2/css/bulma.min.css">
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>

		<link rel="stylesheet" href="./res/style.css">
		<script type="text/javascript" src="./res/scripts.js"></script>

		<title>TATE Museum | Search</title>
	</head>
	<body>
		<nav class="level" style="margin-bottom: 0; border-bottom: solid #bbb 5px;">
			<div class="level-left">
				<div class="level-item">
					<p class="subtitle is-3">
						<strong>TATE</strong> Museum
					</p>
				</div>
			</div>
			<div class="level-right">
				<p class="level-item"><strong>Home</strong></p>
				<p class="level-item"><a href="https://tate.org.uk">Tate Official</a></p>
				<p class="level-item"><a href="https://bulma.io"><img src="res/made-with-bulma.png" alt="Bulma" width=128 height=30></a></p>
			</div>
		</nav>

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
					echo 'Error code: ' .$link->connect_error. '<br>';
					exit;
				}
			}

			$id				= $_GET["artistID"];

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

			$order			= $_GET["order"];

			if($id != null) { // Lista delle opere di un dato artista
				$fields = array('Title', 'Year', 'Medium', 'Inscription', 'ArtistRole', 'Name');
				$query = '
					SELECT DISTINCT Title, Year, Medium, Inscription, ArtistRole, Name, ThumbnailUrl, Artwork.ID ID, Artist.ID IDA
					FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
					WHERE Artist.ID = ' .$id. '
					ORDER BY Title '.$order.'
				;';
			}
			else {
				if($general != '' and !$infos[0] and !$infos[1]) { // Ricerca Generale
					$fieldsArtist = array('Name', 'Gender', 'PlaceOfBirth', 'PlaceOfDeath', 'YearOfBirth', 'YearOfDeath');
					$bestArtist = null;
					for($i = 0; $i < count($fieldsArtist); $i++) {
						$option = '
							SELECT DISTINCT Name, Gender, PlaceOfBirth, PlaceOfDeath, YearOfBirth, YearOfDeath, ID IDA
							FROM Artist
							WHERE ' .$fieldsArtist[$i]. ' LIKE "%'.$general.'%"
							ORDER BY ' .$fieldsArtist[$i]. ' '.$order.'
							LIMIT 200
						;';
						$result = $link->query($option);
						if($bestArtist == null or ($bestArtist > $result->num_rows and $result->num_rows > 0)) {
							$queryArtist = $option;
							$bestArtist = $result->num_rows;
						}
					}

					$fieldsArtwork = array('Title', 'Year', 'Medium', 'Inscription', 'ArtistRole');
					$bestArtwork = null;
					for($i = 0; $i < count($fieldsArtwork); $i++) {
						$option = '
						SELECT DISTINCT Title, Year, Medium, Inscription, ArtistRole, Artist.Name Name, Artist.ID IDA, Artwork.ID ID
						FROM Artwork JOIN Artist ON Artwork.ArtistId=Artist.ID
						WHERE ' .$fieldsArtwork[$i]. ' LIKE "%'.$general.'%"
						ORDER BY ' .$fieldsArtwork[$i]. ' ' .$order. '
						LIMIT 200
						;';
						$result = $link->query($option);
						if($bestArtwork == null or ($bestArtwork > $result->num_rows and $result->num_rows > 0)) {
							$queryArtwork = $option;
							$bestArtwork = $result->num_rows;
						}
					}

					if($bestArtwork == 0) {
						$bestArtwork = $bestArtist + 1;
					}
					if($bestArtist == 0) {
						$bestArtist = $bestArtwork + 1;
					}

					if($bestArtwork < $bestArtist) {
						array_push($fieldsArtwork, "Name");
						$query = $queryArtwork;
						$fields1 = $fieldsArtwork;
					}
					else {
						$query = $queryArtist;
						$fields1 = $fieldsArtist;
					}

					$fields = array($fields1);
					$query_count = 1; 
				}
				else {
					if($general == '' and !$infos[0] and !$infos[1]) { // Ricerca vuota
						$fields = array('Name', 'Gender', 'PlaceOfBirth', 'PlaceOfDeath', 'YearOfBirth', 'YearOfDeath');
						$query ='
							SELECT DISTINCT Name, Gender, PlaceOfBirth, PlaceOfDeath, YearOfBirth, YearOfDeath, Artist.ID IDA
							FROM Artist JOIN Artwork ON Artist.ID=Artwork.ArtistId
							ORDER BY Name '.$order.'
							LIMIT 200
						;';
					}
					else {
						if($infos[0]) {	// Ricerca gudata -> Artist
							if($artist_name == '') $artist_name = '%';
							if($places == '') $places = '%';
							if($artist_year == '') $artist_year = '%';
							if($gender == 'all') $gender = '%';

							$fields = array('Name', 'Gender', 'YearOfBirth', 'YearOfDeath', 'PlaceOfBirth', 'PlaceOfDeath');
							$query = '
								SELECT DISTINCT Name, Gender, YearOfBirth, YearOfDeath, PlaceOfBirth, PlaceOfDeath, Artist.ID IDA
								FROM Artist
								WHERE Name LIKE "%'.$artist_name.'%"
								AND Gender LIKE "'.$gender.'"
								AND (PlaceOfBirth LIKE "%'.$places.'%" OR PlaceOfDeath LIKE "%'.$places.'%")
								AND (YearOfBirth LIKE "'.$artist_year.'" OR YearOfDeath LIKE "'.$artist_year.'")
								ORDER BY Name '.$order.'
							;';
						}
						if($infos[1]) {  // Ricerca gudata -> Artwork
							if($title == '') $title = '%';
							if($inscription == '') $inscription = '%';
							if($medium == '') $medium = '%';
							if($artwork_year == '') $artwork_year = '%';
							if($artist_role == 'all') $artist_role = '%';

							$fields = array('Title', 'Year', 'Medium', 'Inscription', 'ArtistRole', 'Name');
							$query = '
								SELECT DISTINCT Title, Year, Medium, Inscription, ArtistRole, Artist.Name Name, Artwork.ThumbnailUrl, Artwork.ID ID, Artist.ID IDA
								FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
								WHERE Title LIKE "%'.$title.'%"
								AND Year LIKE "%'.$artwork_year.'%"
								AND Medium LIKE "%'.$medium.'%"
								AND Inscription LIKE "%'.$inscription.'%"
								AND ArtistRole LIKE "%'.$artist_role.'%"
								ORDER BY Title '.$order.'
								LIMIT 200
							;';
						}
						if($infos[0] and $infos[1]) {  // Ricerca gudata -> Artist + Artwork
							if($artist_name == '') $artist_name = '%';
							if($places == '') $places = '%';
							if($artist_year == '') $artist_year = '%';
							if($gender == 'all') $gender = '%';

							if($title == '') $title = '%';
							if($inscription == '') $inscription = '%';
							if($medium == '') $medium = '%';
							if($artwork_year == '') $artwork_year = '%';

							$fields = array('Title', 'Year', 'Medium', 'Inscription', 'ArtistRole', 'Name', 'Gender', 'YearOfBirth', 'YearOfDeath', 'PlaceOfBirth', 'PlaceOfDeath');
							$query = '
								SELECT DISTINCT Title, Year, Medium, Inscription, ArtistRole, Artist.Name Name, Gender, YearOfBirth, YearOfDeath, PlaceOfBirth, PlaceOfDeath, Artwork.ThumbnailUrl, Artwork.ID ID, Artist.ID IDA
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
								LIMIT 200
							;';
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
						<li class="is-active" id="2">
							<a href="javascript:SearchTabs('guided', '2')">
								<span class="icon is-small"><i class="fas"></i></span>
								<span>Guided</span>
							</a>
						</li>
						<li class="" id="1">
							<a href="javascript:SearchTabs('general', '1')">
								<span class="icon is-small"><i class="fas"></i></span>
								<span>General</span>
							</a>
						</li>
					</ul>
				</div>

				<div id="SearchTabs">
					<div id="guided" style="display: block;">
						<b>Filters</b><br>
						<div style="text-align: left;">
							<input type="checkbox" id="ArtistInfo" name="artistInfo" value="true"> Artist
							<div id="ArtistDiv" style="margin-bottom: -5%;" hidden>
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
							<div id="ArtworkDiv" style="margin-bottom: 3%;" hidden>
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
														$roleHelper = '
															SELECT DISTINCT ArtistRole
															FROM Artwork
														;';
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
				<button class="button is-primary is-rounded is-medium" type="submit">Search</button>
			</form>
		</div>

		<div class="split right">
			<br>
			<?php
				$result = $link->query($query);
				echo '<h3 class="title is-3" style="text-align: center;">Search Results (Total: ' .$result->num_rows. ')</h3>';
				echo '<div id="tableContainer" class="tableContainer">';
				if($result->num_rows >= 15) {
					echo '<table cellpadding="0" cellspacing="0" width="100%" class="scrollTable"><thead class="fixedHeader"><tr>';
					for($i = 0; $i<count($fields); $i++) {
						echo '<th width="' .(100/count($fields)). '%"><b>' .$fields[$i]. '</b></th>';
					}
					echo '</tr></thead><tbody class="scrollContent">';

					for($k = 0; $row = $result->fetch_assoc(); $k++) {
						if($k % 2 != 0) {
							echo '<tr class="alternateRow">';
						}
						else {
							echo '<tr>';
						}

						for($i = 0; $i<count($fields); $i++) {
							if($fields[$i] == "Title") {
								echo '<td width="' .(100/count($fields)). '%"><a href="artwork.php?id=' .$row["ID"]. '">' .$row[$fields[$i]]. '</a></td>';
							}
							else {
								if($fields[$i] == "Name") {
									echo '<td width="' .(100/count($fields)). '%"><a href="artist.php?id=' .$row["IDA"]. '">' .$row[$fields[$i]]. '</a></td>';
								}
								else {
									echo '<td width="' .(100/count($fields)). '%">' .$row[$fields[$i]]. '</td>';
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
						for($i = 0; $i<count($fields); $i++) {
							echo '<th width="' .(100/count($fields)). '%" style="text-align: center;"><b>' .$fields[$i]. '</b></th>';
						}
						echo '</tr></thead><tbody>';

						for($k = 0; $row = $result->fetch_assoc(); $k++) {
							echo '<tr>';
							for($i = 0; $i<count($fields); $i++) {
								$class = "";
								if($k%2 != 0) {
									$class = "alternate";
								}
								echo '<td class="' .$class. '" width="' .(100/count($fields)). '%">';
								if($fields[$i] == "Title") {
									echo '<a href="artwork.php?id=' .$row["ID"]. '">' .$row[$fields[$i]]. '</a>';
								}
								else {
									if($fields[$i] == "Name") {
										echo '<a href="artist.php?id=' .$row["IDA"]. '">' .$row[$fields[$i]]. '</a>';
									}
									else {
										echo $row[$fields[$i]];
									}
								}
								echo '</td>';
							}
							echo '</tr>';
						}
						echo '</tbody></table></div>';
					}
					else {
						echo 'The informations recived from the input form lead to an empty result.';
					}
				}
				$link->close();
			?>
		</div>
	</body>
</html>
