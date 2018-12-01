--
-- BiotoPi SQLite3 Database Schema
--
-- On SQLite replace "AUTO_INCREMENT" by "AUTOINCREMENT"
--

-- CLEANUP
DROP TABLE IF EXISTS jobs;
DROP TABLE IF EXISTS daemons;
DROP TABLE IF EXISTS daemontypes;
DROP TABLE IF EXISTS devices;
DROP TABLE IF EXISTS protocols;
DROP TABLE IF EXISTS protocoltypes;
DROP TABLE IF EXISTS devicetypes;
DROP TABLE IF EXISTS subtypes;
DROP TABLE IF EXISTS config;
DROP VIEW IF EXISTS jobs_v;
-- END CLEANUP

CREATE TABLE config(
	id			INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`key`		TEXT NOT NULL,
	value		TEXT NOT NULL,
	comment	TEXT NULL,
  created	TEXT NULL,
  updated	TEXT NULL,
);

CREATE TABLE subtypes(
  id			INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name		TEXT NOT NULL,
  created	TEXT NULL,
  updated TEXT NULL,
);

CREATE TABLE devicetypes(
  id				INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name			TEXT NOT NULL, 
  subtype		INTEGER NOT NULL,
  created		TEXT NULL,
  updated		TEXT NULL,
  FOREIGN KEY(subtype) REFERENCES subtypes(id)
);

CREATE TABLE protocoltypes(
  id			INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name		TEXT NOT NULL, 
  data		TEXT,
  created TEXT NULL,
  updated TEXT NULL,
);


CREATE TABLE protocols(
  id			INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name		TEXT NOT NULL, 
  type		INTEGER NOT NULL,
  created	TEXT NULL,
  updated	TEXT NULL,
  FOREIGN KEY(type) REFERENCES protocoltypes(id)
);

DROP TABLE IF EXISTS devices;
CREATE TABLE devices(
  id				INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT, 
  name			TEXT NOT NULL,
  js				TEXT NULL,
  html			TEXT NULL,
  status		INTEGER NOT NULL DEFAULT 0,
  type			INTEGER NOT NULL,
  threshold	TEXT NULL,
  protocol	INTEGER NOT NULL,
  data			TEXT NULL,
  function	TEXT NULL,
  params		TEXT NULL,
  pins			TEXT NULL,
  exec			TEXT NULL,
  created		TEXT NULL,
  updated		TEXT NULL,
  FOREIGN KEY(type) REFERENCES devicetypes(id),
  FOREIGN KEY(protocol) REFERENCES protocols(id)
);

CREATE TABLE daemontypes(
  id			INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name		TEXT NOT NULL,
  value   TEXT NULL,
  created	TEXT NULL,
  updated	TEXT NULL,
);

CREATE TABLE daemons(
  id			INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name		TEXT NOT NULL, 
  type		INTEGER NOT NULL,
  device	INTEGER NOT NULL,
  active	INTEGER NOT NULL DEFAULT 0,
  running INTEGER NOT NULL DEFAULT 0,
  start		TEXT NULL,
  end			TEXT NULL,
  created	TEXT NULL,
  updated	TEXT NULL,
  FOREIGN KEY(type) REFERENCES daemontypes(id),
  FOREIGN KEY(device) REFERENCES devices(id)
);

CREATE TABLE jobs (
  id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  id_devices	INTEGER NOT NULL,
  id_daemons	INTEGER NOT NULL,
  start				TEXT NOT NULL,
  end					TEXT NULL,
  created			TEXT NULL,
  updated			TEXT NULL,
  FOREIGN KEY (id_devices) REFERENCES devices (id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  FOREIGN KEY (id_daemons) REFERENCES daemons (id) ON DELETE NO ACTION ON UPDATE NO ACTION
);

CREATE VIEW jobs_v AS 
	SELECT da.id AS daemon, de.id AS device, da.name AS name, da.type AS dtype, dt.value AS dtypevalue, da.running AS running, da.start AS start, da.end AS end, da.updated AS updated, de.exec AS exec, de.pins AS pins, de.params AS params
	FROM daemons AS da
	INNER JOIN devices de on de.id = da.device 
	INNER JOIN daemontypes dt on dt.id = da.id 
	WHERE active is not null
		AND active = 1
		AND end >= strftime('%Y-%m-%d %H-%M','now') 
		AND start <= strftime('%Y-%m-%d %H-%M','now')
	ORDER BY updated DESC;
;





