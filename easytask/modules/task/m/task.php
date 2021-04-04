<?
namespace Beegee\Tasks\Task;

class Task{

    private static $_instance;
    private static $_paginatia = [
        "size" => 3,
        "from" => 0,
        "to" => 3,
        "page" => 1
    ];

    public static function getTableName()
    {
        return "beegee_task";
    }

    public static function getInstance()
    {

        if (!isset(self::$_instance)) {
            self::$_instance = new static();
        }

        return self::$_instance;
    }

    public function getMap(){
        return [
            "STATUS" => [
                "code" => "STATUS",
                "title" => "Статус задачи",
                "type" => "list",
                "values" => [
                    "C" => "Создан",
                    "E" => "Отредактирован"
                ]
            ],
            "NAME" => [
                "code" => "NAME",
                "title" => "Имя пользователя",
                "type" => "string",
                "validate" => "string"
            ],
            "EMAIL" => [
                "code" => "EMAIL",
                "title" => "Email пользователя",
                "type" => "string",
                "validate" => "email"
            ],
            "TEXT" => [
                "code" => "TEXT",
                "title" => "Текст задачи",
                "type" => "text"
            ]
        ];
    }

    public function getList($params){
        global $APP;

        $query = [
            "select",
            "*",
            "from",
            self::getTableName()
        ];

        if(!empty($params["order"])){
            foreach ($params["order"] as $by=>$order){
                $query[] = "ORDER BY";
                $query[] = "`" . $by . "`";
                $query[] = $order === "desc" ? "desc" : "asc";
            }
        }

        if(!empty($params["filter"])){
            $query[] = "WHERE";
            $i = 0;
            foreach ($params["filter"] as $by=>$order){
                if($i > 0)
                    $query[] = "AND";
                $i++;
                $query[] = "`" . $by . "`";
                $query[] = "=";
                $query[] = $order;
            }
        }

        if($params["page"] > 1)
        {
            self::$_paginatia["page"] = intval($params["page"]);
            self::$_paginatia["to"] = self::$_paginatia["page"] * self::$_paginatia["size"];
            self::$_paginatia["from"] = self::$_paginatia["to"] - self::$_paginatia["size"];
        }

        $query[] = "LIMIT";
        $query[] = self::$_paginatia["from"] . "," . self::$_paginatia["size"];

        $query = implode(" ", $query);

        $arItems = [];
        if($result = $APP->getDBLink()->query($query)){

            while($obj = $result->fetch_assoc()){
                $arItems[] = $obj;
            }

            mysqli_free_result($result);
        }

        return $arItems;
    }

    public function getPagination(){
        global $APP;

        $result = [
            "count" => 0
        ];

        $query = [
            "select",
            "*",
            "from",
            self::getTableName()
        ];

        $query = implode(" ", $query);

        if($result = $APP->getDBLink()->query($query)){

            $result = [
                "count" => $result->num_rows,
                "page" => self::$_paginatia["page"],
                "from" => self::$_paginatia["from"],
                "to" => self::$_paginatia["to"],
                "pages" => []
            ];

            if($result["count"] > self::$_paginatia["size"]){
                $i = 0;
                $page = 0;
                while ($i < $result["count"]){
                    $i += self::$_paginatia["size"];
                    $page++;
                    $result["pages"][] = $page;
                }
            }
        }

        return $result;
    }

    public function validate($arParams){
        $result = [
            "status" => "error",
            "message" =>[]
        ];

        global $APP;

        foreach (self::getMap() as $value){
            if($value["code"] === "STATUS")
                continue;

            if($value["code"] === "EMAIL"){
                if(!$APP->check_email($arParams[$value["code"]])){
                    $result["message"]["EMAIL"] = "Введен некоректный адресс электронной почты";
                }
            }

            if(empty($arParams[$value["code"]])){
                $result["message"][$value["code"]] = "Не заполнено обязательное поле";
            }


        }

        if(empty($result["message"]))
            $result["status"] = "success";

        return $result;
    }

    public function add($arParams){
        global $APP;
        $fields = ["`ID`"];
        $values = ["NULL"];

        foreach (self::getMap() as $value){
            $fields[] = "`" . $value["code"] . "`";
            if($value["code"] === "STATUS")
                $values[] = "'G'";
            else
                $values[] = "'" . trim(htmlentities(stripslashes($arParams[$value["code"]]))) . "'";
        }

        $query = [
            "INSERT",
            "INTO",
            "`" . self::getTableName() . "`",
            "(" . implode(", ", $fields) . ")",
            "VALUES",
            "(" . implode(", ", $values) . ")",
        ];

        $query = implode(" ", $query);

        if($result = $APP->getDBLink()->query($query))
            return true;
        else
            return false;
    }

    public function update($arParams){
        global $APP;

        $arParams["id"] = intval($arParams["id"]);

        if($arParams["id"] > 0){
            $values = [];

            $task = $this->getList(["filter" => ["ID" => $arParams["id"]]]);
            $task = current($task);

            // status
            if(!empty($arParams["TEXT"])){
                if(!$this->checkText($arParams["id"], $arParams["TEXT"], $task["TEXT"])){
                    if($task["STATUS"] == "C"){
                        $arParams["STATUS"] = "W";
                    }else{
                        $arParams["STATUS"] = "M";
                    }
                }
            }

            foreach (self::getMap() as $value){
                if(empty($arParams[$value["code"]]))
                    continue;
                $values[] = "`" .$value["code"] . "` = '" . trim(htmlentities(stripslashes($arParams[$value["code"]]))) . "'";

            }

            $query = [
                "UPDATE",
                "`" . self::getTableName() . "`",
                "SET",
                implode(", ", $values),
                "WHERE",
                "`ID` = " . $arParams["id"]
            ];
            $query = implode(" ", $query);

            if($result = $APP->getDBLink()->query($query))
                return true;
            else
                return false;
        }

    }

    public function checkText($id, $text, $taskText){
        $taskText = trim(htmlentities(stripslashes($taskText)));
        if($taskText === $text)
            return true;
        else
            return false;
    }

}