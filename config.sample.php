<?php
/*
 * 配置数据库
 */
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_USER', 'root');
define('DB_PWD', 'password');
define('DB_NAME','favclip');
define('DOUBAN', 'http://www.douban.com/');
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

require_once('db_connect.php');
?>