<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <meta name="description" content="" />
    <meta name="keyword" content="" />
    <!-- Bootstrap -->
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap.css" rel="stylesheet" media="screen">
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap-responsive.css" rel="stylesheet" media="screen">
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/styles.css" rel="stylesheet" media="screen">
  </head>
  <body>
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid topcontainer fixcontainer">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <div class="topsearchform pull-right hidden-desktop">
            <form class="form-search" style="margin: 0px;">
              <div class="control-group">
                  <div class="input-append">
                      <input type="text" class="input-medium search-query" />
                      <button type="submit"></button>
                      <span class="add-on"><i class="icon-search search-query"></i></span>
                  </div>
              </div>
            </form>
          </div><!--.topsearchform-->
          <div id="brand_name_container">
            <a href="/" id="brand_name" class="brand"><span class="text-info">Open</span><span class="text-success">Web</span><span class="text-error">Rating</span></a>
          </div><!--#brand_name_container-->
          <div class="collapse nav-collapse">
            <p class="navbar-text pull-right">
              <a href="#loginform" role="button" data-toggle="modal" class="navbar-link">Login</a> | <a href="#" class="navbar-link">Register</a>
            </p>
            <div class="topsearchform pull-right visible-desktop">
              <form class="form-search" style="margin: 0px;">
                <div class="control-group">
                    <div class="input-append">
                        <input type="text" class="input-medium search-query" />
                        <button type="submit"></button>
                        <span class="add-on"><i class="icon-search search-query"></i></span>
                    </div>
                </div>
              </form>
            </div><!--.topsearchform-->
                <?=Yii::app()->helper->mainMenu();?>
              <!--.nav-->
          </div>
        </div>
      </div><!--navbar-inner-->
    </div><!--.navbar-->
    <div class="container-fluid fixcontainer">
        <?php echo $content; ?>
      <div class="clear-fix"></div>
    </div>
    <div class="navbar navbar-inverse navbar-fixed-bottom">
      <div class="navbar-inner">
        <div class="fixcontainer">
          <span class="brand" style="font-size: 10pt;"><?php echo date('Y'); ?> PopularOnWeb   All Rights Reserved</span>
        </div>
      </div>
    </div>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery-1.10.2.min.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap.min.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/scripts.js"></script>
  </body>
</html>