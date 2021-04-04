<?
use Beegee\Tasks\Task\Task;

if($_REQUEST["action"] === "edit"){
    global $APP, $USER, $output, $arParams;

    $arParams = [
        "FIELDS" => Task::getInstance()->getMap(),
        "ERRORS" => [],
        "IS_ADMIN" => $USER->isAdmin() ? "Y" : "N"
    ];
    if($_REQUEST["id"] > 0 && $arParams["IS_ADMIN"] === "Y"){
        $arParams["ID"] = intval($_REQUEST["id"]);

        if($_REQUEST["complete"] === "Y"){
            Task::getInstance()->update(["id" => $arParams["ID"], "STATUS" => "C"]);
            die;
        }


        $arParams["VALUES"] = Task::getInstance()->getList(["filter" => ["ID" => $arParams["ID"]]]);
        $arParams["VALUES"] = current($arParams["VALUES"]);
    }
    unset($arParams["FIELDS"]["STATUS"]);

    if($_POST["action"] === "edit"){

        foreach ($_POST as $code=>$value){
            $arParams["VALUES"][$code] = htmlspecialchars($value);
        }

        $arParams["VALIDATION"] = Task::getInstance()->validate($arParams["VALUES"]);
        if($arParams["VALIDATION"]["status"] === "success"){
            if($arParams["ID"] < 1){
                unset($arParams["VALUES"]);
            }

            if($arParams["IS_ADMIN"] !== "Y"){
                Task::getInstance()->add($_POST);
                $arParams["VALIDATION"]["status"] = "add";
            }else{
                if($arParams["ID"] > 0){
                    Task::getInstance()->update($_POST);
                    $arParams["VALIDATION"]["status"] = "update";
                }else{
                    Task::getInstance()->add($_POST);
                    $arParams["VALIDATION"]["status"] = "add";
                }
            }
        }

    }

    $output["CONTENT"] = $APP->LoadView("task", "edit.php");

}
