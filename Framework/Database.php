<?php

class Database {

  private $host;
  private $username;
  private $password;
  private $dbName;
  private $changeLog;

  public function __construct() {
    $this->host = 'localhost';
    $this->username = 'root';
    $this->password = '';
    $this->dbName = 'cacahuetes';
    $this->EnsureChangelogTableExists();
    $this->PopulateChangelog();
  }

  private function PopulateChangelog() {
  }
  
  private function EnsureChangelogTableExists() {    
    $tables = $this->GetResults('SHOW TABLES');
    foreach($tables as $table) {
      if (strcasecmp($table[0], 'changelogs') === 0) {
        return;
      }
    }
    $this->ExecuteSql(
            'CREATE TABLE `cacahuetes`.`changelogmodel` (
              id INT NOT NULL AUTO_INCREMENT,
              date DATETIME NOT NULL,
              name TEXT NOT NULL,
              PRIMARY KEY (id),
              UNIQUE INDEX id_UNIQUE (id ASC));');
  }
  
  private function ExecuteSql($sql) {
    $connection = $this->GetConnection();
    $statement = $connection->query($sql);
    $statement->execute();
  }

  private function GetResults($sql) {
    $connection = $this->GetConnection();
    $statement = $connection->query($sql);
    return $statement->fetchAll();
  }

  private function GetConnection() {
    $connection = new PDO(
            "mysql:host=$this->host;dbname=$this->dbName", $this->username, $this->password);
    return $connection;
  }

}
