<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= $this->page_data['title']?></title>
    <link rel="stylesheet" href="/css/reset.css">
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="/js/my.js"></script>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <?php if(!$this->getAuth()->getAuthStatus()):?>
            <div class="login-box">
                <form action="/main/login" method="POST">
                    <div class="form-line">
                        <input type="email" placeholder="example@mail.com" name="email">
                    </div>
                    <div class="form-line">
                        <input type="password" placeholder="password" name="password">
                    </div>
                    <button type="submit">Войти</button>
                </form>
                <a href="/main/registration">Регистрация</a>
            </div>
        <?php else:?>
            <div class="user-box">
                <p><?= $this->getUser()->getUserLogin();?></p>
                <a href="/personal">Мои встречи</a>
                <a href="/main/logout">Выход</a>
            </div>
        <?php endif;?>
        <h1><a href="/">Events! Here!</a></h1>
        <ul class="pmenu">
            <li><a href="/">Встречи</a></li>
        </ul>
    </div>
    <div class="main">
        <?php include 'layouts/templates/'.$tpl.'.tpl'?>
    </div>
    <div class="sidebar">
        <?php if($this->getUrl()->getUrlSegment(0) == 'personal' and $this->getAuth()->getAuthStatus()):?>
        <form>
            <div class="form-line">
                <label for="all_event">все мои встречи</label>
                <input type="checkbox" name="all_event" id="all_event" <?= (isset($_GET['all_event'])) ? 'checked' : '' ;?>>
            </div>
            <div class="form-line">
                <label for="my_event">созданные мной встречи</label>
                <input type="checkbox" name="my_event" id="my_event" <?= (isset($_GET['my_event'])) ? 'checked' : '' ;?>>
            </div>
            <button type="submit">Применить</button>
        </form>
            <a href="/event/add" class="button more">Создать встречу</a>
        <?php endif;?>
    </div>
    <div class="footer"></div>
</div>
</body>
</html>