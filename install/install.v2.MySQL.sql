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
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  
  name VARCHAR(45) NOT NULL ,
  value VARCHAR(45) NOT NULL ,
  label VARCHAR(80) NOT NULL ,
  description TEXT  NULL ,
  date_of_update TIMESTAMP NULL,
  
  PRIMARY KEY (id)
)ENGINE = InnoDB;

CREATE UNIQUE INDEX settings_name_UNIQUE ON settings(name);

/** 
 * -----------------------------------------------------
 * Table users
 * -----------------------------------------------------
 */ 
CREATE  TABLE IF NOT EXISTS users (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  
  user_name VARCHAR(45) NOT NULL ,
  email VARCHAR(60) NOT NULL  DEFAULT 'noEmail@noEmail.com',
  pass VARCHAR(150) NOT NULL ,
  salt VARCHAR(45) NOT NULL ,
  name VARCHAR(45) NULL ,
  surname VARCHAR(45) NULL ,
  active BOOLEAN NOT NULL DEFAULT FALSE,
  status TINYINT NOT NULL DEFAULT 0,
  date_of_creation TIMESTAMP NOT NULL ,
  date_of_update TIMESTAMP NULL ,
  date_of_last_access TIMESTAMP NOT NULL ,
  date_of_password_last_change TIMESTAMP NOT NULL,
  
  PRIMARY KEY (id)
)ENGINE=InnoDB;

CREATE UNIQUE INDEX users_user_name_UNIQUE ON users (user_name);
CREATE UNIQUE INDEX users_email_UNIQUE ON users (email);

/**
 * -----------------------------------------------------
 * Table users_data
 * -----------------------------------------------------
 */
CREATE  TABLE IF NOT EXISTS users_data (
  id BIGINT UNSIGNED NOT NULL,
  
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
)ENGINE=InnoDB;

/**
 * -----------------------------------------------------
 * Table invitations
 * -----------------------------------------------------
 */
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

/**
 * -----------------------------------------------------
 * Table emails
 * -----------------------------------------------------
 */
CREATE  TABLE IF NOT EXISTS emails (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  
  id_user BIGINT UNSIGNED NOT NULL ,
  
  name VARCHAR(60) NULL ,
  verified BOOLEAN NOT NULL DEFAULT FALSE,
  verification_code VARCHAR(40) NULL,
  date_of_creation TIMESTAMP NOT NULL ,
  visible BOOLEAN DEFAULT FALSE ,
  
  PRIMARY KEY (id) ,
  
  FOREIGN KEY (id_user) REFERENCES users (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE UNIQUE INDEX emails_user_emails_UNIQUE ON emails (id_user, name);

/***************************************************************************/
DELIMITER //
/**
 * -----------------------------------------------------
 * Trigger function(s) for table users.
 * ----------------------------------------------------- 
 */
 
CREATE TRIGGER trg_users_bi BEFORE INSERT ON users
  FOR EACH ROW BEGIN
        SET NEW.date_of_creation = current_timestamp;
        SET NEW.date_of_update =  current_timestamp;
        SET NEW.date_of_last_access =  current_timestamp;
        SET NEW.date_of_password_last_change =  current_timestamp;
    END; //

CREATE TRIGGER trg_users_bu BEFORE UPDATE ON users
  FOR EACH ROW BEGIN
        SET NEW.date_of_update =  current_timestamp;
        IF NEW.pass != OLD.pass THEN
            SET NEW.date_of_password_last_change =  current_timestamp;
        END IF;
    END; //

/**
 * -----------------------------------------------------
 * Trigger function(s) for table users_data.
 * ----------------------------------------------------- 
 */
CREATE TRIGGER trg_users_data_bi BEFORE INSERT ON users_data
  FOR EACH ROW BEGIN
        SET NEW.date_of_update =  current_timestamp;
    END; //

CREATE TRIGGER trg_users_data_bu BEFORE UPDATE ON users_data
    FOR EACH ROW BEGIN
        SET NEW.date_of_update =  current_timestamp;
    END; //

/**
 * -----------------------------------------------------
 * Trigger function(s) for table emails.
 * ----------------------------------------------------- 
 */
CREATE TRIGGER trg_emails_bi BEFORE INSERT ON emails
    FOR EACH ROW BEGIN
        SET NEW.date_of_creation = current_timestamp;
    END; //

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
/***************************************************************************/
DELIMITER ;


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
