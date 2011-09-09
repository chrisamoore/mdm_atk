SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `mdma_rdb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `mdma_rdb` ;

-- -----------------------------------------------------
-- Table `mdma_rdb`.`image`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mdma_rdb`.`image` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NULL ,
  `location` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mdma_rdb`.`page`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mdma_rdb`.`page` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `active` ENUM('Y','N') NULL ,
  `name` VARCHAR(255) NULL ,
  `subhead` VARCHAR(255) NULL ,
  `copy` TEXT NULL ,
  `image_id` INT NOT NULL ,
  PRIMARY KEY (`id`, `image_id`) ,
  INDEX `fk_pages_images1` (`image_id` ASC) ,
  CONSTRAINT `fk_pages_images1`
    FOREIGN KEY (`image_id` )
    REFERENCES `mdma_rdb`.`image` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mdma_rdb`.`category`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mdma_rdb`.`category` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mdma_rdb`.`project`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mdma_rdb`.`project` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `active` ENUM('Y','N') NULL ,
  `name` VARCHAR(255) NULL ,
  `subhead` VARCHAR(255) NULL ,
  `caption` TEXT NULL ,
  `image_id` INT NOT NULL ,
  `category_id` INT NOT NULL ,
  PRIMARY KEY (`id`, `image_id`, `category_id`) ,
  INDEX `fk_projects_images` (`image_id` ASC) ,
  INDEX `fk_projects_categories1` (`category_id` ASC) ,
  CONSTRAINT `fk_projects_images`
    FOREIGN KEY (`image_id` )
    REFERENCES `mdma_rdb`.`image` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_projects_categories1`
    FOREIGN KEY (`category_id` )
    REFERENCES `mdma_rdb`.`category` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
