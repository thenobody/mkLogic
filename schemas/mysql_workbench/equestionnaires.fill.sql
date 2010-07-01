SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
CREATE SCHEMA IF NOT EXISTS `equestionnaires` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ;

-- -----------------------------------------------------
-- Table `equestionnaires`.`questionnaire_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `equestionnaires`.`questionnaire_status` ;

CREATE  TABLE IF NOT EXISTS `equestionnaires`.`questionnaire_status` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `value` VARCHAR(20) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `equestionnaires`.`questionnaires`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `equestionnaires`.`questionnaires` ;

CREATE  TABLE IF NOT EXISTS `equestionnaires`.`questionnaires` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(40) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `status_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_questionnaires_questionnaire_status1` (`status_id` ASC) ,
  CONSTRAINT `fk_questionnaires_questionnaire_status1`
    FOREIGN KEY (`status_id` )
    REFERENCES `equestionnaires`.`questionnaire_status` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `equestionnaires`.`questions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `equestionnaires`.`questions` ;

CREATE  TABLE IF NOT EXISTS `equestionnaires`.`questions` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `question_text` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `template` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `first` TINYINT(1)  NOT NULL DEFAULT 0 ,
  `questionnaire_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_questions_questionnaires1` (`questionnaire_id` ASC) ,
  CONSTRAINT `fk_questions_questionnaires1`
    FOREIGN KEY (`questionnaire_id` )
    REFERENCES `equestionnaires`.`questionnaires` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `equestionnaires`.`question_groups`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `equestionnaires`.`question_groups` ;

CREATE  TABLE IF NOT EXISTS `equestionnaires`.`question_groups` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(20) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `label` VARCHAR(40) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `question_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_question_groups_questions1` (`question_id` ASC) ,
  CONSTRAINT `fk_question_groups_questions1`
    FOREIGN KEY (`question_id` )
    REFERENCES `equestionnaires`.`questions` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `equestionnaires`.`answer_types`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `equestionnaires`.`answer_types` ;

CREATE  TABLE IF NOT EXISTS `equestionnaires`.`answer_types` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(20) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `equestionnaires`.`answer_groups`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `equestionnaires`.`answer_groups` ;

CREATE  TABLE IF NOT EXISTS `equestionnaires`.`answer_groups` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(20) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `label` VARCHAR(40) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `type_id` INT(10) UNSIGNED NOT NULL ,
  `question_group_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_answer_groups_question_groups1` (`question_group_id` ASC) ,
  INDEX `fk_answer_groups_answer_types1` (`type_id` ASC) ,
  CONSTRAINT `fk_answer_groups_question_groups1`
    FOREIGN KEY (`question_group_id` )
    REFERENCES `equestionnaires`.`question_groups` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_answer_groups_answer_types1`
    FOREIGN KEY (`type_id` )
    REFERENCES `equestionnaires`.`answer_types` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `equestionnaires`.`answers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `equestionnaires`.`answers` ;

CREATE  TABLE IF NOT EXISTS `equestionnaires`.`answers` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(20) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `text` TINYINT(1) NOT NULL DEFAULT '0' ,
  `value` VARCHAR(40) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `label` VARCHAR(40) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `answer_limit` INT(11) NOT NULL DEFAULT '0' ,
  `answer_group_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_answers_answer_groups1` (`answer_group_id` ASC) ,
  CONSTRAINT `fk_answers_answer_groups1`
    FOREIGN KEY (`answer_group_id` )
    REFERENCES `equestionnaires`.`answer_groups` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `equestionnaires`.`constraint_rules`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `equestionnaires`.`constraint_rules` ;

CREATE  TABLE IF NOT EXISTS `equestionnaires`.`constraint_rules` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(20) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `rule_regexp` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `equestionnaires`.`constraints`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `equestionnaires`.`constraints` ;

CREATE  TABLE IF NOT EXISTS `equestionnaires`.`constraints` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `answer_id` INT(10) UNSIGNED NOT NULL ,
  `rule_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_constraints_answers1` (`answer_id` ASC) ,
  INDEX `fk_constraints_constraint_rules1` (`rule_id` ASC) ,
  CONSTRAINT `fk_constraints_answers1`
    FOREIGN KEY (`answer_id` )
    REFERENCES `equestionnaires`.`answers` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_constraints_constraint_rules1`
    FOREIGN KEY (`rule_id` )
    REFERENCES `equestionnaires`.`constraint_rules` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `equestionnaires`.`logical_operators`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `equestionnaires`.`logical_operators` ;

CREATE  TABLE IF NOT EXISTS `equestionnaires`.`logical_operators` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(10) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `equestionnaires`.`filtering_order`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `equestionnaires`.`filtering_order` ;

CREATE  TABLE IF NOT EXISTS `equestionnaires`.`filtering_order` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `question_id` INT(10) UNSIGNED NOT NULL ,
  `constraint_id` INT(10) UNSIGNED NOT NULL ,
  `next_constraint_id` INT(10) UNSIGNED NOT NULL ,
  `logical_operator_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_filtering_order_questions1` (`question_id` ASC) ,
  INDEX `fk_filtering_order_constraints1` (`constraint_id` ASC) ,
  INDEX `fk_filtering_order_constraints2` (`next_constraint_id` ASC) ,
  INDEX `fk_filtering_logical_operators1` (`logical_operator_id` ASC) ,
  CONSTRAINT `fk_filtering_order_questions1`
    FOREIGN KEY (`question_id` )
    REFERENCES `equestionnaires`.`questions` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_filtering_order_constraints1`
    FOREIGN KEY (`constraint_id` )
    REFERENCES `equestionnaires`.`constraints` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_filtering_order_constraints2`
    FOREIGN KEY (`next_constraint_id` )
    REFERENCES `equestionnaires`.`constraints` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_filtering_logical_operators1`
    FOREIGN KEY (`logical_operator_id` )
    REFERENCES `equestionnaires`.`logical_operators` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `equestionnaires`.`questionnaire_order`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `equestionnaires`.`questionnaire_order` ;

CREATE  TABLE IF NOT EXISTS `equestionnaires`.`questionnaire_order` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `question_id` INT(10) UNSIGNED NOT NULL ,
  `next_question_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_questionnaire_order_questions1` (`question_id` ASC) ,
  INDEX `fk_questionnaire_order_questions2` (`next_question_id` ASC) ,
  CONSTRAINT `fk_questionnaire_order_questions1`
    FOREIGN KEY (`question_id` )
    REFERENCES `equestionnaires`.`questions` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_questionnaire_order_questions2`
    FOREIGN KEY (`next_question_id` )
    REFERENCES `equestionnaires`.`questions` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `equestionnaires`.`supervisors`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `equestionnaires`.`supervisors` ;

CREATE  TABLE IF NOT EXISTS `equestionnaires`.`supervisors` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `password` VARCHAR(40) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `email` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `active` TINYINT(1) NOT NULL DEFAULT '1' ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `equestionnaires`.`token_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `equestionnaires`.`token_status` ;

CREATE  TABLE IF NOT EXISTS `equestionnaires`.`token_status` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `value` VARCHAR(20) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `equestionnaires`.`tokens`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `equestionnaires`.`tokens` ;

CREATE  TABLE IF NOT EXISTS `equestionnaires`.`tokens` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `token` VARCHAR(64) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `questionnaire_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `status_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_tokens_token_status1` (`status_id` ASC) ,
  INDEX `fk_tokens_questionnaires1` (`questionnaire_id` ASC) ,
  CONSTRAINT `fk_tokens_token_status1`
    FOREIGN KEY (`status_id` )
    REFERENCES `equestionnaires`.`token_status` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_tokens_questionnaires1`
    FOREIGN KEY (`questionnaire_id` )
    REFERENCES `equestionnaires`.`questionnaires` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `equestionnaires`.`user_answers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `equestionnaires`.`user_answers` ;

CREATE  TABLE IF NOT EXISTS `equestionnaires`.`user_answers` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `answer_id` INT(10) UNSIGNED NOT NULL ,
  `token_id` INT(10) UNSIGNED NOT NULL ,
  `text_value` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_user_answers_answers1` (`answer_id` ASC) ,
  INDEX `fk_user_answers_tokens1` (`token_id` ASC) ,
  CONSTRAINT `fk_user_answers_answers1`
    FOREIGN KEY (`answer_id` )
    REFERENCES `equestionnaires`.`answers` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_user_answers_tokens1`
    FOREIGN KEY (`token_id` )
    REFERENCES `equestionnaires`.`tokens` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `equestionnaires`.`validation_order`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `equestionnaires`.`validation_order` ;

CREATE  TABLE IF NOT EXISTS `equestionnaires`.`validation_order` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `question_id` INT(10) UNSIGNED NOT NULL ,
  `constraint_id` INT(10) UNSIGNED NOT NULL ,
  `next_constraint_id` INT(10) UNSIGNED NOT NULL ,
  `logical_operator_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_validation_order_questions1` (`question_id` ASC) ,
  INDEX `fk_validation_order_constraints1` (`constraint_id` ASC) ,
  INDEX `fk_validation_order_constraints2` (`next_constraint_id` ASC) ,
  INDEX `fk_validation_order_logical_operators1` (`logical_operator_id` ASC) ,
  CONSTRAINT `fk_validation_order_questions1`
    FOREIGN KEY (`question_id` )
    REFERENCES `equestionnaires`.`questions` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_validation_order_constraints1`
    FOREIGN KEY (`constraint_id` )
    REFERENCES `equestionnaires`.`constraints` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_validation_order_constraints2`
    FOREIGN KEY (`next_constraint_id` )
    REFERENCES `equestionnaires`.`constraints` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_validation_order_logical_operators1`
    FOREIGN KEY (`logical_operator_id` )
    REFERENCES `equestionnaires`.`logical_operators` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `equestionnaires`.`questionnaire_status`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `equestionnaires`.`questionnaire_status` (`id`, `value`) VALUES (1, 'ready');
INSERT INTO `equestionnaires`.`questionnaire_status` (`id`, `value`) VALUES (2, 'active');
INSERT INTO `equestionnaires`.`questionnaire_status` (`id`, `value`) VALUES (3, 'closed');
INSERT INTO `equestionnaires`.`questionnaire_status` (`id`, `value`) VALUES (4, 'cancelled');

COMMIT;

-- -----------------------------------------------------
-- Data for table `equestionnaires`.`answer_types`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `equestionnaires`.`answer_types` (`id`, `name`) VALUES (1, 'radio');
INSERT INTO `equestionnaires`.`answer_types` (`id`, `name`) VALUES (2, 'checkbox');
INSERT INTO `equestionnaires`.`answer_types` (`id`, `name`) VALUES (3, 'combobox');
INSERT INTO `equestionnaires`.`answer_types` (`id`, `name`) VALUES (4, 'text');

COMMIT;

-- -----------------------------------------------------
-- Data for table `equestionnaires`.`constraint_rules`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `equestionnaires`.`constraint_rules` (`id`, `name`, `rule_regexp`) VALUES (1, 'not-empty', NULL);
INSERT INTO `equestionnaires`.`constraint_rules` (`id`, `name`, `rule_regexp`) VALUES (2, 'empty', NULL);
INSERT INTO `equestionnaires`.`constraint_rules` (`id`, `name`, `rule_regexp`) VALUES (3, 'numeric', NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `equestionnaires`.`logical_operators`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `equestionnaires`.`logical_operators` (`id`, `name`) VALUES (1, 'AND');
INSERT INTO `equestionnaires`.`logical_operators` (`id`, `name`) VALUES (2, 'OR');
INSERT INTO `equestionnaires`.`logical_operators` (`id`, `name`) VALUES (3, 'IF');

COMMIT;

-- -----------------------------------------------------
-- Data for table `equestionnaires`.`token_status`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `equestionnaires`.`token_status` (`id`, `value`) VALUES (1, 'free');
INSERT INTO `equestionnaires`.`token_status` (`id`, `value`) VALUES (2, 'in-use');
INSERT INTO `equestionnaires`.`token_status` (`id`, `value`) VALUES (3, 'used');

COMMIT;
