USE TATE;

-- SET foreign_key_checks = 0;
SET NAMES default;

LOAD DATA LOCAL INFILE 'project_data/artist_data.csv'
INTO TABLE Artist FIELDS 
TERMINATED BY ',' 
ENCLOSED BY '"' 
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

SHOW WARNINGS;

LOAD DATA LOCAL INFILE 'project_data/artwork_data.csv'
INTO TABLE Artwork FIELDS 
TERMINATED BY ',' 
ENCLOSED BY '"' 
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

SHOW WARNINGS;

-- SET foreign_key_checks = 1;