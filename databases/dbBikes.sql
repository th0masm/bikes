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
  `intTeamCategory` BIGINT NULL ,
  PRIMARY KEY (`idTeam`) ,
  INDEX `fkTeamCategory` (`intTeamCategory` ASC) ,
  CONSTRAINT `fkTeamCategory`
    FOREIGN KEY (`intTeamCategory` )
    REFERENCES `dbBikes`.`tblCategories` (`idCategory` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dbBikes`.`tblBikes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbBikes`.`tblBikes` ;

CREATE  TABLE IF NOT EXISTS `dbBikes`.`tblBikes` (
  `idBike` BIGINT NOT NULL AUTO_INCREMENT ,
  `strBikeName` VARCHAR(45) NOT NULL ,
  `intBikeCategory` BIGINT NULL ,
  PRIMARY KEY (`idBike`) ,
  INDEX `fkBikeCategory` (`intBikeCategory` ASC) ,
  CONSTRAINT `fkBikeCategory`
    FOREIGN KEY (`intBikeCategory` )
    REFERENCES `dbBikes`.`tblCategories` (`idCategory` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
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
  `dtRaceDate` DATE NOT NULL ,
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
  `intRider` BIGINT NULL ,
  `intRace` BIGINT NULL ,
  `intPosition` INT NOT NULL ,
  PRIMARY KEY (`idResult`) ,
  INDEX `fkResultRider` (`intRider` ASC) ,
  INDEX `fkResultRace` (`intRace` ASC) ,
  CONSTRAINT `fkResultRider`
    FOREIGN KEY (`intRider` )
    REFERENCES `dbBikes`.`tblRiders` (`idRider` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkResultRace`
    FOREIGN KEY (`intRace` )
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
  `strUserEmail` VARCHAR(100) NOT NULL ,
  `intUserActive` TINYINT(1) NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`idUser`) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
-- -----------------------------------------------------
-- Data for table `dbBikes`.`tblCountries`
-- -----------------------------------------------------
START TRANSACTION;
USE `dbBikes`;
INSERT INTO `tblCountries` VALUES (NULL, 'AF', 'Afghanistan');
INSERT INTO `tblCountries` VALUES (NULL, 'AL', 'Albania');
INSERT INTO `tblCountries` VALUES (NULL, 'DZ', 'Algeria');
INSERT INTO `tblCountries` VALUES (NULL, 'AS', 'American Samoa');
INSERT INTO `tblCountries` VALUES (NULL, 'AD', 'Andorra');
INSERT INTO `tblCountries` VALUES (NULL, 'AO', 'Angola');
INSERT INTO `tblCountries` VALUES (NULL, 'AI', 'Anguilla');
INSERT INTO `tblCountries` VALUES (NULL, 'AQ', 'Antarctica');
INSERT INTO `tblCountries` VALUES (NULL, 'AG', 'Antigua and Barbuda');
INSERT INTO `tblCountries` VALUES (NULL, 'AR', 'Argentina');
INSERT INTO `tblCountries` VALUES (NULL, 'AM', 'Armenia');
INSERT INTO `tblCountries` VALUES (NULL, 'AW', 'Aruba');
INSERT INTO `tblCountries` VALUES (NULL, 'AU', 'Australia');
INSERT INTO `tblCountries` VALUES (NULL, 'AT', 'Austria');
INSERT INTO `tblCountries` VALUES (NULL, 'AZ', 'Azerbaijan');
INSERT INTO `tblCountries` VALUES (NULL, 'BS', 'Bahamas');
INSERT INTO `tblCountries` VALUES (NULL, 'BH', 'Bahrain');
INSERT INTO `tblCountries` VALUES (NULL, 'BD', 'Bangladesh');
INSERT INTO `tblCountries` VALUES (NULL, 'BB', 'Barbados');
INSERT INTO `tblCountries` VALUES (NULL, 'BY', 'Belarus');
INSERT INTO `tblCountries` VALUES (NULL, 'BE', 'Belgium');
INSERT INTO `tblCountries` VALUES (NULL, 'BZ', 'Belize');
INSERT INTO `tblCountries` VALUES (NULL, 'BJ', 'Benin');
INSERT INTO `tblCountries` VALUES (NULL, 'BM', 'Bermuda');
INSERT INTO `tblCountries` VALUES (NULL, 'BT', 'Bhutan');
INSERT INTO `tblCountries` VALUES (NULL, 'BO', 'Bolivia');
INSERT INTO `tblCountries` VALUES (NULL, 'BA', 'Bosnia and Herzegovina');
INSERT INTO `tblCountries` VALUES (NULL, 'BW', 'Botswana');
INSERT INTO `tblCountries` VALUES (NULL, 'BV', 'Bouvet Island');
INSERT INTO `tblCountries` VALUES (NULL, 'BR', 'Brazil');
INSERT INTO `tblCountries` VALUES (NULL, 'IO', 'British Indian Ocean Territory');
INSERT INTO `tblCountries` VALUES (NULL, 'BN', 'Brunei Darussalam');
INSERT INTO `tblCountries` VALUES (NULL, 'BG', 'Bulgaria');
INSERT INTO `tblCountries` VALUES (NULL, 'BF', 'Burkina Faso');
INSERT INTO `tblCountries` VALUES (NULL, 'BI', 'Burundi');
INSERT INTO `tblCountries` VALUES (NULL, 'KH', 'Cambodia');
INSERT INTO `tblCountries` VALUES (NULL, 'CM', 'Cameroon');
INSERT INTO `tblCountries` VALUES (NULL, 'CA', 'Canada');
INSERT INTO `tblCountries` VALUES (NULL, 'CV', 'Cape Verde');
INSERT INTO `tblCountries` VALUES (NULL, 'KY', 'Cayman Islands');
INSERT INTO `tblCountries` VALUES (NULL, 'CF', 'Central African Republic');
INSERT INTO `tblCountries` VALUES (NULL, 'TD', 'Chad');
INSERT INTO `tblCountries` VALUES (NULL, 'CL', 'Chile');
INSERT INTO `tblCountries` VALUES (NULL, 'CN', 'China');
INSERT INTO `tblCountries` VALUES (NULL, 'CX', 'Christmas Island');
INSERT INTO `tblCountries` VALUES (NULL, 'CC', 'Cocos (Keeling) Islands');
INSERT INTO `tblCountries` VALUES (NULL, 'CO', 'Colombia');
INSERT INTO `tblCountries` VALUES (NULL, 'KM', 'Comoros');
INSERT INTO `tblCountries` VALUES (NULL, 'CG', 'Congo');
INSERT INTO `tblCountries` VALUES (NULL, 'CD', 'Congo, The Democratic Republic of the');
INSERT INTO `tblCountries` VALUES (NULL, 'CK', 'Cook Islands');
INSERT INTO `tblCountries` VALUES (NULL, 'CR', 'Costa Rica');
INSERT INTO `tblCountries` VALUES (NULL, 'CI', 'Côte D\'Ivoire');
INSERT INTO `tblCountries` VALUES (NULL, 'HR', 'Croatia');
INSERT INTO `tblCountries` VALUES (NULL, 'CU', 'Cuba');
INSERT INTO `tblCountries` VALUES (NULL, 'CY', 'Cyprus');
INSERT INTO `tblCountries` VALUES (NULL, 'CZ', 'Czech Republic');
INSERT INTO `tblCountries` VALUES (NULL, 'DK', 'Denmark');
INSERT INTO `tblCountries` VALUES (NULL, 'DJ', 'Djibouti');
INSERT INTO `tblCountries` VALUES (NULL, 'DM', 'Dominica');
INSERT INTO `tblCountries` VALUES (NULL, 'DO', 'Dominican Republic');
INSERT INTO `tblCountries` VALUES (NULL, 'EC', 'Ecuador');
INSERT INTO `tblCountries` VALUES (NULL, 'EG', 'Egypt');
INSERT INTO `tblCountries` VALUES (NULL, 'SV', 'El Salvador');
INSERT INTO `tblCountries` VALUES (NULL, 'GQ', 'Equatorial Guinea');
INSERT INTO `tblCountries` VALUES (NULL, 'ER', 'Eritrea');
INSERT INTO `tblCountries` VALUES (NULL, 'EE', 'Estonia');
INSERT INTO `tblCountries` VALUES (NULL, 'ET', 'Ethiopia');
INSERT INTO `tblCountries` VALUES (NULL, 'FK', 'Falkland Islands (Malvinas)');
INSERT INTO `tblCountries` VALUES (NULL, 'FO', 'Faroe Islands');
INSERT INTO `tblCountries` VALUES (NULL, 'FJ', 'Fiji');
INSERT INTO `tblCountries` VALUES (NULL, 'FI', 'Finland');
INSERT INTO `tblCountries` VALUES (NULL, 'FR', 'France');
INSERT INTO `tblCountries` VALUES (NULL, 'GF', 'French Guiana');
INSERT INTO `tblCountries` VALUES (NULL, 'PF', 'French Polynesia');
INSERT INTO `tblCountries` VALUES (NULL, 'TF', 'French Southern Territories');
INSERT INTO `tblCountries` VALUES (NULL, 'GA', 'Gabon');
INSERT INTO `tblCountries` VALUES (NULL, 'GM', 'Gambia');
INSERT INTO `tblCountries` VALUES (NULL, 'GE', 'Georgia');
INSERT INTO `tblCountries` VALUES (NULL, 'DE', 'Germany');
INSERT INTO `tblCountries` VALUES (NULL, 'GH', 'Ghana');
INSERT INTO `tblCountries` VALUES (NULL, 'GI', 'Gibraltar');
INSERT INTO `tblCountries` VALUES (NULL, 'GR', 'Greece');
INSERT INTO `tblCountries` VALUES (NULL, 'GL', 'Greenland');
INSERT INTO `tblCountries` VALUES (NULL, 'GD', 'Grenada');
INSERT INTO `tblCountries` VALUES (NULL, 'GP', 'Guadeloupe');
INSERT INTO `tblCountries` VALUES (NULL, 'GU', 'Guam');
INSERT INTO `tblCountries` VALUES (NULL, 'GT', 'Guatemala');
INSERT INTO `tblCountries` VALUES (NULL, 'GG', 'Guernsey');
INSERT INTO `tblCountries` VALUES (NULL, 'GN', 'Guinea');
INSERT INTO `tblCountries` VALUES (NULL, 'GW', 'Guinea-Bissau');
INSERT INTO `tblCountries` VALUES (NULL, 'GY', 'Guyana');
INSERT INTO `tblCountries` VALUES (NULL, 'HT', 'Haiti');
INSERT INTO `tblCountries` VALUES (NULL, 'HM', 'Heard Island and McDonald Islands');
INSERT INTO `tblCountries` VALUES (NULL, 'VA', 'Holy See (Vatican City State)');
INSERT INTO `tblCountries` VALUES (NULL, 'HN', 'Honduras');
INSERT INTO `tblCountries` VALUES (NULL, 'HK', 'Hong Kong');
INSERT INTO `tblCountries` VALUES (NULL, 'HU', 'Hungary');
INSERT INTO `tblCountries` VALUES (NULL, 'IS', 'Iceland');
INSERT INTO `tblCountries` VALUES (NULL, 'IN', 'India');
INSERT INTO `tblCountries` VALUES (NULL, 'ID', 'Indonesia');
INSERT INTO `tblCountries` VALUES (NULL, 'IR', 'Iran, Islamic Republic of');
INSERT INTO `tblCountries` VALUES (NULL, 'IQ', 'Iraq');
INSERT INTO `tblCountries` VALUES (NULL, 'IE', 'Ireland');
INSERT INTO `tblCountries` VALUES (NULL, 'IM', 'Isle of Man');
INSERT INTO `tblCountries` VALUES (NULL, 'IL', 'Israel');
INSERT INTO `tblCountries` VALUES (NULL, 'IT', 'Italy');
INSERT INTO `tblCountries` VALUES (NULL, 'JM', 'Jamaica');
INSERT INTO `tblCountries` VALUES (NULL, 'JP', 'Japan');
INSERT INTO `tblCountries` VALUES (NULL, 'JE', 'Jersey');
INSERT INTO `tblCountries` VALUES (NULL, 'JO', 'Jordan');
INSERT INTO `tblCountries` VALUES (NULL, 'KZ', 'Kazakhstan');
INSERT INTO `tblCountries` VALUES (NULL, 'KE', 'Kenya');
INSERT INTO `tblCountries` VALUES (NULL, 'KI', 'Kiribati');
INSERT INTO `tblCountries` VALUES (NULL, 'KP', 'Korea, Democratic People\'s Republic of');
INSERT INTO `tblCountries` VALUES (NULL, 'KR', 'Korea, Republic of');
INSERT INTO `tblCountries` VALUES (NULL, 'KW', 'Kuwait');
INSERT INTO `tblCountries` VALUES (NULL, 'KG', 'Kyrgyzstan');
INSERT INTO `tblCountries` VALUES (NULL, 'LA', 'Lao People\'s Democratic Republic');
INSERT INTO `tblCountries` VALUES (NULL, 'LV', 'Latvia');
INSERT INTO `tblCountries` VALUES (NULL, 'LB', 'Lebanon');
INSERT INTO `tblCountries` VALUES (NULL, 'LS', 'Lesotho');
INSERT INTO `tblCountries` VALUES (NULL, 'LR', 'Liberia');
INSERT INTO `tblCountries` VALUES (NULL, 'LY', 'Libyan Arab Jamahiriya');
INSERT INTO `tblCountries` VALUES (NULL, 'LI', 'Liechtenstein');
INSERT INTO `tblCountries` VALUES (NULL, 'LT', 'Lithuania');
INSERT INTO `tblCountries` VALUES (NULL, 'LU', 'Luxembourg');
INSERT INTO `tblCountries` VALUES (NULL, 'MO', 'Macao');
INSERT INTO `tblCountries` VALUES (NULL, 'MK', 'Macedonia, The Former Yugoslav Republic of');
INSERT INTO `tblCountries` VALUES (NULL, 'MG', 'Madagascar');
INSERT INTO `tblCountries` VALUES (NULL, 'MW', 'Malawi');
INSERT INTO `tblCountries` VALUES (NULL, 'MY', 'Malaysia');
INSERT INTO `tblCountries` VALUES (NULL, 'MV', 'Maldives');
INSERT INTO `tblCountries` VALUES (NULL, 'ML', 'Mali');
INSERT INTO `tblCountries` VALUES (NULL, 'MT', 'Malta');
INSERT INTO `tblCountries` VALUES (NULL, 'MH', 'Marshall Islands');
INSERT INTO `tblCountries` VALUES (NULL, 'MQ', 'Martinique');
INSERT INTO `tblCountries` VALUES (NULL, 'MR', 'Mauritania');
INSERT INTO `tblCountries` VALUES (NULL, 'MU', 'Mauritius');
INSERT INTO `tblCountries` VALUES (NULL, 'YT', 'Mayotte');
INSERT INTO `tblCountries` VALUES (NULL, 'MX', 'Mexico');
INSERT INTO `tblCountries` VALUES (NULL, 'FM', 'Micronesia, Federated States of');
INSERT INTO `tblCountries` VALUES (NULL, 'MD', 'Moldova, Republic of');
INSERT INTO `tblCountries` VALUES (NULL, 'MC', 'Monaco');
INSERT INTO `tblCountries` VALUES (NULL, 'MN', 'Mongolia');
INSERT INTO `tblCountries` VALUES (NULL, 'ME', 'Montenegro');
INSERT INTO `tblCountries` VALUES (NULL, 'MS', 'Montserrat');
INSERT INTO `tblCountries` VALUES (NULL, 'MA', 'Morocco');
INSERT INTO `tblCountries` VALUES (NULL, 'MZ', 'Mozambique');
INSERT INTO `tblCountries` VALUES (NULL, 'MM', 'Myanmar');
INSERT INTO `tblCountries` VALUES (NULL, 'NA', 'Namibia');
INSERT INTO `tblCountries` VALUES (NULL, 'NR', 'Nauru');
INSERT INTO `tblCountries` VALUES (NULL, 'NP', 'Nepal');
INSERT INTO `tblCountries` VALUES (NULL, 'NL', 'Netherlands');
INSERT INTO `tblCountries` VALUES (NULL, 'AN', 'Netherlands Antilles');
INSERT INTO `tblCountries` VALUES (NULL, 'NC', 'New Caledonia');
INSERT INTO `tblCountries` VALUES (NULL, 'NZ', 'New Zealand');
INSERT INTO `tblCountries` VALUES (NULL, 'NI', 'Nicaragua');
INSERT INTO `tblCountries` VALUES (NULL, 'NE', 'Niger');
INSERT INTO `tblCountries` VALUES (NULL, 'NG', 'Nigeria');
INSERT INTO `tblCountries` VALUES (NULL, 'NU', 'Niue');
INSERT INTO `tblCountries` VALUES (NULL, 'NF', 'Norfolk Island');
INSERT INTO `tblCountries` VALUES (NULL, 'MP', 'Northern Mariana Islands');
INSERT INTO `tblCountries` VALUES (NULL, 'NO', 'Norway');
INSERT INTO `tblCountries` VALUES (NULL, 'OM', 'Oman');
INSERT INTO `tblCountries` VALUES (NULL, 'PK', 'Pakistan');
INSERT INTO `tblCountries` VALUES (NULL, 'PW', 'Palau');
INSERT INTO `tblCountries` VALUES (NULL, 'PS', 'Palestinian Territory, Occupied');
INSERT INTO `tblCountries` VALUES (NULL, 'PA', 'Panama');
INSERT INTO `tblCountries` VALUES (NULL, 'PG', 'Papua New Guinea');
INSERT INTO `tblCountries` VALUES (NULL, 'PY', 'Paraguay');
INSERT INTO `tblCountries` VALUES (NULL, 'PE', 'Peru');
INSERT INTO `tblCountries` VALUES (NULL, 'PH', 'Philippines');
INSERT INTO `tblCountries` VALUES (NULL, 'PN', 'Pitcairn');
INSERT INTO `tblCountries` VALUES (NULL, 'PL', 'Poland');
INSERT INTO `tblCountries` VALUES (NULL, 'PT', 'Portugal');
INSERT INTO `tblCountries` VALUES (NULL, 'PR', 'Puerto Rico');
INSERT INTO `tblCountries` VALUES (NULL, 'QA', 'Qatar');
INSERT INTO `tblCountries` VALUES (NULL, 'RE', 'Reunion');
INSERT INTO `tblCountries` VALUES (NULL, 'RO', 'Romania');
INSERT INTO `tblCountries` VALUES (NULL, 'RU', 'Russian Federation');
INSERT INTO `tblCountries` VALUES (NULL, 'RW', 'Rwanda');
INSERT INTO `tblCountries` VALUES (NULL, 'BL', 'Saint Barthélemy');
INSERT INTO `tblCountries` VALUES (NULL, 'SH', 'Saint Helena');
INSERT INTO `tblCountries` VALUES (NULL, 'KN', 'Saint Kitts and Nevis');
INSERT INTO `tblCountries` VALUES (NULL, 'LC', 'Saint Lucia');
INSERT INTO `tblCountries` VALUES (NULL, 'MF', 'Saint Martin');
INSERT INTO `tblCountries` VALUES (NULL, 'PM', 'Saint Pierre and Miquelon');
INSERT INTO `tblCountries` VALUES (NULL, 'VC', 'Saint Vincent and the Grenadines');
INSERT INTO `tblCountries` VALUES (NULL, 'WS', 'Samoa');
INSERT INTO `tblCountries` VALUES (NULL, 'SM', 'San Marino');
INSERT INTO `tblCountries` VALUES (NULL, 'ST', 'Sao Tome and Principe');
INSERT INTO `tblCountries` VALUES (NULL, 'SA', 'Saudi Arabia');
INSERT INTO `tblCountries` VALUES (NULL, 'SN', 'Senegal');
INSERT INTO `tblCountries` VALUES (NULL, 'RS', 'Serbia');
INSERT INTO `tblCountries` VALUES (NULL, 'SC', 'Seychelles');
INSERT INTO `tblCountries` VALUES (NULL, 'SL', 'Sierra Leone');
INSERT INTO `tblCountries` VALUES (NULL, 'SG', 'Singapore');
INSERT INTO `tblCountries` VALUES (NULL, 'SK', 'Slovakia');
INSERT INTO `tblCountries` VALUES (NULL, 'SI', 'Slovenia');
INSERT INTO `tblCountries` VALUES (NULL, 'SB', 'Solomon Islands');
INSERT INTO `tblCountries` VALUES (NULL, 'SO', 'Somalia');
INSERT INTO `tblCountries` VALUES (NULL, 'ZA', 'South Africa');
INSERT INTO `tblCountries` VALUES (NULL, 'GS', 'South Georgia and the South Sandwich Islands');
INSERT INTO `tblCountries` VALUES (NULL, 'ES', 'Spain');
INSERT INTO `tblCountries` VALUES (NULL, 'LK', 'Sri Lanka');
INSERT INTO `tblCountries` VALUES (NULL, 'SD', 'Sudan');
INSERT INTO `tblCountries` VALUES (NULL, 'SR', 'Suriname');
INSERT INTO `tblCountries` VALUES (NULL, 'SJ', 'Svalbard and Jan Mayen');
INSERT INTO `tblCountries` VALUES (NULL, 'SZ', 'Swaziland');
INSERT INTO `tblCountries` VALUES (NULL, 'SE', 'Sweden');
INSERT INTO `tblCountries` VALUES (NULL, 'CH', 'Switzerland');
INSERT INTO `tblCountries` VALUES (NULL, 'SY', 'Syrian Arab Republic');
INSERT INTO `tblCountries` VALUES (NULL, 'TW', 'Taiwan, Province Of China');
INSERT INTO `tblCountries` VALUES (NULL, 'TJ', 'Tajikistan');
INSERT INTO `tblCountries` VALUES (NULL, 'TZ', 'Tanzania, United Republic of');
INSERT INTO `tblCountries` VALUES (NULL, 'TH', 'Thailand');
INSERT INTO `tblCountries` VALUES (NULL, 'TL', 'Timor-Leste');
INSERT INTO `tblCountries` VALUES (NULL, 'TG', 'Togo');
INSERT INTO `tblCountries` VALUES (NULL, 'TK', 'Tokelau');
INSERT INTO `tblCountries` VALUES (NULL, 'TO', 'Tonga');
INSERT INTO `tblCountries` VALUES (NULL, 'TT', 'Trinidad and Tobago');
INSERT INTO `tblCountries` VALUES (NULL, 'TN', 'Tunisia');
INSERT INTO `tblCountries` VALUES (NULL, 'TR', 'Turkey');
INSERT INTO `tblCountries` VALUES (NULL, 'TM', 'Turkmenistan');
INSERT INTO `tblCountries` VALUES (NULL, 'TC', 'Turks and Caicos Islands');
INSERT INTO `tblCountries` VALUES (NULL, 'TV', 'Tuvalu');
INSERT INTO `tblCountries` VALUES (NULL, 'UG', 'Uganda');
INSERT INTO `tblCountries` VALUES (NULL, 'UA', 'Ukraine');
INSERT INTO `tblCountries` VALUES (NULL, 'AE', 'United Arab Emirates');
INSERT INTO `tblCountries` VALUES (NULL, 'GB', 'United Kingdom');
INSERT INTO `tblCountries` VALUES (NULL, 'US', 'United States');
INSERT INTO `tblCountries` VALUES (NULL, 'UM', 'United States Minor Outlying Islands');
INSERT INTO `tblCountries` VALUES (NULL, 'UY', 'Uruguay');
INSERT INTO `tblCountries` VALUES (NULL, 'UZ', 'Uzbekistan');
INSERT INTO `tblCountries` VALUES (NULL, 'VU', 'Vanuatu');
INSERT INTO `tblCountries` VALUES (NULL, 'VE', 'Venezuela');
INSERT INTO `tblCountries` VALUES (NULL, 'VN', 'Viet Nam');
INSERT INTO `tblCountries` VALUES (NULL, 'VG', 'Virgin Islands, British');
INSERT INTO `tblCountries` VALUES (NULL, 'VI', 'Virgin Islands, U.S.');
INSERT INTO `tblCountries` VALUES (NULL, 'WF', 'Wallis And Futuna');
INSERT INTO `tblCountries` VALUES (NULL, 'EH', 'Western Sahara');
INSERT INTO `tblCountries` VALUES (NULL, 'YE', 'Yemen');
INSERT INTO `tblCountries` VALUES (NULL, 'ZM', 'Zambia');
INSERT INTO `tblCountries` VALUES (NULL, 'ZW', 'Zimbabwe');
COMMIT;


-- -----------------------------------------------------
-- Data for table `dbBikes`.`tblCategories`
-- -----------------------------------------------------
START TRANSACTION;
USE `dbBikes`;
INSERT INTO `dbBikes`.`tblCategories` (`idCategory`, `strCategoryName`) VALUES (NULL, 'MotoGP');
INSERT INTO `dbBikes`.`tblCategories` (`idCategory`, `strCategoryName`) VALUES (NULL, 'Moto2');
INSERT INTO `dbBikes`.`tblCategories` (`idCategory`, `strCategoryName`) VALUES (NULL, 'Moto3');

COMMIT;

-- -----------------------------------------------------
-- Data for table `dbBikes`.`tblTeams`
-- -----------------------------------------------------
START TRANSACTION;
USE `dbBikes`;
INSERT INTO `dbBikes`.`tblTeams` (`idTeam`, `strTeamName`, `intTeamCategory`) VALUES (NULL, 'Repsol Honda Team', 1);
INSERT INTO `dbBikes`.`tblTeams` (`idTeam`, `strTeamName`, `intTeamCategory`) VALUES (NULL, 'Yamaha Factory Racing', 1);
INSERT INTO `dbBikes`.`tblTeams` (`idTeam`, `strTeamName`, `intTeamCategory`) VALUES (NULL, 'Avintia Racing MotoGP', 1);
INSERT INTO `dbBikes`.`tblTeams` (`idTeam`, `strTeamName`, `intTeamCategory`) VALUES (NULL, 'Came IodaRacing Project', 1);
INSERT INTO `dbBikes`.`tblTeams` (`idTeam`, `strTeamName`, `intTeamCategory`) VALUES (NULL, 'Cardion AB Motoracing', 1);
INSERT INTO `dbBikes`.`tblTeams` (`idTeam`, `strTeamName`, `intTeamCategory`) VALUES (NULL, 'Ducati Team', 1);
INSERT INTO `dbBikes`.`tblTeams` (`idTeam`, `strTeamName`, `intTeamCategory`) VALUES (NULL, 'LCR Honda MotoGP', 1);
INSERT INTO `dbBikes`.`tblTeams` (`idTeam`, `strTeamName`, `intTeamCategory`) VALUES (NULL, 'Monster Yamaha Tech 3', 1);
INSERT INTO `dbBikes`.`tblTeams` (`idTeam`, `strTeamName`, `intTeamCategory`) VALUES (NULL, 'NGM Mobile Forward Racing', 1);
INSERT INTO `dbBikes`.`tblTeams` (`idTeam`, `strTeamName`, `intTeamCategory`) VALUES (NULL, 'Paul Bird Motorsport', 1);
INSERT INTO `dbBikes`.`tblTeams` (`idTeam`, `strTeamName`, `intTeamCategory`) VALUES (NULL, 'Power Electronics Aspar', 1);
INSERT INTO `dbBikes`.`tblTeams` (`idTeam`, `strTeamName`, `intTeamCategory`) VALUES (NULL, 'Pramac Racing Team', 1);
INSERT INTO `dbBikes`.`tblTeams` (`idTeam`, `strTeamName`, `intTeamCategory`) VALUES (NULL, 'San Carlo Honda Gresini', 1);
INSERT INTO `dbBikes`.`tblTeams` (`idTeam`, `strTeamName`, `intTeamCategory`) VALUES (NULL, 'Speed Master', 1);

COMMIT;

-- -----------------------------------------------------
-- Data for table `dbBikes`.`tblBikes`
-- -----------------------------------------------------
START TRANSACTION;
USE `dbBikes`;
INSERT INTO `dbBikes`.`tblBikes` (`idBike`, `strBikeName`, `intBikeCategory`) VALUES (NULL, 'Honda', 1);
INSERT INTO `dbBikes`.`tblBikes` (`idBike`, `strBikeName`, `intBikeCategory`) VALUES (NULL, 'Yamaha', 1);
INSERT INTO `dbBikes`.`tblBikes` (`idBike`, `strBikeName`, `intBikeCategory`) VALUES (NULL, 'ART', 1);

COMMIT;

-- -----------------------------------------------------
-- Data for table `dbBikes`.`tblRiders`
-- -----------------------------------------------------
START TRANSACTION;
USE `dbBikes`;
INSERT INTO `dbBikes`.`tblRiders` (`idRider`, `strRiderName`, `intRiderTeam`, `intRiderCategory`, `intRiderNumber`, `dtRiderBirth`, `intRiderBike`, `strRiderCity`, `intRiderWeight`, `intRiderHeight`, `intRiderCountry`) VALUES (NULL, 'Casey Stoner', 1, 1, 1, NULL , 1, 'Southport', 58, 171, 14);
INSERT INTO `dbBikes`.`tblRiders` (`idRider`, `strRiderName`, `intRiderTeam`, `intRiderCategory`, `intRiderNumber`, `dtRiderBirth`, `intRiderBike`, `strRiderCity`, `intRiderWeight`, `intRiderHeight`, `intRiderCountry`) VALUES (NULL, 'Jorge Lorenzo', 2, 1, 99, NULL, 2, 'Palma de Mallorca', 65, 172, 206);
INSERT INTO `dbBikes`.`tblRiders` (`idRider`, `strRiderName`, `intRiderTeam`, `intRiderCategory`, `intRiderNumber`, `dtRiderBirth`, `intRiderBike`, `strRiderCity`, `intRiderWeight`, `intRiderHeight`, `intRiderCountry`) VALUES (NULL, 'James Ellison', 10, 1, 77, NULL, 3, 'Kendal', 66, 172, 232);

COMMIT;