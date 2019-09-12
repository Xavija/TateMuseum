USE TATE;

-- Dato un artista -> Numero totale di opere (Non necessaria)
SELECT Artist.ID, COUNT(Artist.ID) Num
FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
WHERE Artist.ID = 622
GROUP BY Artist.ID
;

-- Dato un artista -> Anno con il numero maggiore di opere

-- METODO 1: NO PHP
SELECT Count.Year Year, Count.N N
FROM
	(SELECT Artwork.Year Year, COUNT(Artwork.ID) N
	FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
	WHERE Artist.ID = 622
	-- ID dell'artista
	GROUP BY Artwork.Year) Count,
	(SELECT MAX(Count2.N) N
	FROM
		(SELECT Artwork.Year Year, COUNT(Artwork.ID) N
		FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
		WHERE Artist.ID = 622
		-- ID dell'artista
		GROUP BY Artwork.Year) Count2) Max
WHERE Count.N = Max.N
;

-- METODO 2: NEED PHP
SELECT Artwork.Year Year, COUNT(Artwork.ID) N
FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
WHERE Artist.ID = 622
-- ID dell'artista
GROUP BY Artwork.Year
ORDER BY N DESC
;


-- Dato un artista -> Medium con il numero maggiore di opere

-- METODO 1: NO PHP
SELECT Count.Medium Medium, Count.N N
FROM
	(SELECT Artwork.Medium Medium, COUNT(Artwork.ID) N
	FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
	WHERE Artist.ID = 622
	-- ID dell'artista
	GROUP BY Artwork.Medium) Count,
	(SELECT MAX(Count2.N) N
	FROM
		(SELECT Artwork.Medium Medium, COUNT(Artwork.ID) N
		FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
		WHERE Artist.ID = 622
		-- ID dell'artista
		GROUP BY Artwork.Medium) Count2) Max
WHERE Count.N = Max.N
;

-- METODO 2: NEED PHP
SELECT Artwork.Medium Medium, COUNT(Artwork.ID) N
FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
WHERE Artist.ID = 622
-- ID dell'artista
GROUP BY Artwork.Medium
ORDER BY N DESC
;


-- Dato un artista -> ArtistRole con il numero maggiore di opere

-- METODO 1: NO PHP
SELECT Count.ArtistRole ArtistRole, Count.N N
FROM
	(SELECT Artwork.ArtistRole ArtistRole, COUNT(Artwork.ID) N
	FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
	WHERE Artist.ID = 622
	-- ID dell'artista
	GROUP BY Artwork.ArtistRole) Count,
	(SELECT MAX(Count2.N) N
	FROM
		(SELECT Artwork.ArtistRole ArtistRole, COUNT(Artwork.ID) N
		FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
		WHERE Artist.ID = 622
		-- ID dell'artista
		GROUP BY Artwork.ArtistRole) Count2) Max
WHERE Count.N = Max.N
;

-- METODO 2: NEED PHP
SELECT Artwork.ArtistRole ArtistRole, COUNT(Artwork.ID) N
FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
WHERE Artist.ID = 622
-- ID dell'artista
GROUP BY Artwork.ArtistRole
ORDER BY N DESC
;