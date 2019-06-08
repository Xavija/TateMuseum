CREATE DATABASE TATE;
USE TATE;

-- id,name,gender,dates,yearOfBirth,yearOfDeath,placeOfBirth,placeOfDeath,url
CREATE TABLE Artist (
	id          	    INT             NOT NULL,
	name        	    VARCHAR(200)    NOT NULL,
	gender      	    VARCHAR(6)      NOT NULL, -- male/female
	dates       	    VARCHAR(10),
	yearOfBirth 	    INT,
	yearOfDeath 	    INT,
	placeOfBirth	    VARCHAR(200),
	placeOfDeath	    VARCHAR(200),
	url         	    VARCHAR(500),
	PRIMARY KEY(id) 
);

-- id,accession_number,artist,artistRole,artistId,title,dateText,medium,creditLine,year,acquisitionYear,
-- dimensions,width,height,depth,units,inscription,thumbnailCopyright,thumbnailUrl,url
CREATE TABLE Artwork (
	id                  INT             NOT NULL    AUTO_INCREMENT,                         -- 1035
	accession_number    CHAR(6)         NOT NULL    UNIQUE,                                 -- A00001
	artist              VARCHAR(200)    NOT NULL,                                           -- "Blake, Robert"
	artistRole          VARCHAR(50),                                                        -- artist
	artistId            INT,					                                            -- 38
	title               VARCHAR(300)    NOT NULL,                                           -- A Figure Bowing before a Seated Old Man with his Arm Outstretched in Benediction. Verso: Indecipherable Sketch
	dateText            VARCHAR(300),                                                       -- date not known
	medium              VARCHAR(200),                                                       -- "Watercolour, ink, chalk and graphite on paper. Verso: graphite on paper"
	creditLine          VARCHAR(200),                                                       -- Presented by Mrs John Richmond 1922
	year                VARCHAR(50),														-- NULL
	acquisitionYear		INT,																-- 1922
	dimensions			VARCHAR(50),														-- support: 394 x 419 mm
	width				INT,																-- 394
	height 				INT, 																-- 419
	depth 				INT,																-- NULL
	units				CHAR(2),															-- mm
	inscription			VARCHAR(300),														-- NULL
	thumbnailCopyright 	VARCHAR(200),														-- NULL
	thumbnailUrl		VARCHAR(500),														-- <url>
	url 				VARCHAR(500),														-- <url>

	PRIMARY KEY(id),
	FOREIGN KEY(artistId)
		REFERENCES Artist(id)
		ON UPDATE CASCADE ON DELETE SET NULL -- integrità referenziale?
);
