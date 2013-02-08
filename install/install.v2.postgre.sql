/***************************************************************************/

DROP TABLE IF EXISTS emails CASCADE;
DROP TABLE IF EXISTS users_data CASCADE;
DROP TABLE IF EXISTS users CASCADE;

/***************************************************************************/

/** 
 * -----------------------------------------------------
 * Table settings
 * -----------------------------------------------------
 */ 
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
 * Table users
 * -----------------------------------------------------
 */ 
CREATE  TABLE IF NOT EXISTS users (
  id BIGSERIAL NOT NULL,
  
  user_name VARCHAR(45) NOT NULL ,
  email VARCHAR(60) NOT NULL  DEFAULT 'noEmail@noEmail.com',
  pass VARCHAR(150) NOT NULL ,
  salt VARCHAR(45) NOT NULL ,
  name VARCHAR(45) NULL ,
  surname VARCHAR(45) NULL ,
  active BOOLEAN NOT NULL DEFAULT FALSE,
  status SMALLINT NOT NULL DEFAULT 0,
  date_of_creation TIMESTAMP NOT NULL ,
  date_of_update TIMESTAMP NULL ,
  date_of_last_access TIMESTAMP NOT NULL ,
  date_of_password_last_change TIMESTAMP NOT NULL,
  
  PRIMARY KEY (id)
);

CREATE UNIQUE INDEX users_user_name_UNIQUE ON users (UPPER(user_name));
CREATE UNIQUE INDEX users_email_UNIQUE ON users (UPPER(email));

/**
 * -----------------------------------------------------
 * Table users_data
 * -----------------------------------------------------
 */
CREATE  TABLE IF NOT EXISTS users_data (
  id BIGINT  NOT NULL ,
  
  description TEXT NULL ,
  obs TEXT NULL,
  site VARCHAR(1500) NULL ,
  facebook_address VARCHAR(60) NULL,
  twitter_address VARCHAR(60) NULL,
  activation_code VARCHAR(45) NULL,
  date_of_update TIMESTAMP NULL,
  invitations_left SMALLINT NOT NULL DEFAULT -1,
  
  PRIMARY KEY (id) ,
  
  FOREIGN KEY (id) REFERENCES users (id )
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

/**
 * -----------------------------------------------------
 * Table invitations
 * -----------------------------------------------------
 */
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

/**
 * -----------------------------------------------------
 * Table emails
 * -----------------------------------------------------
 */
CREATE  TABLE IF NOT EXISTS emails (
  id BIGSERIAL  NOT NULL ,
  
  id_user BIGINT  NOT NULL ,
  
  name VARCHAR(60) NULL ,
  verified BOOLEAN NOT NULL DEFAULT FALSE,
  verification_code VARCHAR(40) NULL,
  date_of_creation TIMESTAMP NOT NULL ,
  visible BOOLEAN DEFAULT FALSE ,
  
  PRIMARY KEY (id) ,
  
  FOREIGN KEY (id_user) REFERENCES users (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE UNIQUE INDEX emails_user_emails_UNIQUE ON emails (id_user, UPPER(name));

/***************************************************************************/
/**
 * -----------------------------------------------------
 * Trigger function(s) for table users.
 * ----------------------------------------------------- 
 */
CREATE OR REPLACE FUNCTION trg_users_bi ()
    RETURNS trigger AS $$
    BEGIN
        NEW.date_of_creation := current_timestamp;
        NEW.date_of_update :=  current_timestamp;
        NEW.date_of_last_access :=  current_timestamp;
        NEW.date_of_password_last_change :=  current_timestamp;
        
        RETURN NEW;
    END;
    $$ LANGUAGE PLPGSQL;

CREATE OR REPLACE FUNCTION trg_users_bu ()
    RETURNS trigger AS $$
    BEGIN
    
        NEW.date_of_update :=  current_timestamp;
        IF NEW.pass != OLD.pass THEN
            NEW.date_of_password_last_change :=  current_timestamp;
        END IF;
        
        RETURN NEW;
    END;
    $$ LANGUAGE PLPGSQL;

/**
 * -----------------------------------------------------
 * Trigger function(s) for table users_data.
 * ----------------------------------------------------- 
 */
CREATE OR REPLACE FUNCTION trg_users_data_bi ()
    RETURNS trigger AS $$
    BEGIN
        NEW.date_of_update :=  current_timestamp;
        
        RETURN NEW;
    END;
    $$ LANGUAGE PLPGSQL;

CREATE OR REPLACE FUNCTION trg_users_data_bu ()
    RETURNS trigger AS $$
    BEGIN
    
        NEW.date_of_update :=  current_timestamp;
        
        RETURN NEW;
    END;
    $$ LANGUAGE PLPGSQL;
    
/**
 * -----------------------------------------------------
 * Trigger function(s) for table emails.
 * ----------------------------------------------------- 
 */
CREATE OR REPLACE FUNCTION trg_emails_bi ()
    RETURNS trigger AS $$
    BEGIN
        NEW.date_of_creation := current_timestamp;
        
        RETURN NEW;
    END;
    $$ LANGUAGE PLPGSQL;

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
/***************************************************************************/



/***************************************************************************/
/**
 * -----------------------------------------------------
 * Trigger(s) for table settings.
 * ----------------------------------------------------- 
 */
CREATE TRIGGER settings_bu BEFORE UPDATE ON settings
  FOR EACH ROW EXECUTE PROCEDURE trg_settings_bu();  
  
/**
 * -----------------------------------------------------
 * Trigger(s) for table emails.
 * ----------------------------------------------------- 
 */
CREATE TRIGGER emails_bi BEFORE INSERT ON emails
  FOR EACH ROW EXECUTE PROCEDURE trg_emails_bi();  
  
/**
 * -----------------------------------------------------
 * Trigger(s) for table invitations.
 * ----------------------------------------------------- 
 */
CREATE TRIGGER invitations_bi BEFORE INSERT ON invitations
  FOR EACH ROW EXECUTE PROCEDURE trg_invitations_bi();  
  
/**
 * -----------------------------------------------------
 * Trigger(s) for table users.
 * ----------------------------------------------------- 
 */
CREATE TRIGGER users_bi BEFORE INSERT ON users
  FOR EACH ROW EXECUTE PROCEDURE trg_users_bi();  
  
CREATE TRIGGER users_bu BEFORE UPDATE ON users
  FOR EACH ROW EXECUTE PROCEDURE trg_users_bu();  
  
/**
 * -----------------------------------------------------
 * Trigger(s) for table users_data.
 * ----------------------------------------------------- 
 */
CREATE TRIGGER users_data_bi BEFORE INSERT ON users_data
  FOR EACH ROW EXECUTE PROCEDURE trg_users_data_bi();  
  
CREATE TRIGGER users_data_bu BEFORE UPDATE ON users_data
  FOR EACH ROW EXECUTE PROCEDURE trg_users_data_bu();  
  
/***************************************************************************/

