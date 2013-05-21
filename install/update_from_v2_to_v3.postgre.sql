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
  date_of_request TIMESTAMP NOT NULL ,
  expired BOOLEAN NOT NULL DEFAULT FALSE,

  PRIMARY KEY (id),

  
  FOREIGN KEY (id_user) REFERENCES users (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE UNIQUE INDEX password_recovery_lc_UNIQUE ON password_recovery (long_code);

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

  
/**
 * -----------------------------------------------------
 * Trigger(s) for table password_recovery.
 * ----------------------------------------------------- 
 */
CREATE TRIGGER password_recovery_bi BEFORE INSERT ON password_recovery
  FOR EACH ROW EXECUTE PROCEDURE trg_password_recovery_bi();  
