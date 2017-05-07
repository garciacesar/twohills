<?php
require_once "Config.php";

define("HOST", "mysql427.umbler.com");
define("NAME", "twohills");
define("USER", "twohills");
define("PASS", "initnos3");

/*
define("HOST", "localhost");
define("NAME", "twohills");
define("USER", "root");
define("PASS", "root");
*/
class DB{

	private static $instance;

	public static function getInstance(){

		if (!isset(self::$instance)) {

			try {

				self::$instance = new PDO('mysql:host=' . HOST . ';dbname=' . NAME, USER, PASS);
				self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

			} catch (Exception $e) {

				echo $e->getMessage();

			}


		}

	return self::$instance;

	}

	public static function prepare($sql){
		return self::getInstance()->prepare($sql);
	}

}
