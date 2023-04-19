<?php
//Sets up the full PHP environment, loads in dependancies and functions.
define('BASE_PATH',dirname($_SERVER['DOCUMENT_ROOT']).'/');
session_start();
date_default_timezone_set('Europe/London');

require BASE_PATH.'vendor/autoload.php';

use donatj\UserAgent\UserAgentParser;
$parser = new UserAgentParser();
$ua = $parser->parse();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$loader = new \Twig\Loader\FilesystemLoader(BASE_PATH.'templates');
$twig = new \Twig\Environment($loader);

use Medoo\Medoo;
$database = new Medoo([
    'database_type' => $_ENV['DB_TYPE'],
    'database_name' => $_ENV['DB_DATABASE'],
    'server' => $_ENV['DB_SERVER'],
    'username' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
]);

use Pdp\Rules;
$TLD_LIST = Rules::fromPath('https://raw.githubusercontent.com/publicsuffix/list/master/public_suffix_list.dat');

use Spatie\Dns\Dns;
$DNS = new Dns();

if(isset($_COOKIE['user_token'])){
    $_SESSION['user_token'] = $_COOKIE['user_token'];
}

date_default_timezone_set('Europe/London');
require_once BASE_PATH.'functions.php';
?>
