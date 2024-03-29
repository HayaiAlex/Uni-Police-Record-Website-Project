/* Database for use with DIS-COMP4039 Coursework 2 
 * 
 * Please note you do not have to use this.  If you find
 * it easier to use a database of your own design then
 * you are free to do so.  
 *
 * If you do use this database, use it as a starting point only.
 * You will not be able to complete the coursework without 
 * modifying it to some extent.
 */


DROP TABLE IF EXISTS fines;
CREATE TABLE fines (
  Fine_ID int(11) NOT NULL,
  Fine_Amount int(11) NOT NULL,
  Fine_Points int(11) NOT NULL,
  Incident_ID int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO fines (Fine_ID, Fine_Amount, Fine_Points, Incident_ID) VALUES
(1, 2000, 6, 3),
(2, 50, 0, 2),
(3, 500, 3, 4);

DROP TABLE IF EXISTS incident;
CREATE TABLE incident (
  Incident_ID int(11) NOT NULL,
  Vehicle_ID int(11) DEFAULT NULL,
  People_ID int(11) DEFAULT NULL,
  Incident_Date date NOT NULL,
  Incident_Report varchar(500) NOT NULL,
  Offence_ID int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO incident (Incident_ID, Vehicle_ID, People_ID, Incident_Date, Incident_Report, Offence_ID) VALUES
(1, 15, 4, '2017-12-01', '40mph in a 30 limit', 1),
(2, 20, 8, '2017-11-01', 'Double parked', 4),
(3, 13, 4, '2017-09-17', '110mph on motorway', 1),
(4, 14, 2, '2017-08-22', 'Failure to stop at a red light - travelling 25mph', 8),
(5, 13, 4, '2017-10-17', 'Not wearing a seatbelt on the M1', 3);

DROP TABLE IF EXISTS offence;
CREATE TABLE offence (
  Offence_ID int(11) NOT NULL,
  Offence_description varchar(50) NOT NULL,
  Offence_maxFine int(11) NOT NULL,
  Offence_maxPoints int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO offence (Offence_ID, Offence_description, Offence_maxFine, Offence_maxPoints) VALUES
(1, 'Speeding', 1000, 3),
(2, 'Speeding on a motorway', 2500, 6),
(3, 'Seat belt offence', 500, 0),
(4, 'Illegal parking', 500, 0),
(5, 'Drink driving', 10000, 11),
(6, 'Driving without a licence', 10000, 0),
(7, 'Driving without a licence', 10000, 0),
(8, 'Traffic light offences', 1000, 3),
(9, 'Cycling on pavement', 500, 0),
(10, 'Failure to have control of vehicle', 1000, 3),
(11, 'Dangerous driving', 1000, 11),
(12, 'Careless driving', 5000, 6),
(13, 'Dangerous cycling', 2500, 0);

DROP TABLE IF EXISTS ownership;
CREATE TABLE ownership (
  People_ID int(11) NOT NULL,
  Vehicle_ID int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO ownership (People_ID, Vehicle_ID) VALUES
(3, 12),
(8, 20),
(4, 15),
(4, 13),
(1, 16),
(2, 14),
(5, 17),
(6, 18),
(7, 21);

DROP TABLE IF EXISTS people;
CREATE TABLE people (
  People_ID int(11) NOT NULL,
  People_name varchar(50) NOT NULL,
  People_address varchar(50) DEFAULT NULL,
  People_licence varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO people (People_ID, People_name, People_address, People_licence) VALUES
(1, 'James Smith', '23 Barnsdale Road, Leicester', 'SMITH92LDOFJJ829'),
(2, 'Jennifer Allen', '46 Bramcote Drive, Nottingham', 'ALLEN88K23KLR9B3'),
(3, 'John Myers', '323 Derby Road, Nottingham', 'MYERS99JDW8REWL3'),
(4, 'James Smith', '26 Devonshire Avenue, Nottingham', 'SMITHR004JFS20TR'),
(5, 'Terry Brown', '7 Clarke Rd, Nottingham', 'BROWND3PJJ39DLFG'),
(6, 'Mary Adams', '38 Thurman St, Nottingham', 'ADAMSH9O3JRHH107'),
(7, 'Neil Becker', '6 Fairfax Close, Nottingham', 'BECKE88UPR840F9R'),
(8, 'Angela Smith', '30 Avenue Road, Grantham', 'SMITH222LE9FJ5DS'),
(9, 'Xene Medora', '22 House Drive, West Bridgford', 'MEDORH914ANBB223');

DROP TABLE IF EXISTS vehicle;
CREATE TABLE vehicle (
  Vehicle_ID int(11) NOT NULL,
  Vehicle_make varchar(20) NOT NULL,
  Vehicle_model varchar(20) NOT NULL,
  Vehicle_colour varchar(20) NOT NULL,
  Vehicle_licence varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO vehicle (Vehicle_ID, Vehicle_make, Vehicle_model, Vehicle_colour, Vehicle_licence) VALUES
(12, 'Ford', 'Fiesta', 'Blue', 'LB15AJL'),
(13, 'Ferrari', '458', 'Red', 'MY64PRE'),
(14, 'Vauxhall', 'Astra', 'Silver', 'FD65WPQ'),
(15, 'Honda', 'Civic', 'Green', 'FJ17AUG'),
(16, 'Toyota', 'Prius', 'Silver', 'FP16KKE'),
(17, 'Ford', 'Mondeo', 'Black', 'FP66KLM'),
(18, 'Ford', 'Focus', 'White', 'DJ14SLE'),
(20, 'Nissan', 'Pulsar', 'Red', 'NY64KWD'),
(21, 'Renault', 'Scenic', 'Silver', 'BC16OEA'),
(22, 'Hyundai', 'i30', 'Grey', 'AD223NG');

DROP TABLE IF EXISTS users;
CREATE TABLE users (
  User_name varchar(50) NOT NULL,
  User_password varchar(50) NOT NULL,
  User_admin int(11)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO users (User_name, User_password, User_admin) VALUES
('mcnulty', 'plod123', 0),
('moreland', 'fuzz42', 0),
('daniels', 'copper99', 1),
('admin', 'admin', 1);

DROP TABLE IF EXISTS audit;
CREATE TABLE audit (
  Audit_ID int(11) NOT NULL,
  Audit_timestamp timestamp NOT NULL,
  Audit_username varchar(50),
  Audit_action varchar(100) NOT NULL,
  Audit_search_term varchar(50),
  Audit_People_History_ID int(11),
  Audit_Vehicle_History_ID int(11),
  Audit_Incident_History_ID int(11),
  Audit_Fines_History_ID int(11),
  Audit_Ownership_History_ID int(11),
  Audit_Users_History_ID int(11)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS audit_people_history;
CREATE TABLE audit_people_history (
  Audit_People_History_ID int(11) NOT NULL,
  Audit_People_people_ID int(11) NOT NULL,
  Audit_People_old_name varchar(50) DEFAULT NULL,
  Audit_People_old_address varchar(50) DEFAULT NULL,
  Audit_People_old_licence varchar(16) DEFAULT NULL,
  Audit_People_new_name varchar(50) DEFAULT NULL,
  Audit_People_new_address varchar(50) DEFAULT NULL,
  Audit_People_new_licence varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS audit_vehicle_history;
CREATE TABLE audit_vehicle_history (
  Audit_Vehicle_History_ID int(11) NOT NULL,
  Audit_Vehicle_vehicle_ID int(11) NOT NULL,
  Audit_Vehicle_old_make varchar(20) DEFAULT NULL,
  Audit_Vehicle_old_model varchar(20) DEFAULT NULL,
  Audit_Vehicle_old_colour varchar(7) DEFAULT NULL,
  Audit_Vehicle_old_licence varchar(7) DEFAULT NULL,
  Audit_Vehicle_new_make varchar(20) DEFAULT NULL,
  Audit_Vehicle_new_model varchar(20) DEFAULT NULL,
  Audit_Vehicle_new_colour varchar(7) DEFAULT NULL,
  Audit_Vehicle_new_licence varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS audit_incident_history;
CREATE TABLE audit_incident_history (
  Audit_Incident_History_ID int(11) NOT NULL,
  Audit_Incident_incident_ID int(11) NOT NULL,
  Audit_Incident_old_vehicle_ID int(11) DEFAULT NULL,
  Audit_Incident_old_people_ID int(11) DEFAULT NULL,
  Audit_Incident_old_date date DEFAULT NULL,
  Audit_Incident_old_report varchar(500) DEFAULT NULL,
  Audit_Incident_old_offence_ID int(11) DEFAULT NULL,
  Audit_Incident_new_vehicle_ID int(11) DEFAULT NULL,
  Audit_Incident_new_people_ID int(11) DEFAULT NULL,
  Audit_Incident_new_date date DEFAULT NULL,
  Audit_Incident_new_report varchar(500) DEFAULT NULL,
  Audit_Incident_new_offence_ID int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS audit_fines_history;
CREATE TABLE audit_fines_history (
  Audit_Fines_History_ID int(11) NOT NULL,
  Audit_Fines_fine_ID int(11) NOT NULL,
  Audit_Fines_old_amount int(11) DEFAULT NULL,
  Audit_Fines_old_points int(11) DEFAULT NULL,
  Audit_Fines_new_amount int(11) NOT NULL,
  Audit_Fines_new_points int(11) NOT NULL,
  Audit_Fines_incident_ID int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS audit_ownership_history;
CREATE TABLE audit_ownership_history (
  Audit_Ownership_History_ID int(11) NOT NULL,
  Audit_Ownership_old_people_ID int(11) DEFAULT NULL,
  Audit_Ownership_old_vehicle_ID int(11) DEFAULT NULL,
  Audit_Ownership_new_people_ID int(11) DEFAULT NULL,
  Audit_Ownership_new_vehicle_ID int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS audit_users_history;
CREATE TABLE audit_users_history (
  Audit_Users_History_ID int(11) NOT NULL,
  Audit_Users_old_username varchar(50) DEFAULT NULL,
  Audit_Users_old_password varchar(50) DEFAULT NULL,
  Audit_Users_old_admin int(11) DEFAULT NULL,
  Audit_Users_new_username varchar(50) DEFAULT NULL,
  Audit_Users_new_password varchar(50) DEFAULT NULL,
  Audit_Users_new_admin int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE fines
  ADD PRIMARY KEY (Fine_ID),
  ADD KEY incident_ID (Incident_ID);

ALTER TABLE incident
  ADD PRIMARY KEY (Incident_ID),
  ADD KEY fk_incident_vehicle (Vehicle_ID),
  ADD KEY fk_incident_people (People_ID),
  ADD KEY fk_incident_offence (Offence_ID);

ALTER TABLE offence
  ADD PRIMARY KEY (Offence_ID);

ALTER TABLE ownership
  ADD KEY fk_people (People_ID),
  ADD KEY fk_vehicle (Vehicle_ID);

ALTER TABLE people
  ADD PRIMARY KEY (People_ID);

ALTER TABLE vehicle
  ADD PRIMARY KEY (Vehicle_ID);

ALTER TABLE users
  ADD PRIMARY KEY (User_name);

ALTER TABLE audit
  ADD PRIMARY KEY (Audit_ID);
ALTER TABLE audit_people_history
  ADD PRIMARY KEY (Audit_People_History_ID);
ALTER TABLE audit_vehicle_history
  ADD PRIMARY KEY (Audit_Vehicle_History_ID);
ALTER TABLE audit_incident_history
  ADD PRIMARY KEY (Audit_Incident_History_ID);
ALTER TABLE audit_fines_history
  ADD PRIMARY KEY (Audit_Fines_History_ID);
ALTER TABLE audit_ownership_history
  ADD PRIMARY KEY (Audit_Ownership_History_ID);
ALTER TABLE audit_users_history
  ADD PRIMARY KEY (Audit_Users_History_ID);

ALTER TABLE fines
  MODIFY Fine_ID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE incident
  MODIFY Incident_ID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE offence
  MODIFY Offence_ID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
ALTER TABLE people
  MODIFY People_ID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
ALTER TABLE vehicle
  MODIFY Vehicle_ID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
ALTER TABLE audit
  MODIFY Audit_ID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE audit_people_history
  MODIFY Audit_People_History_ID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE audit_vehicle_history
  MODIFY Audit_Vehicle_History_ID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE audit_incident_history
  MODIFY Audit_Incident_History_ID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE audit_fines_history
  MODIFY Audit_Fines_History_ID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE audit_ownership_history
  MODIFY Audit_Ownership_History_ID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE audit_users_history
  MODIFY Audit_Users_History_ID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;


ALTER TABLE fines
  ADD CONSTRAINT fk_fines FOREIGN KEY (Incident_ID) REFERENCES incident (Incident_ID);

ALTER TABLE incident
  ADD CONSTRAINT fk_incident_offence FOREIGN KEY (Offence_ID) REFERENCES offence (Offence_ID),
  ADD CONSTRAINT fk_incident_people FOREIGN KEY (People_ID) REFERENCES people (People_ID),
  ADD CONSTRAINT fk_incident_vehicle FOREIGN KEY (Vehicle_ID) REFERENCES vehicle (Vehicle_ID);

ALTER TABLE ownership
  ADD CONSTRAINT fk_person FOREIGN KEY (People_ID) REFERENCES people (People_ID),
  ADD CONSTRAINT fk_vehicle FOREIGN KEY (Vehicle_ID) REFERENCES vehicle (Vehicle_ID);

ALTER TABLE audit
  ADD CONSTRAINT fk_pereson_history FOREIGN KEY (Audit_People_History_ID) REFERENCES audit_people_history (Audit_People_History_ID),
  ADD CONSTRAINT fk_vehicle_history FOREIGN KEY (Audit_Vehicle_History_ID) REFERENCES audit_vehicle_history (Audit_Vehicle_History_ID),
  ADD CONSTRAINT fk_incident_history FOREIGN KEY (Audit_Incident_History_ID) REFERENCES audit_incident_history (Audit_Incident_History_ID),
  ADD CONSTRAINT fk_fines_history FOREIGN KEY (Audit_Fines_History_ID) REFERENCES audit_fines_history (Audit_Fines_History_ID),
  ADD CONSTRAINT fk_ownership_history FOREIGN KEY (Audit_Ownership_History_ID) REFERENCES audit_ownership_history (Audit_Ownership_History_ID),
  ADD CONSTRAINT fk_users_history FOREIGN KEY (Audit_Users_History_ID) REFERENCES audit_users_history (Audit_Users_History_ID);