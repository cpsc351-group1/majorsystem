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
  `Department` VARCHAR(45),
  `Position` VARCHAR(45),
  `Birthday` DATE NOT NULL,
  `Hiring_Year` YEAR(4) NOT NULL,
  `Gender` VARCHAR(45) NOT NULL,
  `Race` VARCHAR(45) NOT NULL DEFAULT 'User',
  `Photo` Blob,
  PRIMARY KEY (`CNU_ID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Committee`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Committee` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Committee` (
  `Committee_ID` INT NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(45) NOT NULL,
  `Description` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`Committee_ID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Committee Seat`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Committee Seat` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Committee Seat` (
  `Seat_ID` INT NOT NULL AUTO_INCREMENT,
  `Committee_Committee_ID` INT NOT NULL,
  `Starting_Term` VARCHAR(12) NOT NULL,
  `Ending_Term` VARCHAR(12),
  `User_CNU_ID` INT NOT NULL,
  PRIMARY KEY (`Seat_ID`, `Committee_Committee_ID`),
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
  PRIMARY KEY (`Committee_Committee_ID`, `User_CNU_ID`),
  INDEX `fk_Chairman_User1_idx` (`User_CNU_ID` ASC),
  CONSTRAINT `fk_Chairman_Committee1`
    FOREIGN KEY (`Committee_Committee_ID`)
    REFERENCES `mydb`.`Committee` (`Committee_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Chairman_User1`
    FOREIGN KEY (`User_CNU_ID`)
    REFERENCES `mydb`.`User` (`CNU_ID`)
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
  PRIMARY KEY (`Election_Election_ID`, `Voter_CNU_ID`, `Votee_CNU_ID`),
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
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Vote_User2`
    FOREIGN KEY (`Votee_CNU_ID`)
    REFERENCES `mydb`.`User` (`CNU_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
