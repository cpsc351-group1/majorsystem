/*    USERS   */

INSERT IGNORE INTO user
  VALUES (00998877, 'John Doe', 'Molecular Biology and Chemistry', 'Associate Professor', '1979-01-01', '2020-01-01', 'Male', 'White', NULL);

INSERT IGNORE INTO user
  VALUES (00987987, 'Jill Doe', 'English', 'Professor', '1975-01-01', '2015-01-01', 'Female', 'Black', NULL);

INSERT IGNORE INTO user
  VALUES (00978879, 'Beyoncé Knowles-Carter', 'Music', 'Department Lead', '1981-09-04', '2008-05-03', 'Female', 'Black', NULL);

INSERT IGNORE INTO user
  VALUES (00966678, 'Claire Boucher', 'Graphic Design', 'Associate Professor', '1988-03-17', '2018-01-04', 'Non-binary', 'White', NULL);

INSERT IGNORE INTO user
  VALUES (00942069, 'Elizabeth Grant', 'Philosophy and Religion', 'Professor', '1985-06-21', '2005-04-09', 'Female', 'White', NULL);

INSERT IGNORE INTO user
  VALUES (00933833, 'Azealia Banks', 'Communications', 'Adjunct', '1991-05-31', '2020-02-27', 'Female', 'Black', NULL);

INSERT IGNORE INTO user
  VALUES (00999919, 'Kurt Cobain', 'Economics', 'Associate Professor', '1967-02-20', '2018-12-20', 'Male', 'White', NULL);

INSERT IGNORE INTO user
  VALUES (00955259, 'Dolly Parton', 'Molecular Biology and Chemistry', 'Department Lead', '1946-01-19', '1998-04-30', 'Female', 'White', NULL);

INSERT IGNORE INTO user
  VALUES (00982429, 'Mike Lapke', 'Physics, Computer Science and Engineering', 'Associate Professor', '1960-02-03', '06-30-2020', 'Male', 'White', NULL);

INSERT IGNORE INTO user
  VALUES (00944004, 'Onika Maraj-Petty', 'Luter School of Business', 'Department Lead', '1982-12-08', '2000-09-15', 'Female', 'Black', NULL);

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
