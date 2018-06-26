<?php

	class logger{
		public static $error_log_file = "myError.log";
		public static $debug = true;
		public static $error = true;
		public static $database = true;

		public static function logError(string $msg, string $function=null, string $class=null, string $action=null){
			if(isset($class)) $class = "[" . $class . "]";
			else $class = "";
			if(isset($function)) $class = $function . "():";
			else $function = "";
			if(isset($action)) $class = "{" . $action . "}=";
			else $action = "";
			if(self::$error) error_log("ERROR :: [".date("c")."] " . $class . $function . $action . $msg . PHP_EOL, 3, self::$error_log_file);
		}

		public static function logDB(string $msg, string $action=null){
			if(self::$database) error_log("[".date("c")."] [@DB] " . $action . "=" . $msg.PHP_EOL, 3, self::$error_log_file);
		}

		public static function logdebug(string $msg, string $function=null, string $class=null, string $action=null){
			if(isset($class)) $class = "[" . $class . "]";
			else $class = "";
			if(isset($function)) $class = $function . "():";
			else $function = "";
			if(isset($action)) $class = "{" . $action . "}=";
			else $action = "";
			if(self::$debug) error_log("[".date("c")."] DEBUG:: " . $class . $function . $action . $msg . PHP_EOL, 3, self::$error_log_file);
		}
	}
?>
