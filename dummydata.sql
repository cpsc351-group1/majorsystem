DELETE FROM `nomination`;
DELETE FROM `election`;
DELETE FROM `chairman`;
DELETE FROM `committee seat`;
DELETE FROM `committee`;
DELETE FROM `user`;

/*    USERS   */
/*    CNU_ID, Password, Fname, Lname, Email, Department, Position, Birthday, Hiring_Year, Gender, Race, Permissions, Photo    */

INSERT INTO `user`
  VALUES (1, 'admin', 'Test', 'Admin', 'admin@cnu.edu', 'All', 'Administrator', '2000-01-01', '2020', 'Non-binary', 'None', 'Admin', NULL, NULL);

INSERT INTO `user`
  VALUES (00998877, 'testpass1', 'John', 'Doe', 'johndoe@cnu.edu', 'Molecular Biology and Chemistry', 'Associate Professor', '1979-01-01', '2020', 'Male', 'White', 'User', NULL, NULL);

INSERT INTO `user`
  VALUES (00987987, 'testpass2', 'Jill', 'Doe', 'jilldoe@cnu.edu', 'English', 'Professor', '1975-01-01', '2015', 'Female', 'Black or African American', 'Super', NULL, NULL);

INSERT INTO `user`
  VALUES (00978879, 'testpass3', 'Beyoncé', 'Knowles-Carter', 'beyonceknowles@cnu.edu', 'Music', 'Department Lead', '1981-09-04', '2008', 'Female', 'Black or African American', 'Super', NULL, NULL);

INSERT INTO `user`
  VALUES (00966678, 'testpass4', 'Claire', 'Boucher', 'claireboucher@cnu.edu', 'Graphic Design', 'Associate Professor', '1988-03-17', '2018', 'Non-binary', 'White', 'User', NULL, NULL);

INSERT INTO `user`
  VALUES (00942069, 'testpass5', 'Elizabeth', 'Grant', 'elizabethgrant@cnu.edu', 'Philosophy and Religion', 'Professor', '1985-06-21', '2005', 'Female', 'White', 'User', NULL, NULL);

INSERT INTO `user`
  VALUES (00933833, 'testpass6', 'Azealia', 'Banks', 'azealiabanks@cnu.edu', 'Communications', 'Adjunct', '1991-05-31', '2020', 'Female', 'Black or African American', 'User', NULL, NULL);

INSERT INTO `user`
  VALUES (00999919, 'testpass7', 'Kurt', 'Cobain', 'kurtcobain@cnu.edu', 'Economics', 'Associate Professor', '1967-02-20', '2018', 'Male', 'White', 'User', NULL, NULL);

INSERT INTO `user`
  VALUES (00955259, 'testpass8', 'Dolly', 'Parton', 'dollyparton@cnu.edu', 'Molecular Biology and Chemistry', 'Department Lead', '1946-01-19', '1998', 'Female', 'White', 'User', NULL, NULL);

INSERT INTO `user`
  VALUES (00982429, 'testpass9', 'Mike', 'Lapke', 'mikelapke@cnu.edu', 'Physics, Computer Science and Engineering', 'Associate Professor', '1960-02-03', '2020', 'Male', 'White', 'User', NULL, NULL);

INSERT INTO `user`
  VALUES (00944004, 'testpass10', 'Onika', 'Maraj-Petty', 'onikamaraj@cnu.edu', 'Luter School of Business', 'Department Lead', '1982-12-08', '2000', 'Female', 'Black or African American', 'User', NULL, NULL);

/*    COMMITTEES    */
/*    Committee_ID, Name, Description    */

INSERT INTO `committee`
  VALUES (1, 'University Faculty on Committees', 'Committee that oversees committees.');

INSERT INTO `committee`
  VALUES (2, 'Fun Committee', 'Committee that oversees fun activities.');

INSERT INTO `committee`
  VALUES (3, 'Grass Committee', 'Committee that oversees the Great Lawn.');

INSERT INTO `committee`
  VALUES (4, 'Money Committee', 'Committee that oversees financials.');

INSERT INTO `committee`
VALUES (5, 'Sustainability Committee', 'Committee that oversees sustainability.');

/*    COMMITTEE SEATS    */
/*    Committee_Seat_ID, Committee_Committee_ID, Starting_Term, Ending_Term, User_CNU_ID    */
/* TODO: give some of these end_date values for testing archived seats */

INSERT INTO `committee seat`
  VALUES (1, 1, '2019-11-20', NULL, 1);

INSERT INTO `committee seat`
  VALUES (2, 1, '2020-01-04', NULL, 00987987);

INSERT INTO `committee seat`
  VALUES (3, 1, '2019-09-15', NULL, 00978879);

INSERT INTO `committee seat`
  VALUES (4, 2, '2021-02-18', NULL, 00966678);

INSERT INTO `committee seat`
  VALUES (5, 2, '2020-08-08', NULL, 00942069);

INSERT INTO `committee seat`
  VALUES (6, 3, '2021-01-01', NULL, 00933833);

INSERT INTO `committee seat`
  VALUES (7, 3, '2020-10-31', NULL, 00999919);

INSERT INTO `committee seat`
  VALUES (8, 4, '2021-02-14', NULL, 00955259);

INSERT INTO `committee seat`
  VALUES (9, 5, '2020-05-20', NULL, 00982429);

INSERT INTO `committee seat`
  VALUES (10, 5, '2017-07-13', NULL, 00944004);

/*    CHAIRMAN    */
/*    Committee_Committee_ID, User_CNU_ID   */

INSERT INTO `chairman` VALUES (1, 1);

INSERT INTO `chairman` VALUES (2, 00966678);

INSERT INTO `chairman` VALUES (3, 00933833);

INSERT INTO `chairman` VALUES (4, 00955259);

INSERT INTO `chairman` VALUES (5, 00982429);

/*    ELECTION    */
/*    Election_ID, Committee_Committee_ID, Status, Number_Seats    */

INSERT INTO `election`
  VALUES (1, 1, 'Nomination', 1);

INSERT INTO `election`
  VALUES (2, 2, 'Voting', 1);

INSERT INTO `election`
  VALUES (3, 3, 'Nomination', 2);

/*  NOMINATIONS   */
/*  Election_Election_ID, Nominator_CNU_ID, Nominee_CNU_ID    */

INSERT INTO `nomination`
  VALUES (1, 1, 00942069);

INSERT INTO `nomination`
VALUES (1, 1, 00955259);

INSERT INTO `nomination`
VALUES (1, 1, 00998877);

INSERT INTO `nomination`
  VALUES (2, 00998877, 00987987);

INSERT INTO `nomination`
  VALUES (2, 00998877, 00978879);

INSERT INTO `nomination`
  VALUES (2, 1, 00998877);
