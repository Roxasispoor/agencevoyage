-- Base de données de l'application agence de voyages

-- créer avec :
--  $ sqlite3 agvoy.sqlite < create-db-sqlite.sql

PRAGMA encoding="UTF-8";

-- circuit
--  Circuits de tourisme

CREATE TABLE circuit (
   id INTEGER NOT NULL,
   description CLOB NOT NULL,
   pays_depart VARCHAR(30) DEFAULT NULL,
   ville_depart VARCHAR(30) DEFAULT NULL,
   ville_arrivee VARCHAR(30) DEFAULT NULL,
   duree_circuit SMALLINT DEFAULT NULL,
PRIMARY KEY(id));

-- circuits d'exemple
INSERT INTO `circuit`(`id`,`description`, pays_depart, ville_depart, ville_arrivee, duree_circuit) 
            VALUES (1,'Andalousie', 'Espagne', 'Grenade', 'Seville', 4);
INSERT INTO `circuit`(`id`,`description`, pays_depart, ville_depart, ville_arrivee, duree_circuit) 
            VALUES (2,'Vietnam', 'Vietnam', 'Hanoï', 'Hô Chi Minh', 4);
INSERT INTO `circuit`(`id`,`description`, pays_depart, ville_depart, ville_arrivee, duree_circuit) 
            VALUES (3,'Ile de France', 'France', 'Versailles', 'Paris', 2);
INSERT INTO `circuit`(`id`,`description`, pays_depart, ville_depart, ville_arrivee, duree_circuit) 
            VALUES (4,'Italie', 'Italie', 'Florence', 'Rome', 5);

-- etape
--  Etapes des circuits
            
CREATE TABLE etape (
	id INTEGER NOT NULL, 
	circuit_id INTEGER DEFAULT NULL, 
	numero_etape SMALLINT NOT NULL, 
	ville_etape VARCHAR(30) NOT NULL, 
	nombre_jours SMALLINT NOT NULL, 
	PRIMARY KEY(id), 
	CONSTRAINT FK_etape_circuit_id_to_circuit 
		FOREIGN KEY (circuit_id) 
		REFERENCES circuit (id) 
		NOT DEFERRABLE INITIALLY IMMEDIATE);

-- etapes d'exemple

INSERT INTO `etape`(`id`,`circuit_id`, numero_etape, ville_etape, nombre_jours) 
            VALUES (1, 1, 1, 'Grenade', 1);
INSERT INTO `etape`(`id`,`circuit_id`, numero_etape, ville_etape, nombre_jours) 
            VALUES (2, 1, 2, 'Cordoue', 2);
INSERT INTO `etape`(`id`,`circuit_id`, numero_etape, ville_etape, nombre_jours) 
            VALUES (3, 1, 3, 'Seville', 1);

INSERT INTO `etape`(`id`,`circuit_id`, numero_etape, ville_etape, nombre_jours) 
            VALUES (4, 2, 1, 'Hanoï', 1);
INSERT INTO `etape`(`id`,`circuit_id`, numero_etape, ville_etape, nombre_jours) 
            VALUES (5, 2, 2, 'Dà Nang', 1);
INSERT INTO `etape`(`id`,`circuit_id`, numero_etape, ville_etape, nombre_jours) 
            VALUES (6, 2, 3, 'Hôï An', 1);
INSERT INTO `etape`(`id`,`circuit_id`, numero_etape, ville_etape, nombre_jours) 
            VALUES (7, 2, 4, 'Hô Chi Minh', 1);

INSERT INTO `etape`(`id`,`circuit_id`, numero_etape, ville_etape, nombre_jours) 
            VALUES (8, 3, 1, 'Versailles', 1);
INSERT INTO `etape`(`id`,`circuit_id`, numero_etape, ville_etape, nombre_jours) 
            VALUES (9, 3, 2, 'Paris', 1);

INSERT INTO `etape`(`id`,`circuit_id`, numero_etape, ville_etape, nombre_jours) 
            VALUES (10, 4, 1, 'Florence', 1);
INSERT INTO `etape`(`id`,`circuit_id`, numero_etape, ville_etape, nombre_jours) 
            VALUES (11, 4, 2, 'Sienne', 1);
INSERT INTO `etape`(`id`,`circuit_id`, numero_etape, ville_etape, nombre_jours) 
            VALUES (12, 4, 3, 'Pise', 1);
INSERT INTO `etape`(`id`,`circuit_id`, numero_etape, ville_etape, nombre_jours) 
            VALUES (13, 4, 4, 'Rome', 2);
       
CREATE INDEX IDX_etape_circuit_id ON etape (circuit_id);


-- programmation_circuit
--  Circuits programmés

CREATE TABLE programmation_circuit (
	id INTEGER NOT NULL, 
	circuit_id INTEGER DEFAULT NULL, 
	date_depart DATE NOT NULL, 
	nombre_personnes SMALLINT NOT NULL, 
	prix SMALLINT NOT NULL, 
	PRIMARY KEY(id), 
	CONSTRAINT FK_programmation_circuit_circuit_id_to_circuit 
		FOREIGN KEY (circuit_id) 
		REFERENCES circuit (id) 
		NOT DEFERRABLE INITIALLY IMMEDIATE);

-- programmations d'exemple

INSERT INTO `programmation_circuit`(`id`,`circuit_id`, date_depart, nombre_personnes, prix) 
            VALUES (1, 1, '2018-07-10', 10, 850);
INSERT INTO `programmation_circuit`(`id`,`circuit_id`, date_depart, nombre_personnes, prix) 
            VALUES (2, 2, '2017-08-16', 10, 1500);
INSERT INTO `programmation_circuit`(`id`,`circuit_id`, date_depart, nombre_personnes, prix) 
            VALUES (3, 3, '2016-05-15', 12, 120);
INSERT INTO `programmation_circuit`(`id`,`circuit_id`, date_depart, nombre_personnes, prix) 
            VALUES (4, 4, '2017-10-13', 15, 800);

	
CREATE INDEX IDX_programmation_circuit_circuit_id ON programmation_circuit (circuit_id);
