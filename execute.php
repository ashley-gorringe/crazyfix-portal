<?php
//Sets up the full PHP environment, loads in dependancies and functions.
define('BASE_PATH',dirname($_SERVER['DOCUMENT_ROOT']).'/');
session_start();
date_default_timezone_set('Europe/London');

// Turn off all error reporting
error_reporting(0);

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

use Slim\Http\Request;
use Slim\Http\Response;
use Stripe\Stripe;

if(isset($_COOKIE['login_token'])){
    $_SESSION['login_token'] = $_COOKIE['login_token'];
}

date_default_timezone_set('Europe/London');
require_once BASE_PATH.'functions.php';
?>
