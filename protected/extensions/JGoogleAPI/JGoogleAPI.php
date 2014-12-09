<?php

/**
 * The MIT License
 *
 * @copyright Copyright 2012 João Parreira <joaofrparreira@gmail.com>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * ---------------------------------------------------- 
 *
 * Date: 28/Nov/2012
 * Time: 19:08:49 
 * File: JGoogleAPI.php 
 * Encoding: UTF-8
 *
 * @author: João Parreira <joaofrparreira@gmail.com>
 * @package Project: JGoogleAPI
 * @version 0.1a
 *
 * */
YiiBase::setPathOfAlias('JGoogleAPISrcAlias', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'google-api-php-client' . DIRECTORY_SEPARATOR . 'src');

class JGoogleAPI extends CApplicationComponent {

    /**
     * @var string Defines the default authentication type to be used
     */
    public $defaultAuthenticationType = 'serviceAPI';
    private $authenticationType = null;
    // Credentials can be obtained at https://code.google.com/apis/console
    // See http://code.google.com/p/google-api-php-client/wiki/OAuth2 for more information
    /**
     * @var webApplication
     * Saves the data for api access (Accessed by web browsers over a network.)
     */
    public $webappAPI = array(
        'clientId' => null,
        'clientEmail' => null,
        'clientSecret' => null,
        'redirectUri' => null,
        'javascriptOrigins' => null,
    );

    /**
     * @var serviceAPI
     * Saves the data for api with a service account (Calls Google APIs on behalf of your application instead of an end-user.)
     * This is the default authentication type
     */
    public $serviceAPI = array(
        'clientId' => null,
        'clientEmail' => null,
        'publicKey' => null,
        'keyFilePath' => null,
        'privateKeyPassword' => 'notasecret', //default
        'assertionType' => 'http://oauth.net/grant_type/jwt/1.0/bearer',
        'prn' => false
    );
    private $_serviceAPI = array(
        'clientId' => null,
        'clientEmail' => null,
        'publicKey' => null,
        'keyFilePath' => null,
        'privateKeyPassword' => 'notasecret', //default
        'assertionType' => 'http://oauth.net/grant_type/jwt/1.0/bearer',
        'prn' => false
    );
    public $simpleApiKey = null;

    /**
     * @var scopes array
     * This var defines the scopes that will be used by the application
     * Ex: $scopes = array(
     *          'serviceAPI'=>array(
     *              'drive'=>array(
     *                  'https://www.googleapis.com/auth/drive.file',
     *                  'https://www.googleapis.com/auth/drive'
     *              ),
     *          ),
     *          'webappAPI'=>array(
     *              'drive'=>array(
     *                  'https://www.googleapis.com/auth/drive.file',
     *                  'https://www.googleapis.com/auth/drive'
     *              ),
     *          ),
     *      );
     * 
     * Information for available scopes can be found in https://developers.google.com in the api you're trying to access
     */
    public $scopes = null;
    public $useObjects = false;

    /**
     * @var Google_Client $client 
     * Handle the google client when created
     */
    private $client = null;
    private $clientType = 'serviceAPI';

    /**
     * @var GoogleService $_service
     * Handle the google services when created
     */
    private $_services = array();

    /**
     * @var boolean $autoSession
     * Automatic save the auth token to the session
     */
    public $autoSession = true;

    public function init() {
        parent::init();
        $this->serviceAPI = array_merge($this->_serviceAPI, $this->serviceAPI);
    }

    /**
     * @param string $type Authentication type "service account" or "User authentication" (serviceAPI or webappAPI) defualt is 'serviceAPI'
     * @return \Google_Client Return the google client object
     * @throws CException Throws exception if type is invalid
     */
    private function createClient($type = null) {
        if (!isset($this->authenticationType)) {
            $this->authenticationType = $this->defaultAuthenticationType;
        }
        if (!is_null($type)) {
            if ($type == 'serviceAPI' || $type == 'webappAPI')
                $this->authenticationType = $type;
            else
                throw new CException("Invalid choosen authentication type (" . $type . "). Must be 'serviceAPI' or 'webappAPI'.");
        }

        Yii::import('JGoogleAPISrcAlias.Google_Client');
        $client = new Google_Client();
        $client->setApplicationName(Yii::app()->name);
        $client->setUseObjects($this->useObjects);
        switch ($this->authenticationType) {
            case 'serviceAPI':
                $scopes = array();
                if (isset($this->scopes['serviceAPI'])) {
                    foreach ($this->scopes['serviceAPI'] as $service)
                        foreach ($service as $scope)
                            $scopes[] = $scope;
                }
                
//                var_dump($this->serviceAPI['clientEmail']);
//                var_dump($scopes);
//                exit();
                
                $assertionCredentials = new Google_AssertionCredentials($this->serviceAPI['clientEmail'], $scopes, file_get_contents($this->serviceAPI['keyFilePath']), $this->serviceAPI['privateKeyPassword'], $this->serviceAPI['assertionType'], $this->serviceAPI['prn']);
                $client->setAssertionCredentials($assertionCredentials);
                $client->setClientId($this->serviceAPI['clientId']);
                $this->clientType = 'serviceAPI';
                break;
            case 'webappAPI':
                $scopes = array();
                if (isset($this->scopes['webappAPI'])) {
                    foreach ($this->scopes['webappAPI'] as $service)
                        foreach ($service as $scope)
                            $scopes[] = $scope;
                }
                $client->setClientId($this->webappAPI['clientId']);
                $client->setClientSecret($this->webappAPI['clientSecret']);
                $client->setRedirectUri($this->webappAPI['redirectUri']);
                $client->setDeveloperKey($this->simpleApiKey);
                $client->setScopes($scopes);
                $this->clientType = 'webappAPI';
                break;
            default:
                throw new CException("Invalid type given for the client authentication. Must be 'serviceAPI' or 'webappAPI'.");
                break;
        }
        return $client;
    }

    /**
     * @param string $type Authentication type "service account" or "User authentication" (serviceAPI or webappAPI)
     * @return \Google_Client Return the google client object
     */
    public function getClient($type = null) {
        if (is_null($this->client))
            $this->client = $this->createClient($type);
        return $this->client;
    }

    /**
     * @param string $serviceName Name of the service that we want to call
     * @param string $type Authentication type "service account" or "User authentication" (serviceAPI or webappAPI)
     * @return service Return instance to Google Service
     * @throws CException  Throw exception if service name is not declared
     */
    public function getService($serviceName, $type = null) {
        if (!isset($serviceName)) {
            throw new CException("You must declare a service name to use.");
        } else {
            //Need to do this so the path is set by the api library
            $client = $this->getClient(is_null($this->clientType) ? $type : $this->clientType);
            if (!isset($this->_services[$serviceName]) || !array_key_exists($serviceName, $this->_services)) {
                $serviceClassName = "Google_" . $serviceName . "Service";
                Yii::import('JGoogleAPISrcAlias.contrib.' . $serviceClassName);
                $service = new $serviceClassName($client);
                $this->_services[$serviceName] = $service;
            }
        }
        return $this->_services[$serviceName];
    }

    /**
     * @param string $name Name of the object that we want to call (Ex: DriveFile )
     * @param string\Object $service Name or Instance of the service to use, needed to preload required class
     * @param string $type Authentication type "service account" or "User authentication" (serviceAPI or webappAPI) 
     * @return \Object Return the Google Object
     * @throws CException Throw Exception if we don't declare the Object name or the Service name or instance
     * @example getObject('DriveFile','Drive');
     * If the service is not instanciated and we pass the service name that'll be created automaticaly
     */
    public function getObject($name, $service, $type = null) {
        if (!isset($name)) {
            throw new CException("You must declare one name of the object to call.");
        } else {
            if (!isset($service)) {
                throw new CException("You must pass a service name or service object to use. Required for class preload.");
            } else {
                if (is_string($service)) {
                    $this->getService($service, $type);
                }
                $objName = "Google_" . $name;
                Yii::import('JGoogleAPISrcAlias.contrib.' . $objName);
                return new $objName();
            }
        }
    }
    
    
    ////////////////////////////////////////////////////////////////////////////
    //                       далі пішли мої функції                           //
    ////////////////////////////////////////////////////////////////////////////
    
    /**
     * Метод шукає директорію по заданому імені (знаходить першу серед однаково названих)
     * @param $service Google_DriveService
     * @param $dirname string
     * @author Igor Sinepolsky <sinepolskiyihor@gmail.com>
     */
    public function getDirByName($service,$dirname){
      if (!$service || !$dirname){
        return null;
      }//end if
      $drive_dir_mimeType = 'application/vnd.google-apps.folder';
      $drive_dir = null;
      $drive_files = $service->files->listFiles();
      foreach( $drive_files as $item ) {
        for ($i = 0; ($i < count($item) && is_array($item)); $i++){
          if ($item[$i]->title == $dirname && 
              $item[$i]->mimeType == $drive_dir_mimeType){
            $drive_dir = $item[$i];
            break;
          } //end if
        }//end for
      }//end foreach
      return $drive_dir;
    }
    
    /**
     * Метод встановлює права запису для заданих email-адрес на об`єкт із вказаним ID
     * @param $service Google_DriveService
     * @param $drive_item_id string
     * @param $email_writers string[]
     * @author Igor Sinepolsky <sinepolskiyihor@gmail.com>
     */
    public function setWritePermissions($service,$drive_item_id,$email_writers){
      $perm = new Google_Permission();
      $perm->setType('user');
      $perm->setRole('writer');
      foreach ($email_writers as $email){
        $perm->setValue($email);
        $service->permissions->insert($drive_item_id,$perm);
      }
    }
    
    /**
     * Метод створює теку з заданими параметрами і встановлює права запису для заданих email-адрес
     * @param $service Google_DriveService
     * @param $dirname string
     * @param $descr string
     * @param $fileParent Google_ParentReference
     * @param $email_writers string[]
     * @author Igor Sinepolsky <sinepolskiyihor@gmail.com>
     */
    public function createDir($service,$dirname,$descr,$dirParent = null,$email_writers){
      if (!$service || !$dirname){
        return null;
      }
      $drive_dir_mimeType = 'application/vnd.google-apps.folder';
      $drive_item = new Google_DriveFile();
      $drive_item->setTitle( $dirname );
      $drive_item->setMimeType( $drive_dir_mimeType );
      $drive_item->setDescription( $descr );
      if ($dirParent){
        $drive_item->setParents( array( $dirParent ) );
      }
      $drive_dir = $service->files->insert($drive_item, array(
          'data' => null,
          'mimeType' => $drive_dir_mimeType,
      ));
      $this->setWritePermissions($service,$drive_dir->id,$email_writers);
      return $drive_dir;
    }
    
    /**
     * Метод завантажує файл з заданими параметрами і встановлює права запису для заданих email-адрес
     * @param $service Google_DriveService
     * @param $file_path string
     * @param $file_name string
     * @param $descr string
     * @param $fileParent Google_ParentReference
     * @param $email_writers string[]
     * @author Igor Sinepolsky <sinepolskiyihor@gmail.com>
     */
    public function uploadFile($service,
            $file_path, $file_name, $file_description,
            $fileParent, $email_writers){
      if (!$service || !$file_name || !$file_path){
        return null;
      }
      $file_content = file_get_contents($file_path);
      $_finfo = new finfo( FILEINFO_MIME );
      $mimeType_array = explode( ';', $_finfo->buffer($file_content));
      $mimeType = $mimeType_array[0];


      $drive_file = new Google_DriveFile();
      $drive_file->setTitle( $file_name );
      $drive_file->setDescription( $file_description );
      $drive_file->setMimeType( $mimeType );
      $drive_file->setParents( array( $fileParent ) );

      $createdFile = $service->files->insert($drive_file, array(
          'data' => $file_content,
          'mimeType' => $mimeType,
      ));
      $this->setWritePermissions($service,$createdFile->id,$email_writers);
      return $createdFile;
    }

}

?>
