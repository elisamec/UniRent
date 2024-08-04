<?php
namespace UniRent;
//session coockie expiration
if (!defined('COOKIE_EXP_TIME')) {
    define('COOKIE_EXP_TIME', 2592000); // 30 days in seconds
}
//database connection
if (!defined('DB_HOST')) {
    define('DB_HOST', '127.0.0.1');
}
if (!defined('DB_NAME')) {
    define('DB_NAME', 'unirent');
}
if (!defined('DB_USER')) {
    define('DB_USER', 'root');
}
if (!defined('DB_PASS')) {
    define('DB_PASS', 'pippo');
}
//sql file path
if (!defined('SQL_FILE_PATH')) {
    define('SQL_FILE_PATH', 'unirent.sql');
}