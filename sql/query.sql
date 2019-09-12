-- numero totale di opere
-- anno con maggiori opere
-- (controllando che non ci sia sempre lo stesso numero di opere)
-- medium maggiormente utilizzati
-- (controlli)
-- artist role maggiormente popolare

USE TATE;
CLEAR;

-- Totale opere per ogni artista (Non necessaria)
-- SELECT COUNT(Artist.ID) Num
-- FROM Artist
-- 	JOIN Artwork ON Artist.ID = Artwork.ArtistId
-- GROUP BY Artist.ID
-- ;

-- Dato un artista anno con il numero maggiore di opere (in questo caso viene detto per tutti gli artisti perché sono SWAG)
SELECT MAX(N)
FROM
	(SELECT Artwork.Year, COUNT(Artwork.ID) N
	FROM Artist JOIN Artwork ON Artist.ID = Artwork.ArtistId
	WHERE Artist.ID = 622
	GROUP BY Artwork.Year) a
;