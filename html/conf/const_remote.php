<?php
 
// データベースの接続情報
define('DB_USER',   'root');      // MySQLのユーザ名（マイページのアカウント情報を参照）
define('DB_PASSWD', 'sDc8rh4aD');    // MySQLのパスワード（マイページのアカウント情報を参
define('DB_NAME', 'workout_shop'); // MySQLのDB名(このコースではMySQLのユーザ名と同じで
define('DB_CHARSET', 'SET NAMES utf8mb4');  // MySQLのcharset
define('DSN', 'mysql:dbname='.DB_NAME.';host=localhost;charset=utf8');  // データベースのDSN情報
 
define('HTML_CHARACTER_SET', 'UTF-8');  // HTML文字エンコーディング
?>