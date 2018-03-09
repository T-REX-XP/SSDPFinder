<?php
/**
* ssdpdevices 
* @package project
* @author Wizard <sergejey@gmail.com>
* @copyright http://majordomo.smartliving.ru/ (c)
* @version 0.1 (wizard, 13:07:05 [Jul 19, 2016])
*/
include_once(DIR_MODULES.'devices/devices.class.php');

class ssdpdevices extends devices {
/**
* ssdpdevices
*
* Module class constructor
*
* @access private
*/
function ssdpdevices() {
  $this->name="ssdpdevices";
  $this->title="SSDP devices";
  $this->module_category="<#LANG_SECTION_DEVICES#>";
  $this->checkInstalled();
  $this->setDictionary();

}
function setDictionary() {
   include_once(DIR_MODULES.'ssdpdevices/ssdpdevices_structure.inc.php');
   //include_once(DIR_MODULES.'devices/devices_structure.inc.php');
   $this->device_types = $this->ssdpdevices_types;
   include_once(DIR_MODULES.'devices/devices_structure_links.inc.php');
}
///////////////////////////////////////////////////
function addSSDPDevice($device_type, $options=0) {
     $this->setDictionary();
     $type_details=$this->getTypeDetails($device_type);

     if (!is_array($options)) {
         $options=array();
     }
     if (!is_array($this->device_types[$device_type])) {
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
         $new_object_title=ucfirst($device_type).$this->getNewObjectIndex($type_details['CLASS']);
         $object_id=addClassObject($type_details['CLASS'],$new_object_title,'sdevice'.$rec['ID']);
         $rec['LINKED_OBJECT']=$new_object_title;
         if (preg_match('/New device .+/',$rec['TITLE'])) {
             $rec['TITLE']=$rec['LINKED_OBJECT'];
         }
         SQLUpdate('devices',$rec);
     }

     if ($table_rec['ID']) {
         $this->addDeviceToSourceTable($options['TABLE'],$table_rec['ID'],$rec['ID']);
     }

     if ($options['ADD_MENU']) {
         $this->addDeviceToMenu($rec['ID']);
     }

     if ($options['ADD_SCENE']) {
         $this->addDeviceToScene($rec['ID']);
     }

   /// svoya dobavka dlya izmeneniy tablicy objects
   $obj = SQLSelectOne("SELECT * FROM objects WHERE TITLE='".$new_object_title."'");
   $obj['DESCRIPTION'] = $options['TITLE'];
   If (IsSet($obj['ID'])) {
      SQLUpdate('objects', $obj);
   }
  ////////////////////////////////
  // berem zapis objektov
  $obj = SQLSelectOne("SELECT * FROM objects WHERE TITLE='".$new_object_title."'");
  $obj_id = $obj[ID];
  $obj_title = $obj[TITLE];

  // berem zapis klasov
  $clas = SQLSelectOne("SELECT * FROM classes WHERE TITLE='".'S'.$device_type."'");
  // berem zapis svoystv
  $props = SQLSelect("SELECT * FROM properties WHERE CLASS_ID='".$clas['ID']."' OR CLASS_ID='".$clas['PARENT_ID']."'");
  
  foreach($props as $k=>$v) {
    $ssdpinf=SQLSelectOne("SELECT ".DBSafe($v[TITLE])." FROM ssdp_devices WHERE LINKED_OBJECT LIKE '".DBSafe($new_object_title)."'");
    $pval = Array();
    $pval['PROPERTY_ID'] = $v[ID];
    $pval['OBJECT_ID'] = $obj_id;
    $pval['VALUE'] = $ssdpinf[DBSafe($v[TITLE])];
    $pval['PROPERTY_NAME'] = $obj_title.".".$v[TITLE];
    $pval=SQLInsert('pvalues', $pval);
  }
return 1;

 }
/////////////////////////
/*
* devices search
*
* @access public
*/
 function search_devices(&$out) {
  require(DIR_MODULES.'devices/devices_search.inc.php');
 }
/**
* devices edit/add
*
* @access public
*/
 function edit_devices(&$out, $id) {
  require(DIR_MODULES.'devices/devices_edit.inc.php');
 }

  
/**
* Install
*
* Module installation routine
*
* @access private
*/
 function install($data='') {
  parent::install();

  @include_once(ROOT.'languages/'.$this->name.'_'.SETTINGS_SITE_LANGUAGE.'.php');
  @include_once(ROOT.'languages/'.$this->name.'_default'.'.php');

  SQLExec("UPDATE project_modules SET TITLE='SSDP Devices' WHERE NAME='".$this->name."'");

  $this->setDictionary();
  $this->renderStructure();
  $this->homebridgeSync();
 }
/**
* Uninstall
*
* Module uninstall routine
*
* @access public
*/
 function uninstall() {
  parent::uninstall();
 }
/**
* dbInstall
*
* Database installation routine
*
* @access private
*/
 function dbInstall($data='') {
/*
devices - 
*/
  $data = <<<EOD
 devices: ID int(10) unsigned NOT NULL auto_increment
 devices: TITLE varchar(100) NOT NULL DEFAULT ''
 devices: TYPE varchar(100) NOT NULL DEFAULT ''
 devices: LINKED_OBJECT varchar(100) NOT NULL DEFAULT ''
 devices: LOCATION_ID int(10) unsigned NOT NULL DEFAULT 0  
 devices: FAVORITE int(3) unsigned NOT NULL DEFAULT 0 

 devices: SYSTEM varchar(255) NOT NULL DEFAULT ''
 devices: SUBTYPE varchar(100) NOT NULL DEFAULT ''
 devices: ENDPOINT_MODULE varchar(255) NOT NULL DEFAULT ''
 devices: ENDPOINT_NAME varchar(255) NOT NULL DEFAULT ''
 devices: ENDPOINT_TITLE varchar(255) NOT NULL DEFAULT ''
 devices: ROLES varchar(100) NOT NULL DEFAULT ''

 devices_linked: ID int(10) unsigned NOT NULL auto_increment
 devices_linked: DEVICE1_ID int(10) unsigned NOT NULL DEFAULT 0
 devices_linked: DEVICE2_ID int(10) unsigned NOT NULL DEFAULT 0
 devices_linked: LINK_TYPE varchar(100) NOT NULL DEFAULT ''
 devices_linked: LINK_SETTINGS text
 devices_linked: COMMENT varchar(255) NOT NULL DEFAULT '' 


EOD;
  parent::dbInstall($data);
 }
// --------------------------------------------------------------------
}
/*
*
* TW9kdWxlIGNyZWF0ZWQgSnVsIDE5LCAyMDE2IHVzaW5nIFNlcmdlIEouIHdpemFyZCAoQWN0aXZlVW5pdCBJbmMgd3d3LmFjdGl2ZXVuaXQuY29tKQ==
*
*/
