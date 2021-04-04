<?global $arParams?>
<ul class="nav justify-content-end">
    <li class="nav-item">
        <a class="nav-link active" href="/">Список задач</a>
    </li>

    <?foreach ($arParams["SORT"] as $code=>$value):?>
        <li class="nav-item">
            <a class="nav-link active" href="/?sort=<?=$code?>&order=asc">
                <?=$value["TITLE"]?>

                <?if($value["ORDER"] == "desc"):?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"/>
                    </svg>
                <?else:?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
                    </svg>
                <?endif;?>
            </a>
        </li>
    <?endforeach;?>

    <li class="nav-item">
        <a class="nav-link active" href="/?action=edit">Добавить задачу</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/?action=login">Войти</a>
    </li>
</ul>