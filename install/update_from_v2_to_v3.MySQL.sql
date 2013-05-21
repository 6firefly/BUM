/**
 * -----------------------------------------------------
 * Table password_recovery
 * -----------------------------------------------------
 */
CREATE  TABLE IF NOT EXISTS password_recovery (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  id_user BIGINT UNSIGNED NOT NULL ,
  code VARCHAR(10) NOT NULL ,
  long_code VARCHAR(32) NOT NULL ,
  user_name VARCHAR(45) NULL ,
  email VARCHAR(60) NOT NULL ,
  ip VARBINARY(16) NOT NULL,
  used BOOLEAN NOT NULL DEFAULT FALSE,
  date_of_request TIMESTAMP NOT NULL ,
  expired BOOLEAN NOT NULL DEFAULT FALSE,

  PRIMARY KEY (id),
  
  FOREIGN KEY (id_user) REFERENCES users (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)ENGINE = InnoDB;

CREATE UNIQUE INDEX password_recovery_lc_UNIQUE ON password_recovery (long_code);




DELIMITER //
/**
 * -----------------------------------------------------
 * Trigger function(s) for table password_recovery.
 * ----------------------------------------------------- 
 */
CREATE TRIGGER trg_password_recovery_bi BEFORE INSERT ON password_recovery
    FOR EACH ROW BEGIN
        SET NEW.date_of_request = current_timestamp;
    END; //
DELIMITER ;

