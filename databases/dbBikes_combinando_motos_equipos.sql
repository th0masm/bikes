SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `dbBikes` DEFAULT CHARACTER SET utf8 ;
USE `dbBikes` ;

-- -----------------------------------------------------
-- Table `dbBikes`.`tblCategories`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbBikes`.`tblCategories` ;

CREATE  TABLE IF NOT EXISTS `dbBikes`.`tblCategories` (
  `idCategory` BIGINT NOT NULL AUTO_INCREMENT ,
  `strCategoryName` VARCHAR(20) NOT NULL ,
  PRIMARY KEY (`idCategory`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `dbBikes`.`tblTeams`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbBikes`.`tblTeams` ;

CREATE  TABLE IF NOT EXISTS `dbBikes`.`tblTeams` (
  `idTeam` BIGINT NOT NULL AUTO_INCREMENT ,
  `strTeamName` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`idTeam`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dbBikes`.`tblBikes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbBikes`.`tblBikes` ;

CREATE  TABLE IF NOT EXISTS `dbBikes`.`tblBikes` (
  `idBike` BIGINT NOT NULL AUTO_INCREMENT ,
  `strBikeName` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`idBike`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dbBikes`.`tblCountries`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbBikes`.`tblCountries` ;

CREATE  TABLE IF NOT EXISTS `dbBikes`.`tblCountries` (
  `idCountry` BIGINT NOT NULL AUTO_INCREMENT ,
  `strCountryCode` VARCHAR(3) NOT NULL ,
  `strCountryName` VARCHAR(60) NOT NULL ,
  PRIMARY KEY (`idCountry`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dbBikes`.`tblRiders`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbBikes`.`tblRiders` ;

CREATE  TABLE IF NOT EXISTS `dbBikes`.`tblRiders` (
  `idRider` BIGINT NOT NULL AUTO_INCREMENT ,
  `strRiderName` VARCHAR(100) NOT NULL ,
  `intRiderTeam` BIGINT NULL ,
  `intRiderCategory` BIGINT NULL ,
  `intRiderNumber` BIGINT NULL ,
  `dtRiderBirth` DATE NULL ,
  `intRiderBike` BIGINT NULL ,
  `strRiderCity` VARCHAR(100) NULL ,
  `intRiderWeight` INT NULL ,
  `intRiderHeight` INT NULL ,
  `intRiderCountry` BIGINT NULL ,
  `strRiderImage` VARCHAR(45) NULL ,
  PRIMARY KEY (`idRider`) ,
  INDEX `fkRiderCategory` (`intRiderCategory` ASC) ,
  INDEX `fkRiderTeam` (`intRiderTeam` ASC) ,
  INDEX `fkRiderBike` (`intRiderBike` ASC) ,
  INDEX `fkRiderCountry` (`intRiderCountry` ASC) ,
  CONSTRAINT `fkRiderCategory`
    FOREIGN KEY (`intRiderCategory` )
    REFERENCES `dbBikes`.`tblCategories` (`idCategory` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkRiderTeam`
    FOREIGN KEY (`intRiderTeam` )
    REFERENCES `dbBikes`.`tblTeams` (`idTeam` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkRiderBike`
    FOREIGN KEY (`intRiderBike` )
    REFERENCES `dbBikes`.`tblBikes` (`idBike` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkRiderCountry`
    FOREIGN KEY (`intRiderCountry` )
    REFERENCES `dbBikes`.`tblCountries` (`idCountry` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dbBikes`.`tblRaces`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbBikes`.`tblRaces` ;

CREATE  TABLE IF NOT EXISTS `dbBikes`.`tblRaces` (
  `idRace` BIGINT NOT NULL AUTO_INCREMENT ,
  `strRaceName` VARCHAR(100) NOT NULL ,
  `dtRaceDate` DATE NULL ,
  `intRaceCategory` BIGINT NULL ,
  `intRaceCountry` BIGINT NULL ,
  PRIMARY KEY (`idRace`) ,
  INDEX `fkRaceCategory` (`intRaceCategory` ASC) ,
  INDEX `fkRaceCountry` (`intRaceCountry` ASC) ,
  CONSTRAINT `fkRaceCategory`
    FOREIGN KEY (`intRaceCategory` )
    REFERENCES `dbBikes`.`tblCategories` (`idCategory` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkRaceCountry`
    FOREIGN KEY (`intRaceCountry` )
    REFERENCES `dbBikes`.`tblCountries` (`idCountry` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dbBikes`.`tblResults`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbBikes`.`tblResults` ;

CREATE  TABLE IF NOT EXISTS `dbBikes`.`tblResults` (
  `idResult` BIGINT NOT NULL AUTO_INCREMENT ,
  `intResultRider` BIGINT NOT NULL ,
  `intResultRace` BIGINT NOT NULL ,
  `intResultPosition` INT NULL ,
  `intResultPoints` INT NULL ,
  PRIMARY KEY (`idResult`) ,
  INDEX `fkResultRider` (`intResultRider` ASC) ,
  INDEX `fkResultRace` (`intResultRace` ASC) ,
  CONSTRAINT `fkResultRider`
    FOREIGN KEY (`intResultRider` )
    REFERENCES `dbBikes`.`tblRiders` (`idRider` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkResultRace`
    FOREIGN KEY (`intResultRace` )
    REFERENCES `dbBikes`.`tblRaces` (`idRace` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dbBikes`.`tblUsers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbBikes`.`tblUsers` ;

CREATE  TABLE IF NOT EXISTS `dbBikes`.`tblUsers` (
  `idUser` INT NOT NULL AUTO_INCREMENT ,
  `strUserName` VARCHAR(12) NOT NULL ,
  `strUserPassword` VARCHAR(40) NOT NULL ,
  `strUserEmail` VARCHAR(100) NULL ,
  `intUserActive` TINYINT(1) NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`idUser`) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
