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
  include_once (DIR_MODULES.'ssdpdevices/ssdpdevices.class.php');
  $ssdpdevice=SQLSelectOne("SELECT * FROM ssdp_devices WHERE ID='".$id."'");
  $dev=new ssdpdevices();
  $device_type=$ssdpdevice['TYPE']; // тип устройства (см выше допустимые типы) 

  $options=array(); // опции добавления
  $options['TABLE'] = 'ssdp_devices'; // таблица, куда потом запишется LINKED_OBJECT и LINKED_PROPERTY
  $options['TABLE_ID'] = $id; // ID записи в вышеназванной таблице (запись уже должна быть создана такая)
  $options['TITLE'] = $ssdpdevice['TITLE']; // название устройства (не обязательно)
  //$options['LOCATION_ID']=1; // ID расположения (не обязательно)
  //$options['ADD_MENU']=1; // добавлять интерфейс работы с устройством в  меню (не обязательно)
  //$options['ADD_SCENE']=1; // добавлять интерфейс работы с устройством на сцену (не обязательно)
  $result=$dev->addSSDPDevice($device_type, $options); // добавляем устройство -- возвращает 1 в случае успешного добавления
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
  $pinghosts['OFFLINE_INTERVAL'] = '600';
  $pinghosts['ONLINE_INTERVAL'] = '600';
  $pinghosts['HOSTNAME'] = $ssdpdevice['ADDRESS'];
  $pinghosts['CODE_ONLINE'] = 'say("Устройство ".$host[\'TITLE\']." пропало из сети, возможно его отключили" ,2);';
  $pinghosts['CODE_OFFLINE'] = 'say("Устройство ".$host[\'TITLE\']." появилось в сети." ,2);';
  $pinghosts['LINKED_OBJECT'] = $ssdpdevice['LINKED_OBJECT'];
  $chek=SQLSelectOne("SELECT * FROM pinghosts WHERE HOSTNAME='".$ssdpdevice['IP']."'");
  if ($chek['ID']) {
          $chek['ID'] = SQLUpdate('pinghosts', $pinghosts);
      } else {	
          SQLInsert('pinghosts', $pinghosts);
     }
 }
////////////////////////////////// конец моей вставки
/**
* ssdp_devices delete record
*
* @access public
*/
 function delete_ssdp_devices($id) {
  $rec=SQLSelectOne("SELECT * FROM ssdp_devices WHERE ID='$id'");
  // some action for related tables
  SQLExec("DELETE FROM ssdp_devices WHERE ID='".$rec['ID']."'");
 }
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
   $level=$details['level'];
   $message=$details['message'];
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
  subscribeToEvent($this->name, 'SAY');
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
  SQLExec('DROP TABLE IF EXISTS ssdp_devices');
  unsubscribeFromEvent($this->name, 'SAY');
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
 ssdp_devices: NAME varchar(100) NOT NULL DEFAULT ''
 ssdp_devices: SERVICES varchar(500) NOT NULL DEFAULT ''
 ssdp_devices: ADDRESS varchar(255) NOT NULL DEFAULT ''
 ssdp_devices: IP varchar(255) NOT NULL DEFAULT ''
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
 ssdp_devices: UPDATED datetime


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
