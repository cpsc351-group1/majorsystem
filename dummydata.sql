DELETE FROM `chairman`;
DELETE FROM `committee seat`;
DELETE FROM `committee`;
DELETE FROM `user`;

/*    USERS   */

INSERT INTO `user`
  VALUES (00998877, 'John', 'Doe', 'johndoe@cnu.edu', 'Molecular Biology and Chemistry', 'Associate Professor', '1979-01-01', '2020-01-01', 'Male', 'White', NULL);

INSERT INTO `user`
  VALUES (00987987, 'Jill', 'Doe', 'jilldoe@cnu.edu', 'English', 'Professor', '1975-01-01', '2015-01-01', 'Female', 'Black', NULL);

INSERT INTO `user`
  VALUES (00978879, 'Beyoncé', 'Knowles-Carter', 'beyonceknowles@cnu.edu', 'Music', 'Department Lead', '1981-09-04', '2008-05-03', 'Female', 'Black', NULL);

INSERT INTO `user`
  VALUES (00966678, 'Claire', 'Boucher', 'claireboucher@cnu.edu', 'Graphic Design', 'Associate Professor', '1988-03-17', '2018-01-04', 'Non-binary', 'White', NULL);

INSERT INTO `user`
  VALUES (00942069, 'Elizabeth', 'Grant', 'elizabethgrant@cnu.edu', 'Philosophy and Religion', 'Professor', '1985-06-21', '2005-04-09', 'Female', 'White', NULL);

INSERT INTO `user`
  VALUES (00933833, 'Azealia', 'Banks', 'azealiabanks@cnu.edu', 'Communications', 'Adjunct', '1991-05-31', '2020-02-27', 'Female', 'Black', NULL);

INSERT INTO `user`
  VALUES (00999919, 'Kurt', 'Cobain', 'kurtcobain@cnu.edu', 'Economics', 'Associate Professor', '1967-02-20', '2018-12-20', 'Male', 'White', NULL);

INSERT INTO `user`
  VALUES (00955259, 'Dolly', 'Parton', 'dollyparton@cnu.edu', 'Molecular Biology and Chemistry', 'Department Lead', '1946-01-19', '1998-04-30', 'Female', 'White', NULL);

INSERT INTO `user`
  VALUES (00982429, 'Mike', 'Lapke', 'mikelapke@cnu.edu', 'Physics, Computer Science and Engineering', 'Associate Professor', '1960-02-03', '2020-06-30', 'Male', 'White', NULL);

INSERT INTO `user`
  VALUES (00944004, 'Onika', 'Maraj-Petty', 'onikamaraj@cnu.edu', 'Luter School of Business', 'Department Lead', '1982-12-08', '2000-09-15', 'Female', 'Black', NULL);

/*    COMMITTEES    */

INSERT INTO `committee` (Name, Description)
  VALUES ('Sustainability Committee', 'Committee that oversees sustainability.');

INSERT INTO `committee` (Name, Description)
  VALUES ('Fun Committee', 'Committee that oversees fun activities.');

INSERT INTO `committee` (Name, Description)
  VALUES ('Grass Committee', 'Committee that oversees the Great Lawn.');

INSERT INTO `committee` (Name, Description)
  VALUES ('Money Committee', 'Committee that oversees financials.');

INSERT INTO `committee` (Name, Description)
  VALUES ('University Faculty on Committees', 'Committee that oversees committees.');

/*  `committee seat`S   */
/* TODO: give some of these end_date values for testing archived seats */

INSERT INTO `committee seat` (Committee_Committee_ID, Starting_Term, Ending_Term, User_CNU_ID)
  VALUES (1, 'Fall 2019', NULL, 00998877);

INSERT INTO `committee seat` (Committee_Committee_ID, Starting_Term, Ending_Term, User_CNU_ID)
  VALUES (1, 'Spring 2020', NULL, 00987987);

INSERT INTO `committee seat` (Committee_Committee_ID, Starting_Term, Ending_Term, User_CNU_ID)
  VALUES (1, 'Fall 2020', NULL, 00978879);

INSERT INTO `committee seat` (Committee_Committee_ID, Starting_Term, Ending_Term, User_CNU_ID)
  VALUES (2, 'Spring 2021', NULL, 00966678);

INSERT INTO `committee seat` (Committee_Committee_ID, Starting_Term, Ending_Term, User_CNU_ID)
  VALUES (2, 'Fall 2020', NULL, 00942069);

INSERT INTO `committee seat` (Committee_Committee_ID, Starting_Term, Ending_Term, User_CNU_ID)
  VALUES (3, 'Spring 2021', NULL, 00933833);

INSERT INTO `committee seat` (Committee_Committee_ID, Starting_Term, Ending_Term, User_CNU_ID)
  VALUES (3, 'Fall 2019', NULL, 00999919);

INSERT INTO `committee seat` (Committee_Committee_ID, Starting_Term, Ending_Term, User_CNU_ID)
  VALUES (4, 'Spring 2021', NULL, 00955259);

INSERT INTO `committee seat` (Committee_Committee_ID, Starting_Term, Ending_Term, User_CNU_ID)
  VALUES (5, 'Spring 2020', NULL, 00982429);

INSERT INTO `committee seat` (Committee_Committee_ID, Starting_Term, Ending_Term, User_CNU_ID)
  VALUES (5, 'Fall 2020', NULL, 00944004);

/*    CHAIRMAN    */

INSERT INTO chairman VALUES (1, 00998877);

INSERT INTO chairman VALUES (2, 00966678);

INSERT INTO chairman VALUES (3, 00933833);

INSERT INTO chairman VALUES (4, 00955259);

INSERT INTO chairman VALUES (5, 00982429);
