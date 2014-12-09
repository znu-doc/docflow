<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="en" />
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" rel="shortcut icon" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/datepicker.css" />
    <!--link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap-timepicker.min.css" /-->

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <?php Yii::app()->bootstrap->register(); ?>
    <?php Yii::app()->clientScript->registerPackage('bootstrap-switch'); ?>
    <?php Yii::app()->clientScript->registerPackage('select2'); ?>


    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap-combobox.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/styles.css" />

    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap-typeahead.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap-combobox.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap-datepicker.js"></script>
    <!--script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap-timepicker.min.js"></script-->
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.notification.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/doc.js"></script>

    <!--script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/spin.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/blockUI.js"></script-->
  </head>
  <body 
  <?php
  /* if (!Yii::app()->user->CheckAccess('showProperties') && !Monitoringanswers::model()->find('UserID='.Yii::app()->user->id) 
    && $_SERVER['REQUEST_URI'] != '/docflow/site/anketa'){
    ?>
    onload='DOC.AnnoyingBlank("<?php echo Yii::app()->CreateUrl('site/AnketaModal'); ?>");'
    <?php
    } */
  ?>
    >
<div id="content">

      <?php
      $this->menu = array(
         array('label' => 'Головна',
            'url' => Yii::app()->CreateUrl('/site/index'),
            'icon' => 'icon-home',
            'itemOptions'=> array('class'=> 'item120','title'=>'Головна')),
         array('label' => 'Документи',
            'url' => "#", "icon" => "icon-file",
            'items' => array(
               array('label' => 'Список документів', 'url' => Yii::app()->createUrl("/documents/index"),
                  "icon" => " icon-list",),
               array('label' => 'Створити документ', 'url' => Yii::app()->createUrl("/documents/createnew"),
                  "icon" => "icon-plus",),
            ),
           'itemOptions'=> array('class'=> 'item120'),
           'visible' => Yii::app()->user->checkAccess('asOperatorStart'),
         ),
         array('label' => 'Документообіг',
            'url' => "#", "icon" => "icon-briefcase",
            'items' => array(
               array('label' => 'Вхідні розсилки', 
                   'visible' => !Yii::app()->user->checkAccess('showProperties'),
                   'url' => Yii::app()->createUrl("/docflows/index?mode=in"),
                  "icon" => " icon-arrow-down",),
               array('label' => 'Ініційовані розсилки', 
                   'visible' => !Yii::app()->user->checkAccess('showProperties'),
                   'url' => Yii::app()->createUrl("/docflows/index?mode=from"),
                  "icon" => "icon-arrow-up",),
               array('label' => 'Усі розсилки', 
                   'visible' => Yii::app()->user->checkAccess('showProperties'),
                   'url' => Yii::app()->createUrl("/docflows/index?mode=admin"),
                  "icon" => "icon-eye-open",),
               array('label' => 'Ініціювати', 'url' => Yii::app()->createUrl("/docflows/createflow"),
                  "icon" => "icon-star",),
            ),
           'itemOptions'=> array('class'=> 'item120'),
           'visible' => Yii::app()->user->checkAccess('asOperatorStart'),
         ),
         array('label' => 'Сервіс',
            'url' => "#", "icon" => "icon-book",
            'items' => array(
              array('label' => 'Інструкції', 'url' => Yii::app()->CreateUrl("/docflowinfo"),
                 "icon" => "icon-info-sign", 'visible' => true,
                 'itemOptions'=> array('title'=>'Інструкції можливостей ПЗ')),
              array('label' => 'Довідники', 'visible' => 
                  Yii::app()->user->checkAccess('showDirectiries') 
                  || Yii::app()->user->checkAccess('asEvent')
                  || Yii::app()->user->checkAccess('asOffice'),
                 'url' => '#', "icon" => "icon-book", 'items' => Directories::listMenu(),
                'itemOptions'=> array()),
              array('label' => 'Розробникам', 'visible' => Yii::app()->user->checkAccess('asOperatorStart'),
                 'url' => 'http://help.znu.edu.ua', "icon" => "icon-bullhorn",
                 'itemOptions'=> array('title'=>'Скарги та пропозиції')),
              array('label' => 'Контакти', 'url' => array('/site/contact'),
                 "icon" => "icon-envelope", 'visible' => true,
                 'itemOptions'=> array('title'=>'Контактні дані')),
              array('label' => 'Чат', 'url' => array('/site/chat'),
                 "icon" => "icon-envelope", 'visible' => true,
                 'itemOptions'=> array('title'=>'Чат (тестовий)')),
              array('label' => 'Налаштування', 'visible' => Yii::app()->user->checkAccess('showProperties'), 
                  'url' => "#", "icon" => "icon-wrench",
                  'items' => array(
                      array('label' => 'Користувачі', 'url' => Yii::app()->createUrl("user"), 
                          "icon" => " icon-user",),
                      array('label' => 'Групи користувачів', 'url' => Yii::app()->createUrl("srbac"), 
                          "icon" => "icon-lock",),
                      array('label' => 'Керування довідниками', 'url' => Yii::app()->createUrl("directories"), 
                          "icon" => "icon-pencil",),
                  )
              ),

            ),
           'itemOptions'=> array('class'=> 'item120'),
         ),
        array('label' => 'Календар', 'visible' => Yii::app()->user->checkAccess('asEvent'), 
            'url' => "#", "icon" => "icon-calendar",
            'items' => array(
                array('label' => 'Перегляд заходів', 'url' => Yii::app()->createUrl("events"), 
                    "icon" => " icon-eye-open",),
                array('label' => 'Новий захід', 'url' => Yii::app()->createUrl("events/create"), 
                    "icon" => "icon-plus",),
            )
        ),
         array('label' => 'Користувач' . ((Yii::app()->user->checkAccess('asOperatorStart'))? ' ('.Yii::app()->user->name.')':''),
            'url' => '#',
            'icon' => 'icon-user',
            'items' => array(
               array('label' => 'Дані користувача', 'url' => Yii::app()->CreateUrl('/user/ownprofile'),
                  "icon" => " icon-cog",),
               array('label' => 'Вихід', 'url' => Yii::app()->createUrl("/site/logout"),
                  "icon" => "icon-off",),
            ),
            'itemOptions' => array(
              'class' => 'pull-right item120'
            ),
           'visible' => Yii::app()->user->checkAccess('asOperatorStart'),
         )
      );
      $smallmenu = array();
      $j = 1;
      foreach ($this->menu as $item){
        $_item = $item;
        $_item['label'] = '';
        $_item['itemOptions']['class'] = '';
        $smallmenu [$j++] = $_item;
      }
      //var_dump(Yii::app()->session->SessionID);exit();

      ?>
    <div class="container" id="page">
        <?php
        $this->widget('bootstrap.widgets.TbMenu', array(
           'items' => $this->menu,
           'type' => 'tabs',
           'id' => 'menu_general',
        ));
        ?>
        <?php
        $this->widget('bootstrap.widgets.TbMenu', array(
           'items' => $smallmenu,
           'type' => 'list',
           'id' => 'smallmenu_general',
           'htmlOptions' => array(
             'style' => 'display: '.(((isset(Yii::app()->session['hide_smallmenu'])))? 'none':'block').';'
           ),
        ));
        ?>
      <div class="alert in alert-block fade alert-warning" 
           style="display:none;" id="warn-browser">
        <!--a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>Повідомлення від розробників : </strong> 
        Для стабільної роботи документообігу рекомендується 
        використовувати браузер Google Chrome останніх версій.
        <a href="http://www.google.com.ua/intl/ru/chrome/">
          <img src = '/docflow/images/Internet-chrome-icon.gif' 
               style="width: 50px; height: 50px;"/>
        </a-->
      </div>

    

        <?php echo $content; ?>

    

      <hr/>
      <footer>
        <div class="row-fluid">
          <div class="span12" style="text-align: center; background-color: rgba(255,255,255,0.6); border-radius: 5px;">
          © Лабораторія інформаційних систем та комп'ютерних технологій ЗНУ <br/>2013,<?php echo date("Y") ?>
          </div>
        </div>
      </footer>
    </div><!-- page -->
    <!-- Для модальних вікон -->
    <div id="AnnoyingBlank-modal-holder"></div>
</div><!-- content -->
    <script type="text/javascript">
      <?php if (Yii::app()->user->checkAccess('asOperatorStart') 
        //&& !($this->action->Id == 'index' && $this->uniqueid == 'docflows')
        ){ ?>
        DOC.NotificationCheckWithPeriod("<?php echo Yii::app()->CreateUrl("/site/checknotification"); ?>", 
          1000 * 60 * 15);
      <?php } ?>
    </script>
  </body>
</html>
