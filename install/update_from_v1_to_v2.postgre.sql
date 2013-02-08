ALTER TABLE users
    ADD status SMALLINT NOT NULL DEFAULT 0;

ALTER TABLE users_data
    ADD obs TEXT NULL;
    
    
    
ALTER TABLE users_data
    ADD invitations_left SMALLINT NOT NULL DEFAULT -1;
    
    
CREATE  TABLE IF NOT EXISTS invitations (
  id BIGSERIAL NOT NULL,
  
  id_user BIGINT NOT NULL ,
  id_user_invited BIGINT NULL ,
  
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
);

CREATE UNIQUE INDEX invitations_email_code_UNIQUE ON invitations (UPPER(email), UPPER(invitation_code));

CREATE  TABLE IF NOT EXISTS settings (
  id BIGSERIAL NOT NULL ,
  
  name VARCHAR(45) NOT NULL ,
  value VARCHAR(45) NOT NULL ,
  label VARCHAR(80) NOT NULL ,
  description TEXT  NULL ,
  date_of_update TIMESTAMP NULL,
  
  PRIMARY KEY (id)
);

CREATE UNIQUE INDEX settings_name_UNIQUE ON settings (UPPER(name));


/**
 * -----------------------------------------------------
 * Trigger function(s) for table invitations.
 * ----------------------------------------------------- 
 */
CREATE OR REPLACE FUNCTION trg_invitations_bi ()
    RETURNS trigger AS $$
    BEGIN
        NEW.date_of_invitation_send := current_timestamp;
        
        RETURN NEW;
    END;
    $$ LANGUAGE PLPGSQL;

/**
 * -----------------------------------------------------
 * Trigger function(s) for table settings.
 * ----------------------------------------------------- 
 */
CREATE OR REPLACE FUNCTION trg_settings_bu ()
    RETURNS trigger AS $$
    BEGIN
        NEW.date_of_update := current_timestamp;
        
        RETURN NEW;
    END;
    $$ LANGUAGE PLPGSQL;

/**
 * -----------------------------------------------------
 * Trigger(s) for table invitations.
 * ----------------------------------------------------- 
 */
CREATE TRIGGER invitations_bi BEFORE INSERT ON invitations
  FOR EACH ROW EXECUTE PROCEDURE trg_invitations_bi();  

/**
 * -----------------------------------------------------
 * Trigger(s) for table settings.
 * ----------------------------------------------------- 
 */
CREATE TRIGGER settings_bu BEFORE UPDATE ON settings
  FOR EACH ROW EXECUTE PROCEDURE trg_settings_bu();  
  