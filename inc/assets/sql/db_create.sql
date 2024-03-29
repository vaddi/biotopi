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
DROP TABLE IF EXISTS devicesubtypes;
DROP TABLE IF EXISTS config;
DROP TABLE IF EXISTS system;
DROP TABLE IF EXISTS data;
DROP VIEW IF EXISTS jobs_v;
-- END CLEANUP

CREATE TABLE config(
	id			INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`key`		TEXT NOT NULL,
	value		TEXT NOT NULL,
	comment	TEXT NULL,
  created	TEXT NULL,
  updated	TEXT NULL
);

CREATE TABLE devicesubtypes(
  id			INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name		TEXT NOT NULL,
  created	TEXT NULL,
  updated TEXT NULL
);

CREATE TABLE devicetypes(
  id				INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name			TEXT NOT NULL, 
  devicesubtypes		INTEGER NOT NULL,
  created		TEXT NULL,
  updated		TEXT NULL,
  FOREIGN KEY(devicesubtypes) REFERENCES devicesubtypes(id)
);

CREATE TABLE protocoltypes(
  id			INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name		TEXT NOT NULL, 
  data		TEXT,
  created TEXT NULL,
  updated TEXT NULL
);

CREATE TABLE protocols(
  id			INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name		TEXT NOT NULL, 
  type		INTEGER NOT NULL,
  created	TEXT NULL,
  updated	TEXT NULL,
  FOREIGN KEY(type) REFERENCES protocoltypes(id)
);

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
  value   TEXT NOT NULL,
  created	TEXT NULL,
  updated	TEXT NULL
);

CREATE TABLE daemons(
  id			INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name		TEXT NOT NULL, 
  type		INTEGER NOT NULL,
  device	INTEGER NOT NULL,
  active	INTEGER NOT NULL DEFAULT 0,
  start		TEXT NULL,
  end			TEXT NULL,
  created	TEXT NULL,
  updated	TEXT NULL,
  FOREIGN KEY(type) REFERENCES daemontypes(id),
  FOREIGN KEY(device) REFERENCES devices(id)
);

CREATE TABLE jobs (
  id          INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  id_devices	INTEGER NOT NULL,
  id_daemons	INTEGER NOT NULL,
  start				TEXT NOT NULL,
  end					TEXT NULL,
  created			TEXT NULL,
  updated			TEXT NULL,
  FOREIGN KEY (id_devices) REFERENCES devices (id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  FOREIGN KEY (id_daemons) REFERENCES daemons (id) ON DELETE NO ACTION ON UPDATE NO ACTION
);

CREATE TABLE system (
  id      INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name    TEXT NULL,
  value   TEXT NOT NULL,
  created	TEXT NOT NULL DEFAULT (datetime('now','localtime')),
  updated	TEXT NOT NULL DEFAULT (datetime('now','localtime'))
);

CREATE TABLE data (
  id        INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  datetime	TEXT NOT NULL DEFAULT (datetime('now','localtime')),
  device    INTEGER NOT NULL,
  value     TEXT NOT NULL DEFAULT '{}',
  FOREIGN KEY (device) REFERENCES devices (id) ON DELETE NO ACTION ON UPDATE NO ACTION
);

CREATE VIEW jobs_v AS 
  SELECT da.id AS daemon, 
    de.id AS device, 
    da.name AS name, 
    da.type AS dtype, 
    da.start AS start, 
    da.end AS end, 
    da.updated AS updated,
    da.created AS created, 
    de.exec AS exec, 
    de.pins AS pins, 
    de.params AS params
  FROM daemons AS da
  INNER JOIN devices de on de.id = da.device
  INNER JOIN daemontypes dt on dt.id = da.type
  WHERE active is not null
    AND active = 1
    AND strftime( '%s', end ) >= strftime('%s','now')
    OR strftime( '%s', start ) >= strftime('%s','now')
  -- strftime('%Y-%m-%d %H:%M:%S','now')
  ORDER BY updated DESC
;





