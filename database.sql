-- MySQL Script generated by MySQL Workbench
-- Thu Mar 11 09:52:42 2021
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
DROP DATABASE IF EXISTS `mydb`;
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`User`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`User` ;

CREATE TABLE IF NOT EXISTS `mydb`.`User` (
  `CNU_ID` INT NOT NULL AUTO_INCREMENT,
  `Password` VARCHAR(24) NOT NULL,
  `Fname` VARCHAR(45) NOT NULL,
  `Lname` VARCHAR(45) NOT NULL,
  `Email` VARCHAR(72) NOT NULL,
  `Department` VARCHAR(45) NOT NULL,
  `Position` VARCHAR(45) NOT NULL,
  `Birthday` DATE NOT NULL,
  `Hiring_Year` YEAR(4) NOT NULL,
  `Gender` VARCHAR(45) NOT NULL,
  `Race` VARCHAR(45) NOT NULL,
  `Permissions` VARCHAR(45) NOT NULL DEFAULT 'User',
  `Photo` Blob,
  `Archival_Date` DATE DEFAULT NULL,
  PRIMARY KEY (`CNU_ID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Committee`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Committee` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Committee` (
  `Committee_ID` INT NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(45) NOT NULL,
  `Description` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`Committee_ID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Committee Seat`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Committee Seat` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Committee Seat` (
  `Seat_ID` INT NOT NULL AUTO_INCREMENT,
  `Committee_Committee_ID` INT NOT NULL,
  `Starting_Term` DATE NOT NULL,
  `Ending_Term` DATE DEFAULT NULL,
  `User_CNU_ID` INT NOT NULL,
  PRIMARY KEY (`Seat_ID`),
  INDEX `fk_Committee Seat_Committee1_idx` (`Committee_Committee_ID` ASC),
  INDEX `fk_Committee Seat_User1_idx` (`User_CNU_ID` ASC),
  CONSTRAINT `fk_Committee Seat_Committee1`
    FOREIGN KEY (`Committee_Committee_ID`)
    REFERENCES `mydb`.`Committee` (`Committee_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Committee Seat_User1`
    FOREIGN KEY (`User_CNU_ID`)
    REFERENCES `mydb`.`User` (`CNU_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mydb`.`Chairman`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Chairman` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Chairman` (
  `Committee_Committee_ID` INT(11) NOT NULL,
  `User_CNU_ID` INT(11) NOT NULL,
  PRIMARY KEY (`Committee_Committee_ID`),
  INDEX `fk_Chairman_User1_idx` (`User_CNU_ID` ASC),
  CONSTRAINT `fk_Chairman_Committee1`
    FOREIGN KEY (`Committee_Committee_ID`)
    REFERENCES `mydb`.`Committee` (`Committee_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mydb`.`Election`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Election` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Election` (
  `Election_ID` INT NOT NULL AUTO_INCREMENT,
  `Committee_Committee_ID` INT NOT NULL,
  `Status` VARCHAR(45) NOT NULL,
  `Number_Seats` INT NOT NULL,
  PRIMARY KEY (`Election_ID`),
  INDEX `fk_Election_Committee1_idx` (`Committee_Committee_ID` ASC),
  CONSTRAINT `fk_Election_Committee1`
    FOREIGN KEY (`Committee_Committee_ID`)
    REFERENCES `mydb`.`Committee` (`Committee_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Nomination`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Nomination` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Nomination` (
  `Election_Election_ID` INT NOT NULL,
  `Nominator_CNU_ID` INT NOT NULL,
  `Nominee_CNU_ID` INT NOT NULL,
  PRIMARY KEY (`Election_Election_ID`, `Nominator_CNU_ID`, `Nominee_CNU_ID`),
  INDEX `fk_Nomination_Election1_idx` (`Election_Election_ID` ASC),
  INDEX `fk_Nomination_User1_idx` (`Nominator_CNU_ID` ASC),
  INDEX `fk_Nomination_User2_idx` (`Nominee_CNU_ID` ASC),
  CONSTRAINT `fk_Nomination_Election1`
    FOREIGN KEY (`Election_Election_ID`)
    REFERENCES `mydb`.`Election` (`Election_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Nomination_User1`
    FOREIGN KEY (`Nominator_CNU_ID`)
    REFERENCES `mydb`.`User` (`CNU_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Nomination_User2`
    FOREIGN KEY (`Nominee_CNU_ID`)
    REFERENCES `mydb`.`User` (`CNU_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Vote`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Vote` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Vote` (
  `Election_Election_ID` INT NOT NULL,
  `Voter_CNU_ID` INT NOT NULL,
  `Votee_CNU_ID` INT NOT NULL,
  PRIMARY KEY (`Election_Election_ID`, `Voter_CNU_ID`),
  INDEX `fk_Vote_Election1_idx` (`Election_Election_ID` ASC),
  INDEX `fk_Vote_User1_idx` (`Voter_CNU_ID` ASC),
  INDEX `fk_Vote_User2_idx` (`Votee_CNU_ID` ASC),
  CONSTRAINT `fk_Vote_Election1`
    FOREIGN KEY (`Election_Election_ID`)
    REFERENCES `mydb`.`Election` (`Election_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Vote_User1`
    FOREIGN KEY (`Voter_CNU_ID`)
    REFERENCES `mydb`.`User` (`CNU_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

DELETE FROM `nomination`;
DELETE FROM `election`;
DELETE FROM `chairman`;
DELETE FROM `committee seat`;
DELETE FROM `committee`;
DELETE FROM `user`;

/*    USERS   */
/*    CNU_ID, Password, Fname, Lname, Email, Department, Position, Birthday, Hiring_Year, Gender, Race, Permissions, Photo    */

INSERT INTO `user`
  VALUES (1, 'admin', 'Test', 'Admin', 'admin@cnu.edu', 'CNU Administration', 'Administrator', '2000-01-01', '2020', 'Non-binary', 'None', 'Admin', NULL, NULL);

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
  VALUES (00938804, 'testpass9', 'Samuel', 'Tyler', 'samuel.tyler.17@cnu.edu', 'Molecular Biology and Chemistry', 'Student', '1999-09-15', '2017', 'Male', 'White', 'User', NULL, NULL);

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
  VALUES (9, 5, '2020-05-20', NULL, 00938804);

INSERT INTO `committee seat`
  VALUES (10, 5, '2017-07-13', NULL, 00944004);

/*    CHAIRMAN    */
/*    Committee_Committee_ID, User_CNU_ID   */

INSERT INTO `chairman` VALUES (1, 1);

INSERT INTO `chairman` VALUES (2, 00966678);

INSERT INTO `chairman` VALUES (3, 00933833);

INSERT INTO `chairman` VALUES (4, 00955259);

INSERT INTO `chairman` VALUES (5, 00938804);

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

INSERT INTO `nomination`
  VALUES (3, 00998877, 00987987);
