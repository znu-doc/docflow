<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
Yii::setPathOfAlias('bootstrap', dirname(__FILE__) . '/../extensions/bootstrap');
Yii::setPathOfAlias('editable', dirname(__FILE__).'/../extensions/x-editable');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
   'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
   'name' => 'Документообіг',
   'sourceLanguage' => 'uk',
   'language' => 'uk',
   // preloading 'log' component
   'preload' => array('log'),
   // autoloading model and component classes
   'import' => array(
      'application.models.*',
      'application.controllers.directory.*',
      'application.models.directory.*',
      'application.components.*',
      'application.modules.srbac.controllers.SBaseController',
      'ext.EHttpClient.*',
      'ext.EHttpClient.adapter.*',
      'ext.EWideImage.EWideImage',
      'editable.*', //easy include of editable classes
      'application.extensions.yiichat.*',
   ),
   'modules' => array(
      // uncomment the following to enable the Gii tool
      'srbac' => array(
         'userclass' => 'User',
         'userid' => 'id',
         'username' => 'username',
         'debug' => true,
         'delimeter' => "@",
         'pageSize' => 10,
         'superUser' => 'Root',
         'css' => 'srbac.css',
         'layout' => 'application.views.layouts.main',
         'notAuthorizedView' => 'srbac.views.authitem.unauthorized',
         'alwaysAllowed' => array(),
         //'userActions' => array('show', 'View', 'List'),
         'listBoxNumberOfLines' => 15,
         'imagesPath' => 'srbac.images',
         'imagesPack' => 'tango',
         'iconText' => false,
         'header' => 'srbac.views.authitem.header',
         'footer' => 'srbac.views.authitem.footer',
         'showHeader' => true,
         'showFooter' => true,
         'alwaysAllowedPath' => 'srbac.components',
      ),
      'gii' => array(
         'generatorPaths' => array(
            'bootstrap.gii',
         ),
         'class' => 'system.gii.GiiModule',
         'password' => '111',
         // If removed, Gii defaults to localhost only. Edit carefully to taste.
         'ipFilters' => array('*', '::1'),
      ),
   ),
   // application components
   'components' => array(
      //X-editable config
      'editable' => array(
         'class' => 'editable.EditableConfig',
         'form' => 'bootstrap', //form style: 'bootstrap', 'jqueryui', 'plain' 
         'mode' => 'popup', //mode: 'popup' or 'inline'  
         'defaults' => array(//default settings for all editable elements
            'emptytext' => 'Натисніть, щоб редагувати'
         )
      ),
//      'assetManager' => array(
//        //'linkAssets' => true,
//        'forceCopy' => true,
//      ),
      'session' => array(
         'autoStart' => true,
      ),
      'authManager' => array(
         'class' => 'srbac.components.SDbAuthManager',
         'connectionID' => 'db',
         'itemTable' => 'sys_roles',
         'assignmentTable' => 'sys_roleassignments',
         'itemChildTable' => 'sys_rolechildren',
      ),
      'user' => array(
         'class' => "WebUser", // enable cookie-based authentication
         'allowAutoLogin' => true,
      ),
      'bootstrap' => array(
         'class' => 'bootstrap.components.Bootstrap',
      ),
      'clientScript' => array(
         'packages' => array(
            // описание пакета catalog
            'bootstrap-switch' => array(
               'basePath' => 'ext.bootstrap-switch.static',
               'js' => array('js/bootstrapSwitch.js'),
               'css' => array('stylesheets/bootstrap-switch.css'),
            ),
            'select2' => array(
                'basePath' => 'ext.select2',
                'js' => array('select2.js',"select2_locale_uk.js"),
                'css' => array('select2.css'),
            ),
         ),
      ),
      // uncomment the following to enable URLs in path-format
      'urlManager' => array(
         'urlFormat' => 'path',
         'caseSensitive' => false,
         'showScriptName' => false,
         'rules' => array(
            '<controller:\w+>/<id:\d+>' => '<controller>/view',
            '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
            '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            '<module>/<controller:\w+>/<id:\d+>' => '<module>/<controller>/view',
            '<module>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<module>/<controller>/<action>',
            '<module>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
         ),
      ),
      'db' => array(
         'connectionString' => 'mysql:host=localhost;dbname=docflow',
         'emulatePrepare' => true,
         'username' => 'root',
         'password' => 'sa',
         'charset' => 'utf8',
         // включаем профайлер
         'enableProfiling' => true,
         // показываем значения параметров
         'enableParamLogging' => true,
      ),
      'errorHandler' => array(
         // use 'site/error' action to display errors
         'errorAction' => 'site/error',
      ),

      'log' => array(
         'class' => 'CLogRouter',
         'routes' => array(
            array(
               'class' => 'CProfileLogRoute',
               'levels' => 'profile',
               'enabled' => false,
            ),
         // uncomment the following to show log messages on web pages

         /* array(
           'class'=>'CWebLogRoute',
           ), */
         ),
      ),
      'JGoogleAPI' => array(
          'class' => 'ext.JGoogleAPI.JGoogleAPI',
          //Default authentication type to be used by the extension
          'defaultAuthenticationType'=>'serviceAPI',

          //Account type Authentication data
          'serviceAPI' => array(
              'clientId' => '57453613982-6iiuc20c54b44kl17jmv4s79nls3ufua.apps.googleusercontent.com',
              //'clientEmail' => 'it.znu.edu@gmail.com',
              'clientEmail' => '57453613982-6iiuc20c54b44kl17jmv4s79nls3ufua@developer.gserviceaccount.com',
              'keyFilePath' => 'e6556fd783c6f1e8a9fc23bcda8e71d43b369709-privatekey.p12',
          ),
          /*
          //You can define one of the authentication types or both (for a Service Account or Web Application Account) 
          webAppAPI = array(
              'clientId' => 'YOUR_WEB_APPLICATION_CLIENT_ID',
              'clientEmail' => 'YOUR_WEB_APPLICATION_CLIENT_EMAIL',
              'clientSecret' => 'YOUR_WEB_APPLICATION_CLIENT_SECRET',
              'redirectUri' => 'YOUR_WEB_APPLICATION_REDIRECT_URI',
              'javascriptOrigins' => 'YOUR_WEB_APPLICATION_JAVASCRIPT_ORIGINS',
          ),
          */
          //'simpleApiKey' => '57453613982-6iiuc20c54b44kl17jmv4s79nls3ufua@developer.gserviceaccount.com',

          //Scopes needed to access the API data defined by authentication type
          'scopes' => array(
              'serviceAPI' => array(
                  'drive' => array(
                      'https://www.googleapis.com/auth/drive.file',
                  ),
              ),
              'webappAPI' => array(
                  'drive' => array(
                      'https://www.googleapis.com/auth/drive.file',
                  ),
              ),
          ),
          //Use objects when retriving data from api if true or an array if false
          'useObjects'=>true,
      ),
   ),
   // application-level parameters that can be accessed
   // using Yii::app()->params['paramName']
   'params' => array(
      // this is used in contact page
      'adminEmail' => '',
      'docPath' => dirname(__FILE__) . DIRECTORY_SEPARATOR 
         . '..' . DIRECTORY_SEPARATOR 
         . '..' . DIRECTORY_SEPARATOR 
         . '..' . DIRECTORY_SEPARATOR 
         . 'docflow' . DIRECTORY_SEPARATOR 
         . 'docs' . DIRECTORY_SEPARATOR ,
   ),
);
