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
  setting_order INT NULL;
  
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
  date_of_creation TIMESTAMP NULL ,
  date_of_update TIMESTAMP NULL ,
  date_of_last_access TIMESTAMP NULL ,
  date_of_password_last_change TIMESTAMP NULL,
  
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
  date_of_creation TIMESTAMP NULL ,
  visible BOOLEAN DEFAULT FALSE ,
  
  PRIMARY KEY (id) ,
  
  FOREIGN KEY (id_user) REFERENCES users (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE UNIQUE INDEX emails_user_emails_UNIQUE ON emails (id_user, UPPER(name));

/**
 * -----------------------------------------------------
 * Table password_recovery
 * -----------------------------------------------------
 */
CREATE  TABLE IF NOT EXISTS password_recovery (
  id BIGSERIAL  NOT NULL ,
  id_user BIGINT  NOT NULL ,
  code VARCHAR(10) NOT NULL ,
  long_code VARCHAR(32) NOT NULL ,
  user_name VARCHAR(45) NULL ,
  email VARCHAR(60) NOT NULL ,
  ip cidr NOT NULL,
  used BOOLEAN NOT NULL DEFAULT FALSE,
  date_of_request TIMESTAMP NULL ,
  expired BOOLEAN NOT NULL DEFAULT FALSE,

  PRIMARY KEY (id),

  
  FOREIGN KEY (id_user) REFERENCES users (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE UNIQUE INDEX password_recovery_lc_UNIQUE ON password_recovery (long_code);


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
    
/**
 * -----------------------------------------------------
 * Trigger function(s) for table password_recovery.
 * ----------------------------------------------------- 
 */
CREATE OR REPLACE FUNCTION trg_password_recovery_bi ()
    RETURNS trigger AS $$
    BEGIN
        NEW.password_recovery := current_timestamp;
        
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
  
  
/**
 * -----------------------------------------------------
 * Trigger(s) for table password_recovery.
 * ----------------------------------------------------- 
 */
CREATE TRIGGER password_recovery_bi BEFORE INSERT ON password_recovery
  FOR EACH ROW EXECUTE PROCEDURE trg_password_recovery_bi();  
  
/***************************************************************************/

INSERT INTO settings (id, name, value, label, description) VALUES
	(1, 'logInIfNotVerified', '0', 'Allow users to LogIn if they are not active?', ''),
	(2, 'enabledSignUp', '0', 'SignUp is enabled?', 'If SignUp is disabled, no SignUps are allowed, in any case!'),
	(3, 'invitationBasedSignUp', '0', 'Only invited users are allowed to SignUp?', 'If SignUp is disabled, no user can SignUp, even invited ones!'),
	(4, 'invitationButtonDisplay', '0', 'Display the invitation button to all users?', ''),
	(5, 'invitationDefaultNumber', '5', 'Default number of invitations per user? (if <0 = infinit number)', ''),
	(6, 'invitationEmail', 'webmaster@localhost', 'Invitation email is sent from:', ''),
	(7, 'hoursInvitationLinkIsActive', '144', 'How many hours the invitation link is active? (if <0 = forever)', ''),
	(8, 'hoursActivationLinkIsActive', '72', 'How many hours the activation link is active? (if <0 = forever)', ''),
	(9, 'notificationSignUpEmail', 'webmaster@localhost', 'Activation email is sent from:', ''),
	(10, 'hoursVerificationLinkIsActive', '144', 'How many hours the email verification link is active? (if <0 = forever)', 'How many hours the email verification link is active? (when user associates a new email address to his/hers account)'),
	(11, 'notificationVerificationEmail', 'webmaster@localhost', 'Verification email is sent from:', '');
