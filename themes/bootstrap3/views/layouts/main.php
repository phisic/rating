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
    <div class="navbar navbar-inverse navbar-fixed-top">
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
            <a href="/" rel="popover" id="brand_name" class="brand">PopularOnWeb <span class="caret hidden-phone hidden-tablet"></span></a>
            <div id="brand_name_content" style="display: none;">
              <div class="span3 category_list">
                <ul>
                  <li><a href="#" data-target="#category_item1">Cat1</a></li>
                  <li><a href="#" data-target="#category_item2">Cat2</a></li>
                  <li><a href="#" data-target="#category_item3">Cat3</a></li>
                  <li><a href="#" data-target="#category_item4">Cat4</a></li>
                  <li><a href="#" data-target="#category_item5">Cat5</a></li>
                </ul>
              </div><!--.category_list-->
              <div class="span5 category_items">
                <div id="category_item1" class="hide">test111</div>
                <div id="category_item2" class="hide">test222</div>
                <div id="category_item3" class="hide">test333</div>
                <div id="category_item4" class="hide">test444</div>
                <div id="category_item5" class="hide">test555</div>
                <div>Choose category</div>
              </div><!--.category_items-->
              <div class="clearfix"></div>
            </div><!--#brand_name_content-->
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
            <ul class="nav">
              <li><a href="#">Home</a></li>
              <li><a href="#">Link1</a></li>
              <li><a href="#">Link2</a></li>
              <li><a href="#">Link3</a></li>
              <li class="hidden-desktop"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Category List</a>
                <ul class="dropdown-menu">
                  <li><a tabindex="-1" href="#">Cat1</a></li>
                  <li><a tabindex="-1" href="#">Another action</a></li>
                  <li><a tabindex="-1" href="#">Something else here</a></li>
                  <li class="divider"></li>
                  <li><a tabindex="-1" href="#">Separated link</a></li>
                </ul>
              </li>
            </ul><!--.nav-->
          </div>
        </div>
      </div><!--navbar-inner-->
    </div><!--.navbar-->
    <div class="container-fluid fixcontainer">
      <h1>Testinggg</h1>
      <ul class="thumbnails">
        <li class="span3">
          <div class="thumbnail">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/noimage.jpeg" />
            <h4>Test</h4>
            <p>Testing...</p>
          </div>
        </li>
        <li class="span3">
          <div class="thumbnail">
            <a href="#"></a><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/noimage.jpeg" /></a>
            <h4>Test <span class="label label-info">123</span></h4>
            <p>Testing...</p>
          </div>
        </li>
        <li class="span3">
          <div class="thumbnail">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/noimage.jpeg" />
            <h4>Test</h4>
            <p>Testing...</p>
          </div>
        </li>
      </ul><!--.thumbnails-->
      <div class="pagination pagination-centered">
        <ul>
          <li class="disabled"><a href="#">&laquo;</a></li>
          <li class="active"><a href="#">1</a></li>
          <li><a href="#">2</a></li>
          <li><a href="#">3</a></li>
          <li><a href="#">4</a></li>
          <li><span>...</span></li>
          <li><a href="#">20</a></li>
          <li><a href="#">&raquo;</a></li>
        </ul>
      </div>
      <div class="clear-fix"></div>
    </div>
    <div class="navbar navbar-inverse navbar-fixed-bottom">
      <div class="navbar-inner">
        <div class="fixcontainer">
          <span class="brand" style="font-size: 10pt;"><?php echo date('Y'); ?> PopularOnWeb   All Rights Reserved</span>
        </div>
      </div>
    </div>

    <div id="loginform" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="loginFormLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <div id="loginFormLabel"><h4>Authorization</h4></div>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id="authorizationform">
          <div class="control-group">
            <label class="control-label" for="inputLogin">Login</label>
            <div class="controls">
              <input type="text" id="inputLogin" placeholder="Login" />
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="inputPassword">Password</label>
            <div class="controls">
              <input type="password" id="inputPassword" placeholder="Password" />
            </div>
          </div>
          <input type="hidden" name="authorizationButton">
        </form>
      </div><!--.modal-body-->
      <div class="modal-footer">
        <div class="pull-left">
          <a href="#">Forgot your password?</a>
        </div>
        <button class="btn btn-primary" onclick="$('#authorizationform').submit(); return false;">Login</button>
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
      </div><!--.modal-footer-->
    </div><!--#loginform-->

    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery-1.10.2.min.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap.min.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/scripts.js"></script>
  </body>
</html>