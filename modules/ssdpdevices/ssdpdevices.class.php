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
return 1;

 }
////////////// render structure /////
function renderStructure() {

  if (defined('DISABLE_SIMPLE_DEVICES') && DISABLE_SIMPLE_DEVICES==1) return;

  foreach($this->device_types as $k=>$v) {
      //$v['CLASS']
      //$v['PARENT_CLASS']
      //$v['PROPERTIES']
      //$v['METHODS']

      //CLASS
      if ($v['PARENT_CLASS']) {
          $class_id=addClass($v['CLASS'],$v['PARENT_CLASS']);
      } else {
          $class_id=addClass($v['CLASS']);
      }
      if ($class_id) {
          $class=SQLSelectOne("SELECT * FROM classes WHERE ID=".$class_id);
          if ($v['DESCRIPTION']) {
            $class['DESCRIPTION']=$v['DESCRIPTION'];
            SQLUpdate('classes',$class);
          }
      }

      //PROPERTIES
      if (is_array($v['PROPERTIES'])) {
          foreach($v['PROPERTIES'] as $pk=>$pv) {
            $prop_id=addClassProperty($v['CLASS'],$pk,(int)$pv['KEEP_HISTORY']);
              if ($prop_id) {
                  $property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
                  if (is_array($pv)) {
                      foreach($pv as $ppk=>$ppv) {
                          if (substr($ppk,0,1)=='_') continue;
                          $property[$ppk]=$ppv;
                      }
                      SQLUpdate('properties',$property);
                  }
              }
          }
      }

      //METHODS
      if (is_array($v['METHODS'])) {
          foreach($v['METHODS'] as $mk=>$mv) {
              $method_id=addClassMethod($v['CLASS'],$mk,"require(DIR_MODULES.'ssdpdevices/".$v['CLASS']."_".$mk.".php');",'SDevices');
              if (!file_exists(DIR_MODULES."ssdpdevices/".$v['CLASS']."_".$mk.".php")) {
               $code='<?php'."\n\n";
               @SaveFile(DIR_MODULES."ssdpdevices/".$v['CLASS']."_".$mk.".php", $code);
              }
              if ($method_id) {
                  $method=SQLSelectOne("SELECT * FROM methods WHERE ID=".$method_id);
                  if (is_array($mv)) {
                       foreach($mv as $mmk=>$mmv) {
                           if (substr($mmk,0,1)=='_') continue;
                           $method[$mmk]=$mmv;
                       }
                       SQLUpdate('methods',$method);
                  }
              }
          }
      }

      if (is_array($v['INJECTS'])) {
          foreach($v['INJECTS'] as $class_name=>$methods) {
              addClass($class_name);
              foreach($methods as $mk=>$mv) {
                  list($object,$method_name)=explode('.',$mk);
                  addClassObject($class_name,$object);
                  if (!file_exists(DIR_MODULES."ssdpdevices/".$mv.".php")) {
                      $code='<?php'."\n\n";
                      @SaveFile(DIR_MODULES."ssdpdevices/".$mv.".php", $code);
                  }
                  injectObjectMethodCode($mk,'SDevices',"require(DIR_MODULES.'ssdpdevices/".$mv.".php');");
              }
          }
      }
  }
  subscribeToEvent('devices', 'COMMAND', '', 100);

  //update cameras
    $objects = getObjectsByClass('SCameras');
    $total = count($objects);
    for ($i = 0; $i < $total; $i++) {
        $ot = $objects[$i]['TITLE'];
        callMethod($ot.'.updatePreview');
    }
    //update SDigitalSecurityCamera
    $objects = getObjectsByClass('SDigitalSecurityCamera');
    $total = count($objects);
    for ($i = 0; $i < $total; $i++) {
        $ot = $objects[$i]['TITLE'];
        callMethod($ot.'.updatePreview');
    }

}


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
  //parent::uninstall();
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
