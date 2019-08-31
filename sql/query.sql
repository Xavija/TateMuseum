USE TATE;

-- Ricerca di un artista
-- per nome e sesso
SELECT DISTINCT Name, Dates
FROM Artist
WHERE Name LIKE "BLAKE, %"
AND Gender LIKE "Male" 
;

-- SELECT DISTINCT A.Name, A.Dates
-- FROM Artist A JOIN Artwork B ON A.ID = B.ArtistId
-- WHERE B.Title = "";

-- Tutte le opere di ogni artista
SELECT A.Name, B.Title, B.Year
FROM Artist A JOIN Artwork B on A.ID=B.ArtistId
GROUP BY A.Name 
;
