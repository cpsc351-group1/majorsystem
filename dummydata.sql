/*    USERS   */

TRUNCATE TABLE `committee seat`;
TRUNCATE TABLE `chairman`;
TRUNCATE TABLE `committee`;
TRUNCATE TABLE `user`;

INSERT IGNORE INTO user
  VALUES (00998877, 'John Doe', 'Molecular Biology and Chemistry', 'Associate Professor', '1979-01-01', '2020-01-01', 'Male', 'White');

INSERT IGNORE INTO user
  VALUES (00987987, 'Jill Doe', 'English', 'Professor', '1975-01-01', '2015-01-01', 'Female', 'Black');

INSERT IGNORE INTO user
  VALUES (00978879, 'Beyoncé Knowles-Carter', 'Music', 'Department Lead', '1981-09-04', '2008-05-03', 'Female', 'Black');

INSERT IGNORE INTO user
  VALUES (00966678, 'Claire Boucher', 'Graphic Design', 'Associate Professor', '1988-03-17', '2018-01-04', 'Non-binary', 'White');

INSERT IGNORE INTO user
  VALUES (00942069, 'Elizabeth Grant', 'Philosophy and Religion', 'Professor', '1985-06-21', '2005-04-09', 'Female', 'White');

INSERT IGNORE INTO user
  VALUES (00933833, 'Azealia Banks', 'Communications', 'Adjunct', '1991-05-31', '2020-02-27', 'Female', 'Black');

INSERT IGNORE INTO user
  VALUES (00999919, 'Kurt Cobain', 'Economics', 'Associate Professor', '1967-02-20', '2018-12-20', 'Male', 'White');

INSERT IGNORE INTO user
  VALUES (00955259, 'Dolly Parton', 'Molecular Biology and Chemistry', 'Department Lead', '1946-01-19', '1998-04-30', 'Female', 'White');

INSERT IGNORE INTO user
  VALUES (00982429, 'Mike Lapke', 'Physics, Computer Science and Engineering', 'Associate Professor', '1960-02-03', '06-30-2020', 'Male', 'White');

INSERT IGNORE INTO user
  VALUES (00944004, 'Onika Maraj-Petty', 'Luter School of Business', 'Department Lead', '1982-12-08', '2000-09-15', 'Female', 'Black');

/*    COMMITTEES    */

INSERT IGNORE INTO committee (Name, Description)
  VALUES ('Sustainability Committee', 'Committee that oversees sustainability.');

INSERT IGNORE INTO committee (Name, Description)
  VALUES ('Fun Committee', 'Committee that oversees fun activities.');

INSERT IGNORE INTO committee (Name, Description)
  VALUES ('Grass Committee', 'Committee that oversees the Great Lawn.');

INSERT IGNORE INTO committee (Name, Description)
  VALUES ('Money Committee', 'Committee that oversees financials.');

INSERT IGNORE INTO committee (Name, Description)
  VALUES ('University Faculty on Committees', 'Committee that oversees committees.');

/*  COMMITTEE SEATS   */


INSERT IGNORE INTO committee seat
  VALUES (1, 1, 'Fall 2019', NULL, 00998877);

INSERT IGNORE INTO committee seat
  VALUES (2, 1, 'Spring 2020', NULL, 00987987);

INSERT IGNORE INTO committee seat
  VALUES (3, 1, 'Fall 2020', NULL, 00978879);

INSERT IGNORE INTO committee seat
  VALUES (4, 2, 'Spring 2021', NULL, 00966678);

INSERT IGNORE INTO committee seat
  VALUES (5, 2, 'Fall 2020', NULL, 00942069);

INSERT IGNORE INTO committee seat
  VALUES (6, 3, 'Spring 2021', NULL, 00933833);

INSERT IGNORE INTO committee seat
  VALUES (7, 3, 'Fall 2019', NULL, 00999919);

INSERT IGNORE INTO committee seat
  VALUES (8, 4, 'Spring 2021', NULL, 00955259);

INSERT IGNORE INTO committee seat
  VALUES (9, 5, 'Spring 2020', NULL, 00982429);

INSERT IGNORE INTO committee seat
  VALUES (10, 5, 'Fall 2020', NULL, 00944004);

/*    CHAIRMAN    */

INSERT IGNORE INTO chairman VALUES (1, 00998877);

INSERT IGNORE INTO chairman VALUES (2, 00966678);

INSERT IGNORE INTO chairman VALUES (3, 00933833);

INSERT IGNORE INTO chairman VALUES (4, 00955259);

INSERT IGNORE INTO chairman VALUES (5, 00982429);
