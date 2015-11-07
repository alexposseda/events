
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= $this->page_data['title']?></title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/css/style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/main">Events</a>
        </div>
    </div>
</div>

<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="jumbotron">
    <div class="container">
        <h1>Привет, мир!</h1>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-9">
            <?php if(Url::getUrlSegment(0) == 'personal'):?>
                <ul class="nav nav-tabs">
                    <li class="active"><a href="/personal">Мои встречи</a></li>
                    <li><a href="/personal/management">Управление встречами</a></li>
                </ul>
            <?php endif;?>
            <?php include_once 'templates/'.$tpl.'.tpl'?>
        </div>
        <!--sidebar -->
        <div class="col-md-3">
            <?php if(!Auth::checkAuth()):?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Вход</h3>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" action="/main/signin" method="POST">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="email" placeholder="Email" class="form-control" name="email">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="password" placeholder="Password" class="form-control" name="password">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success col-md-12">Sign in</button>
                        </form>

                    </div>
                    <div class="panel-footer">
                        <a href="/main/registration" class="btn btn-default col-md-12">Регистрация</a>
                        <div class="clearfix"></div>
                    </div>
                </div>
            <?php else:?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><a href="/personal"><?= $this->content['user_data']['login']?></a></h3>
                    </div>
                    <div class="panel-body">
                        <img src="/img/noavatar.png" alt="..." class="avatar">
                        <ul class="list-group">
                            <li class="list-group-item"><a href="/add/event">Создать встречу</a> </li>
                            <li class="list-group-item"><a href="/personal">Мои встречи</a></li>
                            <li class="list-group-item"><a href="/personal/management">Управление встречами</a></li>
                        </ul>
                    </div>
                    <div class="panel-footer">
                        <a href="/main/logout" class="btn btn-danger col-md-12">Выход</a>
                        <div class="clearfix"></div>
                    </div>
                </div>
            <?php endif;?>
        </div>
        <!-- end sidebar -->
    </div>
</div> <!-- /container -->
<div id="footer">
    <div class="container">
        <hr>
        <p>&copy; AlexPosseda 2015</p>
    </div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="/js/bootstrap.js"></script>
</body>
</html>