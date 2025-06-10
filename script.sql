-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema login_register
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema login_register
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `login_register` DEFAULT CHARACTER SET utf8 ;
USE `login_register` ;

-- -----------------------------------------------------
-- Table `login_register`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `login_register`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(45) NOT NULL,
  `password` VARCHAR(65) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `login_register`.`vacation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `login_register`.`vacation` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `login_register`.`users_vacation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `login_register`.`users_vacation` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `users_id` INT NOT NULL,
  `vacation_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_users_vacation_users_idx` (`users_id` ASC) ,
  INDEX `fk_users_vacation_vacation1_idx` (`vacation_id` ASC) ,
  CONSTRAINT `fk_users_vacation_users`
    FOREIGN KEY (`users_id`)
    REFERENCES `login_register`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_vacation_vacation1`
    FOREIGN KEY (`vacation_id`)
    REFERENCES `login_register`.`vacation` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
