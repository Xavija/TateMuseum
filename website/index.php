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
				echo 'Errore di connessione al database.' . '<br>';
				echo 'Codice di errore: ' . $link->connect_error . '<br>';
				exit;
			}

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
			
			if($general != '' and !$infos[0] and !$infos[1]) {
				// query #1: artwork
				$query ='
					SELECT Title, Name, Medium, ArtistRole, DateText, Artwork.ThumbnailUrl, Artwork.ID ID
					FROM Artist JOIN Artwork ON Artist.ID=Artwork.ArtistId
					WHERE Title LIKE "'.$general.'%"
					ORDER BY Title ASC;
				';	
				$fields1 = array('Title', 'Name', 'Medium', 'ArtistRole', 'DateText',);
				
				// query #2: artista
				$query ='
					SELECT Name, Gender, PlaceOfBirth, PlaceOfDeath, YearOfBirth, YearOfDeath
					FROM Artist JOIN Artwork ON Artist.ID=Artwork.ArtistId
					WHERE Title LIKE "'.$general.'%"
					ORDER BY Title ASC
				;';
				$fields2 = array('Name', 'Gender', 'PlaceOfBirth', 'PlaceOfDeath', 'YearOfBirth', 'YearOfDeath');
				
				$fields = array($fields1, $fields2);
				$query_count = 2; 
			}
			else {
				if($general == '' and !$infos[0] and !$infos[1]) {
					$fields = array('Title', 'Year', 'Medium', 'Name', 'Gender');
					$query ='
						SELECT Artwork.Title, Artwork.Year, Artwork.Medium, Artist.Name, Artist.Gender, Artwork.ThumbnailUrl, Artwork.ID ID
						FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
						ORDER BY Artist.Name
						LIMIT 200;
					';

					$query_count = 1;
					$fields = array($fields);
				}
				else {
					if($infos[0]) {
						if($artist_name == '') $artist_name = '%';
						if($places == '') $places = '%';
						if($artist_year == '') $artist_year = '%';

						$fields = array('Name', 'Gender', 'YearOfBirth', 'YearOfDeath', 'PlaceOfBirth', 'PlaceOfDeath');
						$query = '
							SELECT DISTINCT Name, Gender, YearOfBirth, YearOfDeath, PlaceOfBirth, PlaceOfDeath
							FROM Artist
							WHERE Name LIKE "%'.$artist_name.'%"
							AND Gender LIKE "'.$gender.'"
							AND (PlaceOfBirth LIKE "%'.$places.'%" OR PlaceOfDeath LIKE "%'.$places.'%")
							AND (YearOfBirth LIKE "'.$artist_year.'" OR YearOfDeath LIKE "'.$artist_year.'")
						;';
						
						$query_count = 1;
						$fields = array($fields);

					}
					if($infos[1]) {
						if($title == '') $title = '%';
						if($inscription == '') $inscription = '%';
						if($medium == '') $medium = '%';
						if($artwork_year == '') $artwork_year = '%';

						$fields = array('Title', 'Year', 'Medium', 'Inscription', 'ArtistRole', 'Artist.Name');
						$query = '
							SELECT DISTINCT Title, Year, Medium, Inscription, ArtistRole, Artist.Name, Artwork.ThumbnailUrl, Artwork.ID ID
							FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
							WHERE Title LIKE "%'.$title.'%"
							AND Year LIKE "%'.$artwork_year.'%"
							AND Medium LIKE "%'.$medium.'%"
							AND Inscription LIKE "%'.$inscription.'%"
							AND ArtistRole LIKE "%'.$artist_role.'%"
						;';
						
						$query_count = 1;
						$fields = array($fields);

					}
					if($infos[0] and $infos[1]) {
						if($artist_name == '') $artist_name = '%';
						if($places == '') $places = '%';
						if($artist_year == '') $artist_year = '%';

						if($title == '') $title = '%';
						if($inscription == '') $inscription = '%';
						if($medium == '') $medium = '%';
						if($artwork_year == '') $artwork_year = '%';

						$fields = array('Title', 'Year', 'Medium', 'Inscription', 'ArtistRole', 'Name', 'Gender', 'YearOfBirth', 'YearOfDeath', 'PlaceOfBirth', 'PlaceOfDeath');
						$query = '
							SELECT Title, Year, Medium, Inscription, ArtistRole, Name, Gender, YearOfBirth, YearOfDeath, PlaceOfBirth, PlaceOfDeath, Artwork.ThumbnailUrl, Artwork.ID ID
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
						;';
						
						$query_count = 1;
						$fields = array($fields);
					}
				}
			}
		?>

		<div class="split left">
			<form action="index.php" method="get">
				<br>
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
									<input type="radio" name="gender" value="%" checked> All<br>
									<input type="radio" name="gender" value="Male"> Male<br>
									<input type="radio" name="gender" value="Female"> Female
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
									<input type="radio" name="artistRole" value="%" checked> All<br>
									<input type="radio" name="artistRole" value="artist"> Artist<br>
									<input type="radio" name="artistRole" value="attributed to"> Attributed to<br>
									<input type="radio" name="artistRole" value="after"> After<br>
									<input type="radio" name="artistRole" value="manner of"> Manner of<br>
									<input type="radio" name="artistRole" value="formerly attributed to"> Formerly attributed to<br>
									<input type="radio" name="artistRole" value="doubtfully attributed to"> Doubtfully attributed to<br>
									<input type="radio" name="artistRole" value="circle of"> Circle of<br>
									<input type="radio" name="artistRole" value="prints after"> Prints after<br>
									<input type="radio" name="artistRole" value="studio of"> Studio of<br>
									<input type="radio" name="artistRole" value="and studio"> And studio<br>
									<input type="radio" name="artistRole" value="follower of"> Follower of<br>
									<input type="radio" name="artistRole" value="and assistants"> And assistants<br>
									<input type="radio" name="artistRole" value="and a pupil"> And a pupil<br>
									<input type="radio" name="artistRole" value="school of"> School of<br>
									<input type="radio" name="artistRole" value="imitator of"> Imitator of<br>
									<input type="radio" name="artistRole" value="style of"> Style of<br>
									<input type="radio" name="artistRole" value="and other artists"> And other artists<br>
									<input type="radio" name="artistRole" value="pupil of"> Pupil of
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
					Order by:
					<div class="dropdown is-left">
						<div class="dropdown-trigger">
							<button id="sortOrder" type="button" class="button" aria-haspopup="true" aria-controls="dropdown-menu3">
								<span>Order</span>
								<span class="icon is-small">
									<i class="fas fa-angle-down" aria-hidden="true"></i>
								</span>
							</button>
						</div>
						<div class="dropdown-menu" id="dropdown-menu3" role="menu">
							<div class="dropdown-content">
								<!-- <a href="#" class="dropdown-item">A - Z</a>
								<a href="#" class="dropdown-item">Z - A</a> -->
								<div class="dropdown-item">
									<label class="radio">
										<input type="radio" name="order" value="asc">A - Z
									</label>
								</div>
								<div class="dropdown-item">
									<label class="radio">
										<input type="radio" name="order" value="desc">Z - A
									</label>
								</div>
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
			<div id="tableContainer" class="tableContainer">
				<?php
					for($j = 0; $j < $query_count; $j++) {
						$result = $link->query($query);
						if($result->num_rows >= 15) {
							echo '<table cellpadding="0" cellspacing="0" width="100%" class="scrollTable"><thead class="fixedHeader"><tr>';
							for($i = 0; $i<count($fields[$j]); $i++) {
								echo '<th width="' .(100/count($fields[$j])). '%"><b>' .$fields[$j][$i]. '</b></th>';
							}
							echo '</tr></thead><tbody class="scrollContent">';

							$k = 0;
							while($row = $result->fetch_assoc()) {
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
											echo '<td width="' .(100/count($fields[$j])). '%"><a href="artist.php?id=' .$row["ID"]. '">' .$row[$fields[$j][$i]]. '</a></td>';
										}
										else {
											echo '<td width="' .(100/count($fields[$j])). '%">' .$row[$fields[$j][$i]]. '</td>';
										}
									}
								}
								echo '</tr>';
								$k++;
							}
							echo '</tbody></table>';
						} else {
							if($result->num_rows > 0){
								echo '<table cellpadding="0" cellspacing="0" width="100%"><thead><tr>';
							for($i = 0; $i<count($fields[$j]); $i++) {
								echo '<th width="' .(100/count($fields[$j])). '%" style="text-align: center;"><b>' .$fields[$j][$i]. '</b></th>';
							}
							echo '</tr></thead><tbody>';

							$k = 0;
							while($row = $result->fetch_assoc()) {
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
											echo '<td width="' .(100/count($fields[$j])). '%"><a href="artist.php?id=' .$row["ID"]. '">' .$row[$fields[$j][$i]]. '</a></td>';
										}
										else {
											echo '<td width="' .(100/count($fields[$j])). '%">' .$row[$fields[$j][$i]]. '</td>';
										}
									}
								}
								echo '</tr>';
								$k++;
							}
							echo '</tbody></table>';
							}
							else echo 'Internal Error OR Empty Result<br><br>';
						}
					}
					$link->close();
				?>
			</div>
		</div>
	</body>
</html>