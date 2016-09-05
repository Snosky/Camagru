#!/usr/bin/env php
<?php
    require('./database.php');

    if (!isset($DB_DSN) || !isset($DB_USER) || !isset($DB_PASSWORD) || empty($DB_DSN) || empty($DB_USER))
    {
        die('Error : database.php is not valid.');
    }

    try {
        $pdo = new \PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
    catch (\PDOException $e)
    {
        echo 'Error PDO: '.$e->getMessage();
        die();
    }

    echo 'Are you sure you want to do this ?! (yes/no)  ';

    flush();
    ob_flush();
    $confirmation  =  trim( fgets( STDIN ) );
    if ($confirmation !== 'yes' && $confirmation !== 'y')
        exit (0);

    echo 'Step 1 : Clear upload images files.'.PHP_EOL;

    $uploads_dir = dirname(__DIR__).'/web/img/uploads/';
    $uploads_files = glob($uploads_dir.'*.png');

    foreach ($uploads_files as $file)
        if (is_file($file))
            unlink($file);


    echo 'Step 1 : Done'.PHP_EOL;

    echo 'Step 2 : Database installation.'.PHP_EOL;
    $sql = "SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
            SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
            SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';
            DROP SCHEMA IF EXISTS `snosky_camagru` ;
            CREATE SCHEMA IF NOT EXISTS `snosky_camagru` DEFAULT CHARACTER SET utf8 ;
            USE `snosky_camagru` ;
            DROP TABLE IF EXISTS `snosky_camagru`.`t_user` ;
            CREATE TABLE IF NOT EXISTS `snosky_camagru`.`t_user` (
              `usr_id` INT NOT NULL AUTO_INCREMENT,
              `usr_username` VARCHAR(32) NULL,
              `usr_email` VARCHAR(255) NULL,
              `usr_password` VARCHAR(255) NULL,
              `usr_salt` VARCHAR(45) NULL,
              `usr_role` VARCHAR(45) NULL DEFAULT 'ROLE_USER',
              `usr_confirm` TINYINT(1) NULL DEFAULT 0,
              `usr_token` VARCHAR(32) NULL,
              PRIMARY KEY (`usr_id`))
            ENGINE = InnoDB;
            DROP TABLE IF EXISTS `snosky_camagru`.`t_image` ;
            CREATE TABLE IF NOT EXISTS `snosky_camagru`.`t_image` (
              `img_id` INT NOT NULL AUTO_INCREMENT,
              `usr_id` INT NOT NULL,
              `img_created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`img_id`),
              INDEX `fk_t_image_t_user_idx` (`usr_id` ASC),
              CONSTRAINT `fk_t_image_t_user`
                FOREIGN KEY (`usr_id`)
                REFERENCES `snosky_camagru`.`t_user` (`usr_id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION)
            ENGINE = InnoDB;
            DROP TABLE IF EXISTS `snosky_camagru`.`t_image_like` ;
            CREATE TABLE IF NOT EXISTS `snosky_camagru`.`t_image_like` (
              `img_id` INT NOT NULL,
              `usr_id` INT NOT NULL,
              INDEX `fk_t_image_like_t_image1_idx` (`img_id` ASC),
              INDEX `fk_t_image_like_t_user1_idx` (`usr_id` ASC),
              PRIMARY KEY (`img_id`, `usr_id`),
              CONSTRAINT `fk_t_image_like_t_image1`
                FOREIGN KEY (`img_id`)
                REFERENCES `snosky_camagru`.`t_image` (`img_id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION,
              CONSTRAINT `fk_t_image_like_t_user1`
                FOREIGN KEY (`usr_id`)
                REFERENCES `snosky_camagru`.`t_user` (`usr_id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION)
            ENGINE = InnoDB;
            DROP TABLE IF EXISTS `snosky_camagru`.`t_image_com` ;
            CREATE TABLE IF NOT EXISTS `snosky_camagru`.`t_image_com` (
              `com_id` INT NOT NULL AUTO_INCREMENT,
              `com_content` TEXT NULL,
              `img_id` INT NOT NULL,
              `usr_id` INT NOT NULL,
              `com_created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`com_id`),
              INDEX `fk_t_image_com_t_image1_idx` (`img_id` ASC),
              INDEX `fk_t_image_com_t_user1_idx` (`usr_id` ASC),
              CONSTRAINT `fk_t_image_com_t_image1`
                FOREIGN KEY (`img_id`)
                REFERENCES `snosky_camagru`.`t_image` (`img_id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION,
              CONSTRAINT `fk_t_image_com_t_user1`
                FOREIGN KEY (`usr_id`)
                REFERENCES `snosky_camagru`.`t_user` (`usr_id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION)
            ENGINE = InnoDB;
            SET SQL_MODE=@OLD_SQL_MODE;
            SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
            SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
            ";

            $sql = explode(';', $sql);
            $error = FALSE;
            foreach ($sql as $line)
            {
                $line = trim($line);
                if (!empty($line))
                {
                    try
                    {
                        $pdo->query($line);
                    }
                    catch (\PDOException $e)
                    {
                        echo 'Error sql : '. $e.PHP_EOL;
                        $error = TRUE;
                    }
                }
            }
            if ($error)
                echo 'Step 2 : Error, please retry.'.PHP_EOL;
            else
                echo 'Step 2 : DONE'.PHP_EOL;
?>
