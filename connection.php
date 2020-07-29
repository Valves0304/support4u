<?php

  // Singleton DataBase Connection.
  class Db {
    private static $instance = NULL;

    private function __construct() {}

    private function __clone() {}

    public static function getInstance() {
      
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
