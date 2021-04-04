<?
/**
 * Init application variables
 * @var Main $APP
 * @var User $USER
 */
use \Beegee\Tasks\Main,
    \Beegee\Tasks\User;

$dir = __DIR__;
$dir = str_replace("/public_html", "", $dir);
require_once $dir . "/modules/main/main.php";

define("DB_HOST", "localhost");
define("DB_LOGIN", "aksoft_beegee");
define("DB_PASSWORD", "wr8X4xNG");
define("DB_NAME", "aksoft_beegee");

global $output, $APP;

$APP = Main::getInstance();
session_start();
$APP->setPathSystem($dir);
$APP->openDbConnect();
$APP->setModules([
    "user",
    "task"
]);

/**
 * Output
 */
require_once "header.php";
echo $output["NAVIGATION"];
echo $output["CONTENT"];
require_once "footer.php";
$APP->closeDbConnect();
