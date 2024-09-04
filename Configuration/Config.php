<?php
namespace Configuration;
class Config {
    public const COOKIE_EXP_TIME = 2592000; // 30 days in seconds
    public const DB_HOST = '127.0.0.1';
    public const DB_NAME = 'unirent';
    public const DB_USER = 'root';
    public const DB_PASS = 'pippo';
    public const SQL_FILE_PATH = __DIR__ . '/../unirent.sql';
}
