<?php

  // Singleton DataBase Connection. 
  class Db {
    private static $instance = NULL;

    private function __construct() {}

    private function __clone() {}

    public static function getInstance() {
//      $servername = getenv('DB_HOST');
//      $username   = getenv('DB_USER');
//      $password   = getenv('DB_PASS');
//      $database   = getenv('DB_NAME');
//     $dbport     = getenv('DB_PORT');      
      
      $servername = "localhost";
      $username   = "s4u_dbuser";
      $password   = "csis4495";
      $database   = "s4u_s4udb";
      $dbport     = 3306;      

      if (!isset(self::$instance)) {
        self::$instance = new mysqli($servername, $username, $password, $database);
        // Check connection
        if (self::$instance->connect_error) {
          die("Erro de conexao: " . self::$instance->connect_error);
        } 
      } 
      
      return self::$instance;
    }
  }
?>