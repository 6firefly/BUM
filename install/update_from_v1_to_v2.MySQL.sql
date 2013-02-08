ALTER TABLE users
    ADD status TINYINT NOT NULL DEFAULT 0;

ALTER TABLE users_data
    ADD obs TEXT NULL;

        
ALTER TABLE users_data
    ADD invitations_left SMALLINT NOT NULL DEFAULT -1;

    
CREATE  TABLE IF NOT EXISTS invitations (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  
  id_user BIGINT UNSIGNED NOT NULL ,
  id_user_invited BIGINT UNSIGNED NULL,
  
  email VARCHAR(60) NOT NULL ,
  note TEXT NULL,
  invitation_code VARCHAR(10) NOT NULL ,
  date_of_invitation_send TIMESTAMP NULL ,
  date_of_invitation_accepted TIMESTAMP NULL ,
  
  PRIMARY KEY (id),
  
  FOREIGN KEY (id_user) REFERENCES users (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    
  FOREIGN KEY (id_user_invited) REFERENCES users (id)
    ON DELETE SET NULL
    ON UPDATE CASCADE
)ENGINE = InnoDB;

CREATE UNIQUE INDEX invitations_email_code_UNIQUE ON invitations (email, invitation_code);

CREATE  TABLE IF NOT EXISTS settings (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  
  name VARCHAR(45) NOT NULL ,
  value VARCHAR(45) NOT NULL ,
  label VARCHAR(80) NOT NULL ,
  description TEXT  NULL ,
  date_of_update TIMESTAMP NULL,
  
  PRIMARY KEY (id)
)ENGINE = InnoDB;

CREATE UNIQUE INDEX settings_name_UNIQUE ON settings(name);


DELIMITER //
/**
 * -----------------------------------------------------
 * Trigger function(s) for table invitations.
 * ----------------------------------------------------- 
 */
CREATE TRIGGER trg_invitations_bi BEFORE INSERT ON invitations
    FOR EACH ROW BEGIN
        SET NEW.date_of_invitation_send = current_timestamp;
    END; //
/**
 * -----------------------------------------------------
 * Trigger function(s) for table settings.
 * ----------------------------------------------------- 
 */
CREATE TRIGGER trg_settings_bu BEFORE UPDATE ON settings
    FOR EACH ROW BEGIN
        SET NEW.date_of_update = current_timestamp;
    END; //
DELIMITER ;
