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
  // редактируем структур файла (на всякий случай проверяем) уже должно быть сделано это
  $current = file_get_contents(DIR_MODULES.'devices/devices_structure.inc.php');
  $chek = stripos($current, 'UPNPdevices');
  if ($chek === false) {
            $this->edit_device_structure();
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
  //$result=$dev->addDevice($device_type, $options); // добавляем устройство -- возвращает 1 в случае успешного добавления
  
  // поскольку пока функция добавления устройств работает не правильно то продублируем ее здесь	 
     $dev->setDictionary();
     $type_details=$dev->getTypeDetails($device_type);
     if (!is_array($options)) {
         $options=array();
     }
     if (!is_array($dev->device_types[$device_type])) {
         return 0;
     }
     if ($options['TABLE'] && $options['TABLE_ID']) {
         $table_rec=SQLSelectOne("SELECT * FROM ".$options['TABLE']." WHERE ID=".$options['TABLE_ID']);
         if (!$table_rec['ID']) {
             return 0;
         }
     }
     if ($options['LINKED_OBJECT']!='') {
         $old_device=SQLSelectOne("SELECT ID FROM devices WHERE LINKED_OBJECT LIKE '".DBSafe($options['LINKED_OBJECT'])."'");
         if ($old_device['ID']) return $old_device['ID'];
         $rec['LINKED_OBJECT']=$options['LINKED_OBJECT'];
     }
     
     $rec=array();
     $rec['TYPE']=$device_type;
     if ($options['TITLE']) {
       $rec['TITLE']=$options['TITLE'];
     } else {
       $rec['TITLE']='New device '.date('H:i');
     }
     if ($options['LOCATION_ID']) {
         $rec['LOCATION_ID']=$options['LOCATION_ID'];
     }
     $rec['ID']=SQLInsert('devices',$rec);
     if ($rec['LOCATION_ID']) {
         $location_title=getRoomObjectByLocation($rec['LOCATION_ID'],1);
     }
     if (!$rec['LINKED_OBJECT']) {
         $new_object_title=ucfirst($rec['TYPE']).$dev->getNewObjectIndex($type_details['CLASS']);
         $object_id=addClassObject($type_details['CLASS'],$new_object_title,'sdevice'.$rec['ID']);
         $rec['LINKED_OBJECT']=$new_object_title;
         if (preg_match('/New device .+/',$rec['TITLE'])) {
             $rec['TITLE']=$rec['LINKED_OBJECT'];
         }
         SQLUpdate('devices',$rec);
     }
     if ($table_rec['ID']) {
         $dev->addDeviceToSourceTable($options['TABLE'],$table_rec['ID'],$rec['ID']);
     }
     if ($options['ADD_MENU']) {
         $dev->addDeviceToMenu($rec['ID']);
     }
     if ($options['ADD_SCENE']) {
         $dev->addDeviceToScene($rec['ID']);
     }
	
	 //конец функции простых устройств
	 
	 
   // zapolnyaem dannie ob ustroystve 
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
		    $pval=SQLInsert('pvalues', $pval);
            }
        }
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
             if ($parsed_url['host'] == '127.0.0.1'){
                 $parsed_url['host'] = getLocalIp();
                }
        $baseUrl = $parsed_url['scheme'].'://'.$parsed_url['host'].':'.$parsed_url['port']; 
       }else{
             if ($parsed_url['host'] == '127.0.0.1'){
                 $parsed_url['host'] = getLocalIp();
                }
            $baseUrl = $parsed_url['host'];
        }
    }
    return  $baseUrl;
}
/**
* get local IP 
*
* @access public
*/
//получаем айпи адрес локального компьютера
function getLocalIp() { 
return gethostbyname(trim(`hostname`)); 
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
  $terminal['TITLE'] = $ssdpdevice['DESCRIPTION'];
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
  $pinghosts['TYPE'] = '0';
  $pinghosts['OFFLINE_INTERVAL'] = '600';
  $pinghosts['ONLINE_INTERVAL'] = '600';
  $pinghosts['HOSTNAME'] = $this->getIp($ssdpdevice['ADDRESS'],false);
  $pinghosts['CODE_ONLINE'] = 'say("Устройство ".$host[\'TITLE\']." пропало из сети, возможно его отключили" ,2);';
  $pinghosts['CODE_OFFLINE'] = 'say("Устройство ".$host[\'TITLE\']." появилось в сети." ,2);';
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
   $cached_filename = $_SERVER['REMOTE_ADDR'] . '/cached/voice/*' . md5($message) . '*.mp3';
   $usedsay=SQLSelect("SELECT * FROM ssdp_devices WHERE USE_TO_SAY='".'1'."'");
   foreach ($usedsay as $saydev) {
        if ($saydev['TYPE']=='MediaRenderer' AND $saydev['USE_TO_SAY']=='1' AND $levelmes<=$level) {
          setGlobal($saydev['LINKED_OBJECT'].'.playUrl', $cached_filename);
        }
   }
   //playSound($cached_filename,1);
   //...
  }
 }
	
/**
* Edit device structure
*
*
* @access private
*/
// edit device module
function edit_device_structure() {
  if (file_exists(DIR_MODULES.'devices/devices_structure.inc.php')) {
      $current = file_get_contents(DIR_MODULES.'devices/devices_structure.inc.php');
      $add = file_get_contents(DIR_MODULES.'ssdp_finder/ssdpdevices_structure.template.php');
      $chek = stripos($current, 'UPNPdevices');
      if ($chek === false) {
          file_put_contents(DIR_MODULES.'ssdp_finder/devices_structure.inc.original',  $current);
          $data = str_replace(");", $add, $current);
          file_put_contents(DIR_MODULES.'devices/devices_structure.inc.php', $data);
          }
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

  subscribeToEvent($this->name, 'SAYTO','',20);
  subscribeToEvent($this->name, 'ASK','',20);
  subscribeToEvent($this->name, 'SAY');
  $this->edit_device_structure();
  // delete module ssdpdevices
  SQLExec("DELETE FROM plugins WHERE MODULE_NAME LIKE 'ssdpdevices'");
  SQLExec("DELETE FROM project_modules WHERE NAME LIKE 'ssdpdevices'");
  include_once (DIR_MODULES.'market/market.class.php');
  $market=new market();
  $market->removeTree(ROOT.'modules/ssdpdevices');
  $market->removeTree(ROOT.'templates/ssdpdevices');
  if (file_exists(ROOT.'scripts/cycle_ssdp_finder.php')) {
   @unlink(ROOT.'scripts/cycle_ssdp_finder.php');
  }
  
  parent::install();
 }
/**
* Uninstall
*
* Module uninstall routine
*
* @access public
*/
 function uninstall() {
 // restore  devices_structure.inc.php
 $current = file_get_contents(DIR_MODULES.'ssdp_finder/devices_structure.inc.original');
 file_put_contents(DIR_MODULES.'devices/devices_structure.inc.php',  $current, LOCK_EX);
	 
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
