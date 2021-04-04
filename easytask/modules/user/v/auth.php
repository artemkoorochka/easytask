<?global $arParams;?>

<div class="row justify-content-md-center">
    <div class="col-auto">
        <form class="card border-primary my-5"
              method="post"
              action="/?action=login">

            <h5 class="card-header bg-primary text-white">Авторизация</h5>

            <div class="card-body text-primary">

                <input type="hidden" name="action" value="login">

                <?if(isset($arParams["AUTH_ERRORS"])):?>
                    <div class="alert alert-danger"><?=$arParams["AUTH_ERRORS"]?></div>
                <?endif;?>

                <div class="form-group">
                    <label for="user-login">Логин:</label>
                    <input type="text"
                           class="<?=$arParams["AUTH_ERRORS"]? "form-control is-invalid":"form-control"?>"
                           id="user-login"
                           name="login"
                           aria-describedby="user-loginHelp"
                           placeholder="Введите логин"
                           required />
                    <small id="user-loginHelp" class="form-text text-muted">admin</small>
                </div>

                <div class="form-group">
                    <label for="user-password">Пароль:</label>
                    <input type="password"
                           class="<?=$arParams["AUTH_ERRORS"]? "form-control is-invalid":"form-control"?>"
                           id="user-password"
                           name="password"
                           placeholder="Введите пароль"
                           required
                           value="" />
                </div>

            </div>

            <div class="card-footer text-right">
                <input type="submit" class="btn btn-primary" value="Отправить">
            </div>

        </form>
    </div>
</div>
