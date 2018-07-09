<?php
require('upnp/vendor/autoload.php');
use jalder\Upnp\Upnp;
/**
* SSDP Finder 
* @package project
* @author Wizard <sergejey@gmail.com>
* @copyright http://majordomo.smartliving.ru/ (c)
* @version 0.1 (wizard, 15:02:03 [Feb 06, 2016])
*/
//
//
class ssdp_finder extends module {
/**
* ssdp_finder
*
* Module class constructor
*
* @access private
*/
function ssdp_finder() {
  $this->name="ssdp_finder";
  $this->title="SSDP Finder";
  $this->module_category="<#LANG_SECTION_DEVICES#>";
  $this->checkInstalled();
}
/**
* saveParams
*
* Saving module parameters
*
* @access public
*/
function saveParams($data=0) {
 $p=array();
 if (IsSet($this->id)) {
  $p["id"]=$this->id;
 }
 if (IsSet($this->view_mode)) {
  $p["view_mode"]=$this->view_mode;
 }
 if (IsSet($this->edit_mode)) {
  $p["edit_mode"]=$this->edit_mode;
 }
 if (IsSet($this->data_source)) {
  $p["data_source"]=$this->data_source;
 }
 if (IsSet($this->tab)) {
  $p["tab"]=$this->tab;
 }
 return parent::saveParams($p);
}
/**
* getParams
*
* Getting module parameters from query string
*
* @access public
*/
function getParams() {
  global $id;
  global $mode;
  global $view_mode;
  global $edit_mode;
  global $tab;
  global $data_source;
  if (isset($id)) {
   $this->id=$id;
  }
  if (isset($mode)) {
   $this->mode=$mode;
  }
  if (isset($view_mode)) {
   $this->view_mode=$view_mode;
  }
  if (isset($edit_mode)) {
   $this->edit_mode=$edit_mode;
  }
  if (isset($tab)) {
   $this->tab=$tab;
  }
  if (isset($data_source)) {
    $this->data_source=$data_source;
   }
}
/**
* Run
*
* Description
*
* @access public
*/
function run() {
 global $session;
  $out=array();
  if ($this->action=='admin') {
   $this->admin($out);
  } else {
   $this->usual($out);
  }
  if (IsSet($this->owner->action)) {
   $out['PARENT_ACTION']=$this->owner->action;
  }
  if (IsSet($this->owner->name)) {
   $out['PARENT_NAME']=$this->owner->name;
  }
  $out['VIEW_MODE']=$this->view_mode;
  $out['EDIT_MODE']=$this->edit_mode;
  $out['MODE']=$this->mode;
  $out['ACTION']=$this->action;
  $out['DATA_SOURCE']=$this->data_source;
  $out['TAB']=$this->tab;
  $this->data=$out;
  $p=new parser(DIR_TEMPLATES.$this->name."/".$this->name.".html", $this->data, $this);
  $this->result=$p->result;
}
/**
* BackEnd
*
* Module backend
*
* @access public
*/
function admin(&$out) {
 $this->getConfig();
 $out['API_URL']=$this->config['API_URL'];
 if (!$out['API_URL']) {
  $out['API_URL']='http://';
 }
 $out['API_KEY']=$this->config['API_KEY'];
 $out['API_USERNAME']=$this->config['API_USERNAME'];
 $out['API_PASSWORD']=$this->config['API_PASSWORD'];
 if ($this->view_mode=='update_settings') {
   global $api_url;
   $this->config['API_URL']=$api_url;
   global $api_key;
   $this->config['API_KEY']=$api_key;
   global $api_username;
   $this->config['API_USERNAME']=$api_username;
   global $api_password;
   $this->config['API_PASSWORD']=$api_password;
   $this->saveConfig();
   $this->redirect("?");
 }
 if (isset($this->data_source) && !$_GET['data_source'] && !$_POST['data_source']) {
  $out['SET_DATASOURCE']=1;
 }
 if ($this->data_source=='ssdp_devices' || $this->data_source=='') {
  if ($this->view_mode=='' || $this->view_mode=='search_ssdp_devices') {
   $this->search_ssdp_devices($out);
  }
  if ($this->view_mode=='scan_ssdp_devices') {
         $this->scan_ssdp_devices($out);
  }
  if ($this->view_mode=='edit_ssdp_devices') {
   $this->edit_ssdp_devices($out, $this->id);
  }
  if (isset($this->data_source) && !$_GET['data_source'] && !$_POST['data_source']) {
    $out['SET_DATASOURCE']=1;
   }
  if ($this->view_mode=='delete_ssdp_devices') {
   $this->delete_ssdp_devices($this->id);
   $this->redirect("?");
  }
  if ($this->view_mode=='add_to_SSDPdevices') {
   $this->add_to_SSDPdevices($this->id);
   $this->redirect("/admin.php?pd=&md=panel&inst=&action=ssdpdevices");
  }
  if ($this->view_mode=='add_to_pinghost') {
   $this->add_to_pinghost($this->id);
   $this->redirect("/admin.php?pd=&md=panel&inst=&action=pinghosts");
  }
 }
}
/**
* FrontEnd
*
* Module frontend
*
* @access public
*/
function usual(&$out) {
 $this->admin($out);
}
/**
* ssdp_devices search
*
* @access public
*/
 function search_ssdp_devices(&$out) {
  require(DIR_MODULES.$this->name.'/ssdp_devices_search.inc.php');
 }
 function scan_ssdp_devices(&$out) {
  require(DIR_MODULES.$this->name.'/ssdp_devices_scan.inc.php');
 }
/**
* get local IP 
*
* @access public
*/
function getLocalIp() { 
  return gethostbyname(trim(`hostname`)); 
}

/**
* ssdp_devices edit/add
*
* @access public
*/
 function edit_ssdp_devices(&$out, $id) {
   require(DIR_MODULES.$this->name.'/ssdp_devices_edit.inc.php');
 }
/**
* ssdp_devices add record to SSDPdevice
*
* @access public
*/
 function add_to_SSDPdevices($id) {
  if (!$id) {
      $id = ($_GET["id"]);
  }
  // podkluchaem prostie ustroystva i sozdaem ego
  include_once (DIR_MODULES.'devices/devices.class.php');
  $ssdpdevice=SQLSelectOne("SELECT * FROM ssdp_devices WHERE ID='".$id."'");
  $dev=new devices();
  $dev->renderStructure();
  $device_type=$ssdpdevice['TYPE']; // тип устройства (см выше допустимые типы) 
  $options=array(); // опции добавления
  $options['TABLE'] = 'ssdp_devices'; // таблица, куда потом запишется LINKED_OBJECT и LINKED_PROPERTY
  $options['TABLE_ID'] = $id; // ID записи в вышеназванной таблице (запись уже должна быть создана такая)
  $options['TITLE'] = $ssdpdevice['TITLE']; // название устройства (не обязательно)
  $options['LOCATION_ID']=$ssdpdevice['LOCATION']; // ID расположения (не обязательно)
  //$options['ADD_MENU']=1; // добавлять интерфейс работы с устройством в  меню (не обязательно)
  //$options['ADD_SCENE']=1; // добавлять интерфейс работы с устройством на сцену (не обязательно)
  $result=$dev->addDevice($device_type, $options); // добавляем устройство -- возвращает 1 в случае успешного добавления

 // если устройство создано то заполняем данные о нем в остальных таблицах - чтобы вручную не вводить
 if ($result){ 
   // zapolnyaem dannie ob ustroystve в обьектах
  $ssdpdevice=SQLSelectOne("SELECT * FROM ssdp_devices WHERE ID='".$id."'");
  $new_object_title = $ssdpdevice['LINKED_OBJECT'];
  $obj = SQLSelectOne("SELECT * FROM objects WHERE TITLE='".$new_object_title."'");
  $obj['DESCRIPTION'] = $options['TITLE'];
  $obj['LOCATION_ID'] = $options['LOCATION_ID'];
  If (IsSet($obj['ID'])) {
     SQLUpdate('objects', $obj);
  }
  $obj = SQLSelectOne("SELECT * FROM objects WHERE TITLE='".$new_object_title."'");
  $obj_id = $obj['ID'];
  $obj_title = $obj['TITLE'];
  $clas = SQLSelectOne("SELECT * FROM classes WHERE TITLE='".'S'.$device_type."'");
  $props = SQLSelect("SELECT * FROM properties WHERE CLASS_ID='".$clas['ID']."' OR CLASS_ID='".$clas['PARENT_ID']."'");
  $ssdp = SQLSelect("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'ssdp_devices'");
  foreach($props as $v) {
      foreach($ssdp as $t_name) {
          if ( mb_strtolower($v['TITLE']) == mb_strtolower($t_name ['COLUMN_NAME'])){
	            $ssdpinf=SQLSelectOne("SELECT ".DBSafe($v['TITLE'])." FROM ssdp_devices WHERE LINKED_OBJECT LIKE '".DBSafe($new_object_title)."'");
		    $pval = Array();
		    $pval['PROPERTY_ID'] = $v['ID'];
		    $pval['OBJECT_ID'] = $obj_id;
		    $pval['VALUE'] = $ssdpinf[DBSafe($v['TITLE'])];
		    $pval['PROPERTY_NAME'] = $obj_title.".".$v['TITLE'];
                    $pval['UPDATED'] = date('Y-m-d H:i:s');
		    $pval=SQLInsert('pvalues', $pval);
            }
        }
     }

   //set the class of UPNP devices
    $clasofdevice = SQLSelectOne("SELECT * FROM classes WHERE TITLE LIKE 'UPNPdevices'");

   //add the roomlocation in properties object
   //select properties id of linkedroom
	if ($options['LOCATION_ID']) {
            $props = SQLSelectOne("SELECT * FROM properties WHERE TITLE LIKE 'linkedRoom' ");
            $pval = Array();
            $pval['PROPERTY_ID'] = $props['ID'];
            $pval['OBJECT_ID'] = $obj_id;
            $pval['VALUE'] = getRoomObjectByLocation($options['LOCATION_ID']);
            $pval['PROPERTY_NAME'] = $obj_title.".linkedRoom";
            $pval['UPDATED'] = date('Y-m-d H:i:s');
            $pval=SQLInsert('pvalues', $pval);
    }
   //add the groupEco in properties object
   $props = SQLSelectOne("SELECT * FROM properties WHERE TITLE LIKE 'groupEco' AND CLASS_ID='".$clasofdevice['ID']."'");
   $pval = Array();
   $pval['PROPERTY_ID'] = $props['ID'];
   $pval['OBJECT_ID'] = $obj_id;
   $pval['VALUE'] = '1';
   $pval['PROPERTY_NAME'] = $obj_title.".groupEco";
   $pval['UPDATED'] = date('Y-m-d H:i:s');
   $pval=SQLInsert('pvalues', $pval);

   //add the groupEcoOn in properties object
   $props = SQLSelectOne("SELECT * FROM properties WHERE TITLE LIKE 'groupEcoOn' AND CLASS_ID='".$clasofdevice['ID']."'");
   $pval = Array();
   $pval['PROPERTY_ID'] = $props['ID'];
   $pval['OBJECT_ID'] = $obj_id;
   $pval['VALUE'] = '0';
   $pval['PROPERTY_NAME'] = $obj_title.".groupEcoOn";
   $pval['UPDATED'] = date('Y-m-d H:i:s');
   $pval=SQLInsert('pvalues', $pval);

   //add the groupSunrise in properties object
   $props = SQLSelectOne("SELECT * FROM properties WHERE TITLE LIKE 'groupSunrise' AND CLASS_ID='".$clasofdevice['ID']."'");
   $pval = Array();
   $pval['PROPERTY_ID'] = $props['ID'];
   $pval['OBJECT_ID'] = $obj_id;
   $pval['VALUE'] = '0';
   $pval['PROPERTY_NAME'] = $obj_title.".groupSunrise";
   $pval['UPDATED'] = date('Y-m-d H:i:s');
   $pval=SQLInsert('pvalues', $pval);

   //add the isActivity in properties object
   $props = SQLSelectOne("SELECT * FROM properties WHERE TITLE LIKE 'isActivity' AND CLASS_ID='".$clasofdevice['ID']."'");
   $pval = Array();
   $pval['PROPERTY_ID'] = $props['ID'];
   $pval['OBJECT_ID'] = $obj_id;
   $pval['VALUE'] = '0';
   $pval['PROPERTY_NAME'] = $obj_title.".isActivity";
   $pval['UPDATED'] = date('Y-m-d H:i:s');
   $pval=SQLInsert('pvalues', $pval);
  }
 }
/**
* get ip from url
*
* @access public
*/
function getIp($baseUrl,$withPort) {
	if( !empty($baseUrl) ){
        $parsed_url = parse_url($baseUrl);
        if($withPort ==true){
            $baseUrl = $parsed_url['scheme'].'://'.$parsed_url['host'].':'.$parsed_url['port']; 
        }else{
            $baseUrl = $parsed_url['host'];
        }
    }
    return  $baseUrl;
}
/**
* get port from url
*
* @access public
*/
 function getPort($address){
  $baseUrl="";
	if( !empty($address) ){
        $parsed_url = parse_url($address);
        $baseUrl = $parsed_url['port'];
        }
    return  $baseUrl;
}
/**
* ssdp_devices add record to terminal
*
* @access public
*/
function add_to_terminal($id) {
  if (!$id) {
      $id = ($_GET["id"]);
  }
  $ssdpdevice=SQLSelectOne("SELECT * FROM ssdp_devices WHERE ID='".$id."'");
  $terminal=array(); // опции добавления
  $terminal['NAME'] = $ssdpdevice['LINKED_OBJECT'];
  $terminal['TITLE'] = $ssdpdevice['LINKED_OBJECT'];
  $terminal['HOST'] = $this->getIp($ssdpdevice['ADDRESS'],false);
  $terminal['CANPLAY'] = '1';
  $terminal['PLAYER_TYPE'] = 'dlna';
  $terminal['PLAYER_PORT'] = $this->getPort($ssdpdevice['ADDRESS']);
  $terminal['IS_ONLINE'] = '1';
  $terminal['LINKED_OBJECT'] = $ssdpdevice['LINKED_OBJECT'];
  $terminal['LATEST_ACTIVITY'] = date("Y-m-d H:i:s");  
  $chek=SQLSelectOne("SELECT * FROM terminals WHERE LINKED_OBJECT='".$ssdpdevice['LINKED_OBJECT']."'");
  if ($chek['ID']) {
          $chek['ID'] = SQLUpdate('terminals', $terminal);
      } else {	
          SQLInsert('terminals', $terminal);
     }
 }
/**
* ssdp_devices add record to pinghost
*
* @access public
*/
 function add_to_pinghost($id) {
  if (!$id) {
      $id = ($_GET["id"]);
  }
  $ssdpdevice=SQLSelectOne("SELECT * FROM ssdp_devices WHERE ID='".$id."'");
  $pinghosts=array(); // опции добавления
  $pinghosts['TITLE'] = $ssdpdevice['TITLE'];
  $pinghosts['TYPE'] = '1';
  $pinghosts['SEARCH_WORD'] = $ssdpdevice['UUID'];
  $pinghosts['OFFLINE_INTERVAL'] = '600';
  $pinghosts['ONLINE_INTERVAL'] = '600';
  $pinghosts['HOSTNAME'] = $ssdpdevice['CONTROLADDRESS'];;
  $pinghosts['CODE_OFFLINE'] = 'say("Устройство ".$host[\'TITLE\']." пропало из сети, возможно его отключили" ,2);';
  $pinghosts['CODE_ONLINE'] = 'say("Устройство ".$host[\'TITLE\']." появилось в сети." ,2);';
  $pinghosts['LINKED_OBJECT'] = $ssdpdevice['LINKED_OBJECT'];
  $pinghosts['LINKED_PROPERTY'] = "alive";
  $pinghosts['CHECK_NEXT'] = date("Y-m-d H:i:s");  
  $chek=SQLSelectOne("SELECT * FROM pinghosts WHERE LINKED_OBJECT='".$ssdpdevice['LINKED_OBJECT']."'");
  if ($chek['ID']) {
          $chek['ID'] = SQLUpdate('pinghosts', $pinghosts);
      } else {	
          SQLInsert('pinghosts', $pinghosts);
     }
 }
/**
* ssdp_devices delete record
*
* @access public
*/
 function delete_ssdp_devices($id) {
  $rec=SQLSelectOne("SELECT * FROM ssdp_devices WHERE ID='$id'");
	 if($rec['LINKED_OBJECT']) {
  /// delete from simple device
  $sdev_del=SQLSelectOne("SELECT * FROM devices WHERE LINKED_OBJECT='".$rec['LINKED_OBJECT']."'");
  $sdevice = $sdev_del['ID'];
  include_once (DIR_MODULES.'devices/devices.class.php');
  $dev=new devices();
  $dev->delete_devices($sdevice);
  // delete from pinghost
  SQLExec("DELETE FROM pinghosts WHERE LINKED_OBJECT='".$rec['LINKED_OBJECT']."'"); 
  // delete from terminals
  SQLExec("DELETE FROM terminals WHERE LINKED_OBJECT='".$rec['LINKED_OBJECT']."'"); 
  // standart code
  // delete fromp tables ssdp_devices
  SQLExec("DELETE FROM ssdp_devices WHERE ID='".$rec['ID']."'"); 
		 }
 }
////////////////////////////////// конец моей вставки	
 function propertySetHandle($object, $property, $value) {
  $this->getConfig();
   $table='ssdp_devices';
   $properties=SQLSelect("SELECT ID FROM $table WHERE LINKED_OBJECT LIKE '".DBSafe($object)."' AND LINKED_PROPERTY LIKE '".DBSafe($property)."'");
   $total=count($properties);
   if ($total) {
    for($i=0;$i<$total;$i++) {
     //to-do
    }
   }
 }
 function processSubscription($event, $details='') {
 $this->getConfig();
  if ($event=='SAY') {
   $levelmes = getGlobal('ThisComputer.minMsgLevel');
   $level=$details['level'];
   $message=$details['message'];
   $ipadressserver = $this->getLocalIp();
   
   if (file_exists(ROOT.'/cms/cached/voice/' . md5($message) . '_google.mp3')) {
       $cached_filename = 'http://'. $ipadressserver . '/cms/cached/voice/' . md5($message) . '_google.mp3';
   } else if (file_exists(ROOT.'/cms/cached/voice/' . md5($message) . '_yandex.mp3')) {
       $cached_filename = 'http://'. $ipadressserver . '/cms/cached/voice/' . md5($message) . '_yandex.mp3';
   };
   $usedsay=SQLSelect("SELECT * FROM ssdp_devices WHERE USE_TO_SAY='".'1'."'");
   foreach ($usedsay as $saydev) {
        if ($saydev['TYPE']=='MediaRenderer' AND $saydev['USE_TO_SAY']=='1' AND $levelmes>=$level) {
          setGlobal($saydev['LINKED_OBJECT'].'.playUrl', $cached_filename);
        }
   }
/* if ($event=='ASK') {
   $tartget = $this->targetToIp($details['target']);
   if(!$target) return 0;
   $message=$details['prompt'];
   $this->send_mpt('ask', $message, $target);
   if($this->debug == 1) debmes('mpt ask ' . $message . '; target = ' . $target);
  }

  if ($event=='SAYTO') {
   if($this->debug == 1) debmes('mpt sayto start');
   $level=$details['level'];
   $message=$details['message'];
   $target = $this->targetToIp($details['destination']);
   if($this->debug == 1) debmes('mpt sayto after ttIp : ' . $target);
   if(!$target) return 0;
   $this->send_mpt('tts', $message, $target);
   if($this->debug == 1) debmes('mpt sayto ' . $message . '; level = ' . $level . '; to = ' . $target);
  }

  if ($event=='SAYREPLY') {
   $level=$details['level'];
   $message=$details['message'];
   $source=$details['source'];
   $tartget = $this->targetToIp($details['replyto']);
   if(!$target) return 0;
   $this->send_mpt('tts', $message, $target);
   if($this->debug == 1) debmes('mpt sayto ' . $message . '; level = ' . $level . '; to = ' . $destination);
  } */
   //playSound($cached_filename,1);
   //...
  }
 }
	

/**
* Install
*
* Module installation routine
*
* @access private
*/
 function install($data='') {
  // подписки на события 
  subscribeToEvent($this->name, 'SAYREPLY','',20);
  subscribeToEvent($this->name, 'SAYTO','',20);
  subscribeToEvent($this->name, 'ASK','',20);
  subscribeToEvent($this->name, 'SAY','',20);

  parent::install();
 }
/**
* Uninstall
*
* Module uninstall routine
*
* @access publ
*/
 function uninstall() {

			 
 // delete devices from ssdpdevices
  $allrec=SQLSelect("SELECT * FROM ssdp_devices"); 
  foreach ($allrec as $rec )   {
     $this->delete_ssdp_devices($rec['ID']);
     }
  // delete all tables 
  SQLExec('DROP TABLE IF EXISTS playlist_render');
  SQLExec('DROP TABLE IF EXISTS ssdp_devices');
  SQLExec('DROP TABLE IF EXISTS mediaservers_playlist');
  // unsubscribeFromEvent SAY
  unsubscribeFromEvent($this->name, 'SAY');
  unsubscribeFromEvent($this->name, 'SAYTO');
  unsubscribeFromEvent($this->name, 'ASK');
  unsubscribeFromEvent($this->name, 'SAYREPLY');

  //delete ssdp_finder module
  parent::uninstall();
 }
/**
* dbInstall
*
* Database installation routine
*
* @access private
*/
 function dbInstall($data) {
/*
ssdp_devices - 
*/
  $data = <<<EOD
 ssdp_devices: ID int(10) unsigned NOT NULL auto_increment
 ssdp_devices: TITLE varchar(100) NOT NULL DEFAULT ''
 ssdp_devices: SERVICES varchar(500) NOT NULL DEFAULT ''
 ssdp_devices: ADDRESS varchar(255) NOT NULL DEFAULT ''
 ssdp_devices: UUID varchar(255) NOT NULL DEFAULT ''
 ssdp_devices: MODEL varchar(255) NOT NULL DEFAULT ''
 ssdp_devices: MANUFACTURER varchar(255) NOT NULL DEFAULT ''
 ssdp_devices: DESCRIPTION varchar(255) NOT NULL DEFAULT ''
 ssdp_devices: LOCATION varchar(255) NOT NULL DEFAULT ''
 ssdp_devices: TYPE varchar(255) NOT NULL DEFAULT ''
 ssdp_devices: LOGO varchar(255) NOT NULL DEFAULT ''
 ssdp_devices: LINKED_OBJECT varchar(100) NOT NULL DEFAULT ''
 ssdp_devices: LINKED_PROPERTY varchar(100) NOT NULL DEFAULT ''
 ssdp_devices: LINKED_METHOD varchar(100) NOT NULL DEFAULT ''
 ssdp_devices: USE_TO_SAY int(1) unsigned NOT NULL DEFAULT 0
 ssdp_devices: UPDATED datetime
 ssdp_devices: CONTROLADDRESS varchar(255) NOT NULL DEFAULT ''
 
 mediaservers_playlist: ID int(255) unsigned NOT NULL auto_increment
 mediaservers_playlist: TITLE varchar(100) NOT NULL DEFAULT ''
 mediaservers_playlist: DESCRIPTION varchar(300) NOT NULL DEFAULT ''
 mediaservers_playlist: GENRE varchar(50) NOT NULL DEFAULT ''
 mediaservers_playlist: URL_LINK varchar(250) NOT NULL DEFAULT ''
 mediaservers_playlist: LINKED_OBJECT varchar(100) NOT NULL DEFAULT ''
 mediaservers_playlist: FAVORITE int(3) unsigned NOT NULL DEFAULT 0 
 
 playlist_render: ID int(255) unsigned NOT NULL auto_increment
 playlist_render: TITLE varchar(100) NOT NULL DEFAULT ''
 playlist_render: DESCRIPTION varchar(255) NOT NULL DEFAULT ''
 playlist_render: GENRE varchar(50) NOT NULL DEFAULT ''
 playlist_render: URL_LINK varchar(250) NOT NULL DEFAULT ''
 playlist_render: LINKED_OBJECT varchar(100) NOT NULL DEFAULT ''
 playlist_render: FAVORITE int(3) unsigned NOT NULL DEFAULT 0 
EOD;
  parent::dbInstall($data);
 }
// --------------------------------------------------------------------
}
/*
*
* TW9kdWxlIGNyZWF0ZWQgRmViIDA2LCAyMDE2IHVzaW5nIFNlcmdlIEouIHdpemFyZCAoQWN0aXZlVW5pdCBJbmMgd3d3LmFjdGl2ZXVuaXQuY29tKQ==
*
*/
