<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap -->
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/styles.css" rel="stylesheet" media="screen">

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/html5shiv.js"></script>
          <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <?php if (isset(Yii::app()->params['GACode'])) echo Yii::app()->params['GACode']; ?>
        <div class="container maincontainer">
            <nav class="navbar navbar-default top-nav-bar navbar-fixed-top" role="navigation">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        
                        <form class="navbar-form navbar-left visible-xs pull-right top-search-form" role="search">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Search">
                            </div>
                            <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
                        </form>
                        <a class="navbar-brand hidden-xs" href="/"><span class="text-info">Open</span><span class="text-success">Web</span><span class="text-danger">Rating</span></a>
                        <a class="navbar-brand visible-xs" style="padding-left:5px;padding-right:0px;" href="/"><span class="text-info">Open</span><span class="text-success">Web</span><span class="text-danger">Rating</span></a>
                    </div>

                    <div class="collapse navbar-collapse navbar-ex1-collapse">
                        <?= Yii::app()->helper->mainMenu(); ?>
                        <form class="navbar-form navbar-right hidden-md hidden-sm" role="search">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Search">
                            </div>
                            <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
                        </form>
                        <?
//                        <ul class="nav navbar-nav navbar-right">
//                            <!--li><a href="#">Login</a></li>
//                                <li><a href="#">Register</a></li-->
//                            <li><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span><b class="caret"></b></a>
//                                <ul class="dropdown-menu">
//                                    <li><a href="#">Login</a></li>
//                                    <li><a href="#">Register</a></li>
//                                </ul>
//                            </li>
//                        </ul>
                       ?>
                    </div><!-- /.navbar-collapse -->
                </div>
            </nav>
                <div class="page-inner">
                    <div class="page-unique">
                        <?php echo $content; ?>
                    </div>
                </div>
            <nav class="navbar navbar-default navbar-fixed-bottom" role="navigation">
                <div class="container">
                    <a class="navbar-brand" href="/"><?php echo date('Y'); ?> PopularOnWeb   All Rights Reserved</a>
                </div>
            </nav>
        </div>



        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery-1.10.2.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap.min.js"></script>
    </body>
</html>