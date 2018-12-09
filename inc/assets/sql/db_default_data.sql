--
-- BiotoPi SQLite3 TEST Database
--

INSERT INTO protocoltypes VALUES(1, 'i2c', '');
INSERT INTO protocoltypes VALUES(2, 'spi', '');
INSERT INTO protocoltypes VALUES(3, 'soap', '');
INSERT INTO protocoltypes VALUES(4, 'json', '');


INSERT INTO protocols VALUES(1, 'raw', 1);
INSERT INTO protocols VALUES(2, 'json', 4);
INSERT INTO protocols VALUES(3, 'cvs', 2);
INSERT INTO protocols VALUES(4, 'xml', 3);


INSERT INTO devicesubtypes VALUES(1, 'analog');
INSERT INTO devicesubtypes VALUES(2, 'digital');


INSERT INTO devicetypes VALUES(1, 'sensor', 1);
INSERT INTO devicetypes VALUES(2, 'sensor', 2);
INSERT INTO devicetypes VALUES(3, 'akteur', 1);
INSERT INTO devicetypes VALUES(4, 'akteur', 2);


INSERT INTO daemontypes VALUES(1, 'once', '2017-11-19 10:44:29.937');
INSERT INTO daemontypes VALUES(2, 'secondly', '2017-11-19 10:45:29.937');
INSERT INTO daemontypes VALUES(3, 'minutly', '2017-11-19 10:46:29.937');
INSERT INTO daemontypes VALUES(4, 'hourly', '2017-11-19 10:47:29.937');
INSERT INTO daemontypes VALUES(5, 'daily', '2017-11-19 10:47:39.937');
INSERT INTO daemontypes VALUES(6, 'weekly', '2017-11-19 10:47:49.937');
INSERT INTO daemontypes VALUES(7, 'monthly', '2017-11-19 10:47:59.937');
INSERT INTO daemontypes VALUES(8, 'quartly', '2017-11-19 10:48:29.937');
INSERT INTO daemontypes VALUES(9, 'yearly', '2017-11-19 10:48:57.937');

-- 
-- Testdata
-- 

INSERT INTO devices VALUES (1,	'temp1',	NULL,	NULL,	0,	1,	NULL,	1,	NULL,	NULL,	NULL,	NULL,	NULL,	'2017-12-31 20:17:05',	'0000-00-00 00:00:00');
INSERT INTO devices VALUES (2,	'hum1',	NULL,	NULL,	0,	1,	NULL,	1,	NULL,	NULL,	NULL,	NULL,	NULL,	'2017-12-31 20:17:12',	'0000-00-00 00:00:00');
INSERT INTO devices VALUES (3,	'single',	NULL,	NULL,	0,	2,	'100',	1,	NULL,	NULL,	NULL,	'[3]',	NULL,	'2018-01-01 12:38:47',	'0000-00-00 00:00:00');
INSERT INTO devices VALUES (4,	'double',	NULL,	NULL,	0,	3,	'75',	2,	NULL,	NULL,	NULL,	'4,5',	NULL,	'2018-01-01 13:54:17',	'0000-00-00 00:00:00');


INSERT INTO daemons VALUES (1,	'temp1_daemon',	1,	1,	'0',	'2018-01-14 09:00:00',	'2018-01-14 09:01:00',	'2018-01-13 21:01:03',	'0000-00-00 00:00:00');
INSERT INTO daemons VALUES (2,	'hum1_daemon',	1,	2,	1,	'2018-01-14 09:00:00',	'2018-01-14 09:20:00',	'2018-01-13 20:11:47',	'0000-00-00 00:00:00');
INSERT INTO daemons VALUES (3,	'test_daemon',	3,	1,	1,	'2018-01-14 09:00:00',	'2018-01-14 09:30:00',	'2018-01-13 20:17:59',	'0000-00-00 00:00:00');
INSERT INTO daemons VALUES (4,	'test_daemon',	3,	3,	1,	'2018-01-14 09:30:00',	'2018-01-14 10:00:00',	'2018-01-13 21:21:32',	'2018-01-14 05:30:51');
INSERT INTO daemons VALUES (5,	'test_daemon',	1,	3,	'0',	NULL,	NULL,	'2018-01-13 21:30:51',	'0000-00-00 00:00:00');


-- INSERT INTO jobs VALUES (1,	1,	1,	'2018-01-14 09:00:00',	NULL,	'0000-00-00 00:00:00',	'0000-00-00 00:00:00');

INSERT INTO system VALUES ( 1, "System Test", "0" );

-- 
-- Sample Queries
-- 

-- get the protocol name and type, for the device who has 'test' in the name
SELECT d.id AS id, d.name AS device, pt.name AS type, p.name AS protocol, pins, exec, params
FROM protocols AS p
INNER JOIN devices d on d.protocol = p.id
INNER JOIN protocoltypes pt on pt.id = p.id
-- WHERE d.name LIKE '%test%';


-- get protocol name and type, for active daemons
SELECT d.id AS id, d.name AS device, da.active AS active, pt.name AS type, p.name AS protocol, pins, exec, params
-- SELECT * 
FROM devices AS d
INNER JOIN daemons da on d.id = da.id
INNER JOIN protocols p on d.protocol = p.id
INNER JOIN protocoltypes pt on pt.id = p.id
WHERE da.active = 1;

-- jobs_v query
SELECT * FROM daemons da
INNER JOIN devices de on de.id = da.id 
	WHERE active not null
	AND active = 1
	AND end >= NOW() 
	AND start <= NOW() 
	ORDER BY updated DESC;


-- Update the 'exec' field on the device which id has the value 1
UPDATE devices
SET exec = '/bin/bash'
WHERE status = 0;




