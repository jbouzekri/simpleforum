-- -----------------------------------------------------
-- Table simpleforum_topic
-- -----------------------------------------------------
CREATE TABLE simpleforum_topic (
  id int(11) NOT NULL auto_increment,
  node_id int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  name varchar(150) NOT NULL default '',
  content longtext NOT NULL,
  view_count int(11) NOT NULL default '0',
  response_count int(11) NOT NULL default '0',
  state enum('VALIDATED', 'MODERATED', 'PUBLISHED', 'CLOSED') default 'PUBLISHED',
  published int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  language_id int(11) NOT NULL default '0',
  PRIMARY KEY (id)
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Table simpleforum_response
-- -----------------------------------------------------
CREATE TABLE simpleforum_response (
  id int(11) NOT NULL auto_increment,
  topic_id int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  name varchar(150) NOT NULL default '',
  content longtext NOT NULL,
  positive_vote int(11) NOT NULL default '0',
  total_vote int(11) NOT NULL default '0',
  state enum('VALIDATED', 'MODERATED', 'PUBLISHED') default 'PUBLISHED',
  published int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
    CONSTRAINT FOREIGN KEY (topic_id)
      REFERENCES simpleforum_topic (id) ON DELETE CASCADE
) ENGINE=InnoDB;
