<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.2/css/bulma.min.css">
		<link rel="stylesheet" href="bulma.min.css">
		<link rel="stylesheet" type="text/css" href="./style.css">
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script type="text/javascript" src="./scripts.js"></script>
	</head>
	<body style="font-family: Arial; font-size: 125%; color: #444444;">
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
				$query ='	SELECT Title, Name, Medium, ArtistRole, DateText
				FROM Artist JOIN Artwork ON Artist.ID=Artwork.ArtistId
				WHERE Title LIKE "'.$general.'%"
				ORDER BY Title ASC;';	
				$fields1 = array('Title', 'Name', 'Medium', 'ArtistRole', 'DateText',);
				
				// query #2: artista
				$query ='	SELECT Name, Gender, PlaceOfBirth, PlaceOfDeath, YearOfBirth, YearOfDeath
				FROM Artist JOIN Artwork ON Artist.ID=Artwork.ArtistId
				WHERE Title LIKE "'.$general.'%"
				ORDER BY Title ASC;';
				$fields2 = array('Name', 'Gender', 'PlaceOfBirth', 'PlaceOfDeath', 'YearOfBirth', 'YearOfDeath');
				
				$fields = array($fields1, $fields2);
				$query_count = 2; 
			}
			else {
				if($general == '' and !$infos[0] and !$infos[1]) {
					$fields = array('Title', 'Year', 'Medium', 'Name', 'Gender');
					$query ='
						SELECT Artwork.Title, Artwork.Year, Artwork.Medium, Artist.Name, Artist.Gender
						FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
						ORDER BY Artist.Name
						LIMIT 50;
					';
					$debugQuery = '
						SELECT Artwork.Title, Artwork.Year, Artwork.Medium, Artist.Name, Artist.Gender<br>
						FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId<br>
						ORDER BY Artist.Name<br>
						LIMIT 50
					;';

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
						$debugQuery = '
							SELECT DISTINCT Name, Gender, YearOfBirth, YearOfDeath, PlaceOfBirth, PlaceOfDeath<br>
							FROM Artist<br>
							WHERE Name LIKE "%'.$artist_name.'%"<br>
							AND Gender LIKE "'.$gender.'"<br>
							AND (PlaceOfBirth LIKE "%'.$places.'%" OR PlaceOfDeath LIKE "%'.$places.'%")<br>
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
							SELECT DISTINCT Title, Year, Medium, Inscription, ArtistRole, Artist.Name
							FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
							WHERE Title LIKE "%'.$title.'%"
							AND Year LIKE "%'.$artwork_year.'%"
							AND Medium LIKE "%'.$medium.'%"
							AND Inscription LIKE "%'.$inscription.'%"
							AND ArtistRole LIKE "%'.$artist_role.'%"
						;';
						$debugQuery = '
							SELECT DISTINCT Title, Year, Medium, Inscription, ArtistRole, Artist.Name<br>
							FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId<br>
							WHERE Title LIKE "%'.$title.'%"<br>
							AND Year LIKE "%'.$artwork_year.'%"<br>
							AND Medium LIKE "%'.$medium.'%"<br>
							AND Inscription LIKE "%'.$inscription.'%"<br>
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
							SELECT Title, Year, Medium, Inscription, ArtistRole, Name, Gender, YearOfBirth, YearOfDeath, PlaceOfBirth, PlaceOfDeath
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
						$debugQuery = '
							SELECT Title, Year, Medium, Inscription, ArtistRole, Name, Gender, YearOfBirth, YearOfDeath, PlaceOfBirth, PlaceOfDeath<br>
							FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId<br>
							WHERE Name LIKE "%'.$artist_name.'%"<br>
							AND Gender LIKE "'.$gender.'"<br>
							AND (PlaceOfBirth LIKE "%'.$places.'%" OR PlaceOfDeath LIKE "%'.$places.'%")<br>
							AND (YearOfBirth LIKE "'.$artist_year.'" OR YearOfDeath LIKE "'.$artist_year.'")<br>
							AND Title LIKE "%'.$title.'%"<br>
							AND Year LIKE "%'.$artwork_year.'%"<br>
							AND Medium LIKE "%'.$medium.'%"<br>
							AND Inscription LIKE "%'.$inscription.'%"<br>
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
							<input type="checkbox" id="ArtistInfo" name="artistInfo" value="true">Artist
							<div id="ArtistDiv" style="margin-bottom: -5%;" hidden>
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
							<input type="checkbox" id="ArtworkInfo" name="artworkInfo" value="true">Artwork
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
						<b> General Info</b>
						<input class="input is-rounded is-focused" type="text" name="general" placeholder="Author/Artwork generic info" style="margin-top:4px; margin-bottom: 25px;"><br>
					</div>
				</div>

				<b>Options</b><br>
				<div style="text-align: left;">
					Order by:
					<div class="dropdown is-left">
						<div class="dropdown-trigger">
							<button id="sortOrder" type="button" class="button" aria-haspopup="true" aria-controls="dropdown-menu3">
								<span>A - Z</span>
								<span class="icon is-small">
									<i class="fas fa-angle-down" aria-hidden="true"></i>
								</span>
							</button>
						</div>
						<div class="dropdown-menu" id="dropdown-menu3" role="menu">
							<div class="dropdown-content">
								<a href="#" class="dropdown-item">A - Z</a>
								<a href="#" class="dropdown-item">Z - A</a>
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
			<div class="tabs is-toggle is-centered ">
				<ul>
					<li class="is-active" id="1">
						<a href="javascript:activateTab( 'tab1div', '1') ">
							<span class="icon is-small "><i class="fas "></i></span>
							<span>Tabella</span>
						</a>
					</li>
					<li class="" id="2">
						<a href="javascript:activateTab( 'tab2div', '2') ">
							<span class="icon is-small "><i class="fas "></i></span>
							<span>Query</span>
						</a>
					</li>
						<li class="" id="3">
						<a href="javascript:activateTab( 'tab3div', '3') ">
							<span class="icon is-small "><i class="fas "></i></span>
							<span>Data</span>
						</a>
					</li>
				</ul>
			</div>
			<div id="tabCtrl">
				<div id="tab1div" style="display: block; ">
					<?php
					
					for($j = 0; $j < $query_count; $j++) {
						$result = $link->query($query);
						if($result->num_rows > 0){ 
							echo '<table border="2"><tr>';
							for($i = 0; $i<count($fields[$j]); $i++)
								echo '<td>' .$fields[$j][$i]. '</td>';

							while($row = $result->fetch_assoc()) {
								echo '</tr><tr>';
								for($i = 0; $i<count($fields[$j]); $i++)
									echo '<td>' .$row[$fields[$j][$i]]. '</td>';
							}
							echo '</tr></table>';
						}
						else echo 'Internal Error OR Empty Result<br><br>';
					}

					$link->close();
					?>
				</div>

				<div id="tab2div" style="display: none; ">
					<?php
						echo 'Query: <br><br>' . $debugQuery;
					?>
				</div>

				<div id="tab3div" style="display: none; ">
					<?php
						echo 'General Info/Title: '. $general .'<br><br>';

						echo 'Filtri Selezionati: '		. $infos[0] . ' ' . $infos[1] .	'<br><br>';

						echo 'Artista - Nome: '			. $artist_name .				'<br>';
						echo 'Artista - Luogo: '		. $places.						'<br>';
						echo 'Artista - Anno: '			. $artist_year.					'<br>';
						echo 'Artista - Genere: '		. $gender .						'<br><br>';

						echo 'Opera - Titolo'			. $title .						'<br>';
						echo 'Opera - Iscrizione: '		. $inscription .				'<br>';
						echo 'Opera - Medium: '			. $medium .						'<br>';
						echo 'Opera - Anno: '			. $artwork_year .				'<br>';
						echo 'Opera - Ruolo Artista: '	. $artist_role .				'<br>';
					?>
				</div>
			</div>
		</div>
	</body>
</html>
