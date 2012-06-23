-- -----------------------------------------------------
-- Table simpleforum_topic
-- -----------------------------------------------------
CREATE TABLE simpleforum_topic (
  id NUMBER NOT NULL PRIMARY KEY,
  node_id NUMBER NOT NULL,
  user_id NUMBER NOT NULL,
  name varchar2(150) NOT NULL,
  content CLOB NOT NULL,
  view_count NUMBER NOT NULL,
  response_count NUMBER NOT NULL,
  state VARCHAR2(20),
  published NUMBER NOT NULL,
  modified NUMBER NOT NULL,
  language_id NUMBER NOT NULL,
  CONSTRAINT simpleforum_topic_chk1 CHECK (state IN ('VALIDATED', 'MODERATED', 'PUBLISHED', 'CLOSED'))
);

-- -----------------------------------------------------
-- Table simpleforum_response
-- -----------------------------------------------------
CREATE TABLE simpleforum_response (
  id NUMBER NOT NULL PRIMARY KEY,
  topic_id NUMBER NOT NULL,
  user_id NUMBER NOT NULL,
  name varchar(150) NOT NULL,
  content CLOB NOT NULL,
  positive_vote NUMBER NOT NULL,
  total_vote NUMBER NOT NULL,
  state VARCHAR2(20),
  published NUMBER NOT NULL,
  CONSTRAINT simpleforum_response_chk1 FOREIGN KEY (topic_id)
      REFERENCES simpleforum_topic (id) ON DELETE CASCADE,
  CONSTRAINT simpleforum_response_chk2 CHECK (state IN ('VALIDATED', 'MODERATED', 'PUBLISHED'))
);

-- -----------------------------------------------------
-- Séquence simpleforum_topic
-- -----------------------------------------------------
CREATE SEQUENCE se_simpleforum_topic
  MINVALUE 1 
  MAXVALUE 9999999999999999999999999999 
  INCREMENT BY 1 
  START WITH 1
  NOCACHE ORDER NOCYCLE
;

-- -----------------------------------------------------
-- Séquence simpleforum_response
-- -----------------------------------------------------
CREATE SEQUENCE se_simpleforum_response
  MINVALUE 1 
  MAXVALUE 9999999999999999999999999999 
  INCREMENT BY 1 
  START WITH 1
  NOCACHE ORDER NOCYCLE
;

-- -----------------------------------------------------
-- Trigger simpleforum_topic
-- -----------------------------------------------------
CREATE OR REPLACE TRIGGER simpleforum_topic_id_tr
BEFORE INSERT ON simpleforum_topic FOR EACH ROW WHEN (new.id IS NULL) BEGIN
  SELECT se_simpleforum_topic.nextval INTO :new.id FROM dual;
END;
/

-- -----------------------------------------------------
-- Trigger simpleforum_response
-- -----------------------------------------------------
CREATE OR REPLACE TRIGGER simpleforum_response_id_tr
BEFORE INSERT ON simpleforum_response FOR EACH ROW WHEN (new.id IS NULL) BEGIN
  SELECT se_simpleforum_response.nextval INTO :new.id FROM dual;
END;
/
