<?
/**
 * @var Main $APP
 * @var User $USER
 */
use Beegee\Tasks\Main,
    Beegee\Tasks\User,
    Beegee\Tasks\Task;

global $output, $arParams, $USER;
$GLOBALS["USER"] = User::getInstance();
session_start();

if($_SESSION["USER"]["AUTHORIZED"] === "Y")
    define("AUTHORIZED", "Y");

$arParams["SORT"] = [
    "STATUS" => [
        "TITLE" => "Сортировать по статусу"
    ],
    "NAME" => [
        "TITLE" => "Сортировать по имени"
    ],
    "EMAIL"  => [
        "TITLE" => "Сортировать по E-mail"
    ]
];

// sort=NAME&order=asc
if($_REQUEST["sort"]){
    $_SESSION["sort"] = $_REQUEST["sort"];
    $_SESSION["order"] = $_REQUEST["order"];
}

switch ($_REQUEST["action"]){
    case "login":

        if($_POST){
            if($USER->authorizeByLogin($_POST["login"], $_POST["password"]) == "Y"){
                $_SESSION["USER"] = [
                    "AUTHORIZED" => "Y"
                ];
                if(!defined("AUTHORIZED"))
                    define("AUTHORIZED", "Y");
                header("Location: /", true, 301);
            }
            else{
                $arParams["AUTH_ERRORS"] = "Неверный логин или пароль";
            }
        }

        break;
    case "logout":
        unset($_SESSION["USER"]);
        break;
}


foreach ($arParams["SORT"] as $code=>$value){
    if($_SESSION["sort"] == $code){
        if($_SESSION["order"] == "asc"){
            $arParams["SORT"][$code]["ORDER"] = "desc";
        }else{
            $arParams["SORT"][$code]["ORDER"] = "asc";
        }
    }

}

if(defined("AUTHORIZED") && AUTHORIZED === "Y" && $_REQUEST["action"] !== "logout"){
    $output["NAVIGATION"] = $APP->LoadView("user", "nav.admin.php");
}
else{
    $output["NAVIGATION"] = $APP->LoadView("user", "nav.guest.php");
    switch ($_REQUEST["action"]){
        case "login":
        case "logout":
        $output["CONTENT"] = $APP->LoadView("user", "auth.php");
            break;
    }
}