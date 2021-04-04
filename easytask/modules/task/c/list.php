<?
use Beegee\Tasks\Task\Task;

if(empty($_REQUEST["action"])){

    global $output, $USER, $arParams;

    if($_REQUEST["page"] > 1){
        $arParams["page"] = $_REQUEST["page"];
    }

    if(!empty($_SESSION["sort"])){
        $arParams["order"] = [
            $_SESSION["sort"] => $_SESSION["order"]
        ];
    }

    $arParams = [
        "SORT" => [],
        "ITEMS" => Task::getInstance()->getList($arParams),
        "PAGINATION" => Task::getInstance()->getPagination(),
        "IS_ADMIN" => $USER->isAdmin() ? "Y" : "N"
    ];

    $output["CONTENT"] = $APP->LoadView("task", "list.php");
}
