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

ALTER TABLE settings
   ADD CONSTRAINT settings_name_a_unique UNIQUE (name);
/** 
 * -----------------------------------------------------
 * Table site_emails_content
 * -----------------------------------------------------
 */ 
CREATE  TABLE IF NOT EXISTS site_emails_content (
  id BIGSERIAL NOT NULL,
  name VARCHAR(45) NOT NULL ,
  subject VARCHAR(100) NOT NULL ,
  body TEXT NOT NULL ,
  available_variables TEXT NULL ,
  date_of_update TIMESTAMP NULL ,
  version INT DEFAULT 0,

  PRIMARY KEY (id) 

);

CREATE UNIQUE INDEX site_emails_content_name_UNIQUE ON site_emails_content (UPPER(name));


ALTER TABLE site_emails_content
  ADD CONSTRAINT site_emails_content_name_foreign FOREIGN KEY (name)
      REFERENCES settings (name)
      ON UPDATE CASCADE ON DELETE CASCADE;



    
/**
 * -----------------------------------------------------
 * Trigger function(s) for table settings.
 * ----------------------------------------------------- 
 */
CREATE OR REPLACE FUNCTION trg_site_emails_content_bu ()
    RETURNS trigger AS $$
    BEGIN
        NEW.date_of_update := current_timestamp;
        
        RETURN NEW;
    END;
    $$ LANGUAGE PLPGSQL;

/**
 * -----------------------------------------------------
 * Trigger(s) for table settings.
 * ----------------------------------------------------- 
 */
CREATE TRIGGER site_emails_content_bu BEFORE UPDATE ON site_emails_content
  FOR EACH ROW EXECUTE PROCEDURE trg_site_emails_content_bu();  
