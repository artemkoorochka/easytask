<?
global $arParams;
switch ($arParams["VALIDATION"]["status"]){
    case "add":
            ?><div class="alert alert-success">Задача успешно создана</div><?
        break;
    case "update":
            ?><div class="alert alert-success">Задача успешно отредактирована</div><?
        break;
}
?>


<form class="card border-primary my-4"
      method="post"
      action="">

    <h5 class="card-header bg-primary text-white">Редактирование задачи</h5>

    <div class="card-body text-primary">

        <input type="hidden" name="action" value="edit">
        <?if($arParams["ID"] > 0):?>
            <input type="hidden" name="id" value="<?=$arParams["ID"]?>">
        <?endif;?>
        <?foreach ($arParams["FIELDS"] as $code=>$param):?>
        <div class="form-group row">
            <label for="task-<?=$code?>" class="col-md-4 col-lg-6 col-form-label text-right"><?=$param["title"]?>: </label>
            <div class="col-md-8 col-lg-6">
            <?
            switch ($param["type"]){
                case "text":
                    ?><textarea name="<?=$code?>"
                                class="form-control h-200<?
                                if($arParams["VALIDATION"]["status"] == "error"){
                                    if(empty($arParams["VALIDATION"]["message"][$param["code"]])){
                                        echo " is-valid";
                                    }else{
                                        echo " is-invalid";
                                    }
                                }
                                ?>"
                                id="task-<?=$code?>"
                                placeholder="Введите текст"><?=$arParams["VALUES"][$param["code"]]?></textarea><?
                    break;
                default:
                    ?><input type="text"
                             name="<?=$code?>"
                             class="form-control<?
                             if($arParams["VALIDATION"]["status"] == "error"){
                                 if(empty($arParams["VALIDATION"]["message"][$param["code"]])){
                                     echo " is-valid";
                                 }else{
                                     echo " is-invalid";
                                 }
                             }
                             ?>"
                             id="task-<?=$code?>"
                             placeholder="Введите текст"
                             value="<?=$arParams["VALUES"][$param["code"]]?>" />
                    <?
                    if($arParams["VALIDATION"]["status"] == "error"){
                        if(!empty($arParams["VALIDATION"]["message"][$param["code"]])){
                            ?><small id="user-loginHelp" class="form-text text-danger"><?=$arParams["VALIDATION"]["message"][$param["code"]]?></small><?
                        }
                    }
            }
            ?>
            </div>
        </div>
        <?endforeach;?>

    </div>

    <div class="card-footer text-right">
        <?if($arParams["IS_ADMIN"] == "Y"):?>
            <div class="btn btn-success" onclick="complete(<?=$arParams["ID"]?>)">Выполнено</div>
        <?endif;?>
        <input type="submit" class="btn btn-primary" value="Сохранить">
    </div>

</form>
