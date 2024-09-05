<?php
namespace Configuration;


/**
 * Config class represents the configuration settings for the UniRent application.
 *
 * This class is responsible for managing the configuration settings such as database credentials,
 * file paths, and other application-specific settings.
 *
 * @package UniRent\Configuration
 */
class Config {


    /**
     * Cookie expiration time in seconds.
     *
     * This constant defines the expiration time for the cookies used by the UniRent application.
     * The default value is set to 2592000 seconds, which is equivalent to 30 days.
     *
     * @var int COOKIE_EXP_TIME
     *  
     */
    public const COOKIE_EXP_TIME = 2592000; 

    /**
     * Database host.
     *
     * This constant defines the host address of the database server used by the UniRent application.
     * The default value is set to '127.0.0.1'.
     *
     * @var string DB_HOST
     * 
     */
    public const DB_HOST = '127.0.0.1';

    /**
     * Database name.
     *
     * This constant defines the name of the database used by the UniRent application.
     * The default value is set to 'unirent'.
     *
     * @var string DB_NAME
     * 
     */
    public const DB_NAME = 'unirent';

    /**
     * Database username.
     *
     * This constant defines the username used to connect to the database server.
     * The default value is set to 'root'.
     *
     * @var string DB_USER
     *  
     */
    public const DB_USER = 'root';

    /**
     * Database password.
     *
     * This constant defines the password used to connect to the database server.
     * The default value is set to 'pippo'.
     *
     * @var string DB_PASS
     *  
     */
    public const DB_PASS = 'pippo';

    /**
     * SQL file path.
     *
     * This constant defines the file path of the SQL file used to initialize the database.
     * The default value is set to the relative path '../unirent.sql' from the current directory.
     *
     * @var string SQL_FILE_PATH
     *  
     */
    public const SQL_FILE_PATH = __DIR__ . '/../unirent.sql';
    
    
    
    
    
}
