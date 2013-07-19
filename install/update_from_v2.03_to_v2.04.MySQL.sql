INSERT INTO settings (name, value) SELECT 'sender_registerNewEmail', value FROM settings WHERE name = 'notificationVerificationEmail';
DELETE FROM settings WHERE name = 'notificationVerificationEmail';

INSERT INTO settings (name, value) SELECT 'sender_invitation', value FROM settings WHERE name = 'invitationEmail';
DELETE FROM settings WHERE name = 'invitationEmail';

INSERT INTO settings (name, value) SELECT 'sender_signUp', value FROM settings WHERE name = 'notificationSignUpEmail';
DELETE FROM settings WHERE name = 'notificationSignUpEmail';

INSERT INTO settings (name, value) SELECT 'sender_passwordRecovery', value FROM settings WHERE name = 'passwordRecoveryEmail';
DELETE FROM settings WHERE name = 'passwordRecoveryEmail';

ALTER TABLE settings 
    ADD   version INT DEFAULT 0;

/** 
 * -----------------------------------------------------
 * Table site_emails_content
 * -----------------------------------------------------
 */ 
CREATE  TABLE IF NOT EXISTS site_emails_content (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  name VARCHAR(45) NOT NULL ,
  subject VARCHAR(100) NOT NULL ,
  body TEXT NOT NULL ,
  available_variables TEXT NULL ,
  date_of_update TIMESTAMP NULL ,
  version INT DEFAULT 0,

  PRIMARY KEY (id) ,

  FOREIGN KEY (name) REFERENCES settings (name)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)ENGINE = InnoDB;

CREATE UNIQUE INDEX site_emails_content_name_UNIQUE ON site_emails_content (name);


DELIMITER //


/**
 * -----------------------------------------------------
 * Trigger function(s) for table site_emails_content.
 * ----------------------------------------------------- 
 */
CREATE TRIGGER trg_site_emails_content_bu BEFORE UPDATE ON site_emails_content
    FOR EACH ROW BEGIN
        SET NEW.date_of_update = current_timestamp;
    END; //


DELIMITER ;
