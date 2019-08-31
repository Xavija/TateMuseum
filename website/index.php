<!DOCTYPE html>
<html>

	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.2/css/bulma.min.css">
		<!--<link rel="stylesheet" href="/home/michele/Desktop/bulma-0.7.5/css/bulma.min.css"> -->
		<link rel="stylesheet" type="text/css" href="style.css">
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script type="text/javascript" src="scripts.js"></script>
	</head>

	<body>
		<div class="split left">
			<!--<form action="index.html" method="get">-->
				<form action="connessione.php" method="get">
				<br>
				<b>General Info</b>
				<div class="control">
					<textarea class="textarea has-fixed-size is-focused" placeholder="General Info" name="search"></textarea>
				</div>
				<br>
				<b>Filters</b><br>
				<div class=alignLeft>
					<input type="checkbox" id="ArtistInfo">Artist</input>
					<div id="ArtistDiv" hidden>
						<div class="control">
							<input class="input is-rounded is-focused" type="number" min=0 placeholder="Artist ID" name="author">
						</div>
					</div><br>

					<input type="checkbox" id="DatesInfo">Dates</input>
					<div id="DatesDiv" hidden>
						<div class="control">
							<input class="input is-rounded is-focused" type="number" min=0 placeholder="Date" name="date">
						</div>
					</div><br>

					<input type="checkbox" id="ArtworkInfo">Artwork</input>
					<div id="ArtworkDiv" hidden>
						<div class="control">
							<input class="input is-rounded is-focused" type="number" min=0 placeholder="Artwork ID" name="artwork">
						</div>
					</div><br>
				</div>
				<br>
				<br>
				<b>Options</b><br>
				<div class=alignLeft>
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
								<a href="javascript:changeDropdown('A - Z')" class="dropdown-item">
									A - Z
								</a>
								<a href="javascript:changeDropdown('Z - A')" class="dropdown-item">
									Z - A
								</a>
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
			<div class="tabs is-toggle is-centered">
				<ul>
					<li class="is-active" id="1">
						<a href="javascript:activateTab('tab1div', '1')">
							<span class="icon is-small">
								<i class="fas" aria-hidden="true"></i>
							</span>
							<span>
								<centre>Tab 1</centre>
							</span>
						</a>
					</li>
					<li class="" id="2">
						<a href="javascript:activateTab('tab2div', '2')">
							<span class="icon is-small">
								<i class="fas"></i>
							</span>
							<span>
								<centre>Tab 2</centre>
							</span>
						</a>
					</li>
					<li class="" id="3">
						<a href="javascript:activateTab('tab3div', '3')">
							<span class="icon is-small">
								<i class="fas"></i>
							</span>
							<span>
								<centre>Tab 3</centre>
							</span>
						</a>
					</li>
					<li class="" id="4">
						<a href="javascript:activateTab('tab4div', '4')">
							<span class="icon is-small">
								<i class="fas"></i>
							</span>
							<span>
								<centre>Tab 4</centre>
							</span>
						</a>
					</li>
				</ul>
			</div>
			<div id="tabCtrl">
				<div id="tab1div" style="display: block;">Page 1</div>
				<div id="tab2div" style="display: none;">Page 1<br>Page 2</div>
				<div id="tab3div" style="display: none;">Page 1<br>Page 2<br>Page 3</div>
				<div id="tab4div" style="display: none;">Page 1<br>Page 2<br>Page 3<br>Page 4</div>
			</div>
		</div>
		</div>
	</body>

</html>