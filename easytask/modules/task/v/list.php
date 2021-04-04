<?global $arParams;?>



<?if(!empty($arParams["ITEMS"])):?>
    <?
    foreach ($arParams["ITEMS"] as $arItem):
        switch ($arItem["STATUS"]){
            case "C":
                $arItem["STATUS"] = "success";
                $arItem["STATUS_TITLE"] = "выполнено";
                break;
            case "M":
                $arItem["STATUS"] = "warning";
                $arItem["STATUS_TITLE"] = "отредактировано администратором";
                break;
            case "W":
                $arItem["STATUS"] = "warning";
                $arItem["STATUS_TITLE"] = "выполнено и отредактировано администратором";
                break;
            default:
                $arItem["STATUS"] = "primary";
                $arItem["STATUS_TITLE"] = "ждёт выполнения";
        }
    ?>
        <div class="card border-<?=$arItem["STATUS"]?> mb-3">
            <h5 class="card-header bg-<?=$arItem["STATUS"]?> text-white"><?=$arItem["STATUS_TITLE"]?></h5>
            <div class="card-body text-<?=$arItem["STATUS"]?>">
                <h5 class="card-title">
                    Имя:<?=$arItem["NAME"]?><br>
                    E-mail:<?=$arItem["EMAIL"]?></h5>
                <p class="card-text">
                    <?
                    $arItem["TEXT"] = explode("\n", $arItem["TEXT"]);
                    for ($i = 0; $i < count ($arItem["TEXT"]); $i++)
                        echo $arItem["TEXT"][$i] . '<br />';
                    ?>
                </p>
            </div>
            <?if($arParams["IS_ADMIN"] === "Y"):?>
            <div class="card-footer">
                <?if($arParams["IS_ADMIN"] == "Y"):?>
                    <div class="btn btn-success" onclick="complete(<?=$arItem["ID"]?>)">Выполнено</div>
                <?endif;?>
                <a href="/?action=edit&id=<?=$arItem["ID"]?>" class="btn btn-<?=$arItem["STATUS"]?>">Изменить</a>
            </div>
            <?endif;?>
        </div>

    <?endforeach;?>
<?endif;?>

<?if(count($arParams["PAGINATION"]["pages"]) > 1):?>
<div class="row justify-content-md-center">
    <div class="col-auto">
        <nav>
            <ul class="pagination">
                <?foreach ($arParams["PAGINATION"]["pages"] as $page):?>
                    <?if($arParams["PAGINATION"]["page"] == $page):?>
                        <li class="page-item active" aria-current="page">
                            <span class="page-link"><?=$page?></span>
                        </li>
                    <?else:?>
                        <li class="page-item"><a class="page-link" href="?page=<?=$page?>"><?=$page?></a></li>
                    <?endif;?>
                <?endforeach;?>
            </ul>
        </nav>
    </div>
</div>
<?endif;?>
