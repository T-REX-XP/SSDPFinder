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
  // вывод на необходимость обновления методов
  $out['UPDATE_METHODS']=$this->chek_update_drivers();
  // конец вставки
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
 $out['API_USERNAME']=$this->config['API_USERNAME'];
 if ($this->view_mode=='update_settings') {
  
   global $api_username;
   $this->config['API_USERNAME']=$api_username;
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
  if ($this->view_mode=='scan_3rddevice_devices') {
         $this->scan_3rddevice_devices($out);
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
   $this->redirect("?");
  }
  if ($this->view_mode=='add_to_pinghost') {
   $this->add_to_pinghost($this->id);
   $this->redirect("/admin.php?pd=&md=panel&inst=&action=pinghosts");
  }
  if ($this->view_mode=='clear_trash') {
   $this->clear_trash($this->id);
   $this->redirect("?");
  }
  if ($this->view_mode=='update_methods') {
   $this->update_methods($this->id);
   $this->redirect("?");
  }
  if ($this->view_mode=='install_module') {
   $this->install_module($this->id, $this->namemodule, $this->installed_module);
   $this->redirect("?");
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
* ssdp_devices install modules
* функция добавления устройства в список ssdp_devices и установки модуля для устройства
* @access public
*/
 function install_module(&$out) {
  require(DIR_MODULES.$this->name.'/ssdp_install_module.inc.php');
 }


/**
* ssdp_devices search
*
* @access public
*/
 function search_ssdp_devices(&$out) {
  require(DIR_MODULES.$this->name.'/ssdp_devices_search.inc.php');
 }


/**
* ssdp_devices scan
*
* @access public
*/
 function scan_ssdp_devices(&$out) {
  require(DIR_MODULES.$this->name.'/ssdp_devices_scan.inc.php');
 }

/**
* ssdp_devices scan
*
* @access public
*/
 function scan_3rddevice_devices(&$out) {
  require(DIR_MODULES.$this->name.'/3rddevice_devices_scan.inc.php');
 }


/**
* ssdp_devices scan
*
* @access public
*/
 function update_methods(&$out) {
  require(DIR_MODULES.$this->name.'/update_methods.inc.php');
 }


/**
* get local IP 
*
* @access public
*/
function getLocalIp() { 
  $s = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
  socket_connect($s ,'8.8.8.8', 53);  // connecting to a UDP address doesn't send packets
  socket_getsockname($s, $local_ip_address, $port);
  @socket_shutdown($s, 2);
  socket_close($s);
  
  return $local_ip_address; 
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
    $ssdpdevice=SQLSelectOne("SELECT * FROM ssdp_devices WHERE ID='".$id."'");
    $device_type=$ssdpdevice['TYPE']; // тип устройства (см выше допустимые типы) 

    // zagruzhaem structuru ustroystva
    $this->loadStructureForDevice($device_type);

    // podkluchaem prostie ustroystva i sozdaem ego
    include_once (DIR_MODULES.'devices/devices.class.php');
    $dev=new devices();
    $dev->renderStructure(); 
    $options=array(); // опции добавления
    $options['TABLE'] = 'ssdp_devices'; // таблица, куда потом запишется LINKED_OBJECT и LINKED_PROPERTY
    $options['TABLE_ID'] = $id; // ID записи в вышеназванной таблице (запись уже должна быть создана такая)
    $options['TITLE'] = $ssdpdevice['TITLE']; // название устройства (не обязательно)
    $options['LOCATION_ID']=$ssdpdevice['LOCATION']; // ID расположения (не обязательно)
    //$options['ADD_MENU']=1; // добавлять интерфейс работы с устройством в  меню (не обязательно)
    //$options['ADD_SCENE']=1; // добавлять интерфейс работы с устройством на сцену (не обязательно)
    //$result=$dev->addDevice($device_type, $options); // добавляем устройство -- возвращает 1 в случае успешного добавления
    
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
     
    // loaded the drivers for added device
    $this->loadDrivers($device_type);

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
                   $pval['LINKED_MODULES'] = 'SSDPFinder';
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
        $pval['LINKED_MODULES'] = 'SSDPFinder';
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
    $pval['LINKED_MODULES'] = 'SSDPFinder';
    $pval=SQLInsert('pvalues', $pval);
 
    //add the groupEcoOn in properties object
    $props = SQLSelectOne("SELECT * FROM properties WHERE TITLE LIKE 'groupEcoOn' AND CLASS_ID='".$clasofdevice['ID']."'");
    $pval = Array();
    $pval['PROPERTY_ID'] = $props['ID'];
    $pval['OBJECT_ID'] = $obj_id;
    $pval['VALUE'] = '0';
    $pval['PROPERTY_NAME'] = $obj_title.".groupEcoOn";
    $pval['UPDATED'] = date('Y-m-d H:i:s');
    $pval['LINKED_MODULES'] = 'SSDPFinder';
    $pval=SQLInsert('pvalues', $pval);
 
    //add the groupSunrise in properties object
    $props = SQLSelectOne("SELECT * FROM properties WHERE TITLE LIKE 'groupSunrise' AND CLASS_ID='".$clasofdevice['ID']."'");
    $pval = Array();
    $pval['PROPERTY_ID'] = $props['ID'];
    $pval['OBJECT_ID'] = $obj_id;
    $pval['VALUE'] = '0';
    $pval['PROPERTY_NAME'] = $obj_title.".groupSunrise";
    $pval['UPDATED'] = date('Y-m-d H:i:s');
    $pval['LINKED_MODULES'] = 'SSDPFinder';
    $pval=SQLInsert('pvalues', $pval);
 
    //add the isActivity in properties object
    $props = SQLSelectOne("SELECT * FROM properties WHERE TITLE LIKE 'isActivity' AND CLASS_ID='".$clasofdevice['ID']."'");
    $pval = Array();
    $pval['PROPERTY_ID'] = $props['ID'];
    $pval['OBJECT_ID'] = $obj_id;
    $pval['VALUE'] = '0';
    $pval['PROPERTY_NAME'] = $obj_title.".isActivity";
    $pval['UPDATED'] = date('Y-m-d H:i:s');
    $pval['LINKED_MODULES'] = 'SSDPFinder';
    $pval=SQLInsert('pvalues', $pval);
}

/**
* load structure for devices
*
* @access public
*/
function loadStructureForDevice($device_type){

    // записываем structure in addons для устройства
    if (!file_exists(ROOT.'/modules/devices/addons/SSDPFinder_'.$device_type.'_structure.php')) {
        // Открываем файл для получения существующего содержимого
        $current = file_get_contents('https://raw.githubusercontent.com/tarasfrompir/SSDPDrivers/master/modules/devices/addons/SSDPFinder_'.$device_type.'_structure.php');
        file_put_contents(ROOT.'/modules/devices/addons/SSDPFinder_'.$device_type.'_structure.php', $current);
        }
    return  true;
}


/**
* load drivers for devices
*
* @access public
*/
function loadDrivers($device_type){

    // записываем шаблон для устройства
    if (!file_exists(ROOT.'/templates/classes/views/S'.$device_type.'.html')) {
        $current = file_get_contents('https://raw.githubusercontent.com/tarasfrompir/SSDPDrivers/master/templates/classes/views/S'.$device_type.'.html');
        file_put_contents(ROOT.'/templates/classes/views/S'.$device_type.'.html', $current);
        }

    // записываем methods для устройства
    $device = SQLSelectOne("SELECT * FROM classes WHERE TITLE LIKE 'S".$device_type."'");
    $methods = SQLSelect("SELECT * FROM methods WHERE CLASS_ID='".$device['ID']."'");
    foreach ($methods as $method) {
        $current = file_get_contents('https://raw.githubusercontent.com/tarasfrompir/SSDPDrivers/master/modules/devices/S'.$device_type.'_'.$method['TITLE'].'.php');
        file_put_contents(ROOT.'/modules/devices/S'.$device_type.'_'.$method['TITLE'].'.php', $current);
        };

    // записываем управляющий класс для устройства
    if (!file_exists(ROOT.'/modules/ssdp_finder/upnp/vendor/jalder/upnp/src/'.$device_type.'.php')) {
        $current = file_get_contents('https://raw.githubusercontent.com/tarasfrompir/SSDPDrivers/master/modules/ssdp_finder/upnp/vendor/jalder/upnp/src/'.$device_type.'.php');
        file_put_contents(ROOT.'/modules/ssdp_finder/upnp/vendor/jalder/upnp/src/'.$device_type.'.php', $current);
        }
    if (!file_exists(ROOT.'/modules/ssdp_finder/upnp/vendor/jalder/upnp/src/'.$device_type)){
        mkdir(ROOT.'/modules/ssdp_finder/upnp/vendor/jalder/upnp/src/'.$device_type, 0777);
        }
    if (!file_exists(ROOT.'/modules/ssdp_finder/upnp/vendor/jalder/upnp/src/'.$device_type.'/Remote.php')) {
        $current = file_get_contents('https://raw.githubusercontent.com/tarasfrompir/SSDPDrivers/master/modules/ssdp_finder/upnp/vendor/jalder/upnp/src/'.$device_type.'/Remote.php');
        file_put_contents(ROOT.'/modules/ssdp_finder/upnp/vendor/jalder/upnp/src/'.$device_type.'/Remote.php', $current);
        };

    for ($i = 1; $i <= 10; $i++) {
        if (!file_exists(ROOT.'/modules/ssdp_finder/upnp/vendor/jalder/upnp/src/'.$device_type.'/Remote'.$i.'.php')) {
            $current = file_get_contents('https://raw.githubusercontent.com/tarasfrompir/SSDPDrivers/master/modules/ssdp_finder/upnp/vendor/jalder/upnp/src/'.$device_type.'/Remote'.$i.'.php');
            if (!$current) {
                break;
                };
            file_put_contents(ROOT.'/modules/ssdp_finder/upnp/vendor/jalder/upnp/src/'.$device_type.'/Remote'.$i.'.php', $current);
            };
        };
    return  true;
}


/**
* get ip from url
*
* @access public
*/
function clear_trash() {
	require(DIR_MODULES.$this->name.'/ssdp_devices_clear_trash.inc.php');
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
  $terminal['HOST'] = $this->getIp($ssdpdevice['CONTROLADDRESS'],false);
  $terminal['CANPLAY'] = '1';

  DebMes ($ssdpdevice['LINKED_OBJECT']);
  if (strpos($ssdpdevice['LINKED_OBJECT'], 'chromecast')) {
    $terminal['PLAYER_TYPE'] = 'chromecast';
  } else {
    $terminal['PLAYER_TYPE'] = 'dnla';
  }
  $terminal['PLAYER_PORT'] = $this->getPort($ssdpdevice['CONTROLADDRESS']);
  $terminal['PLAYER_CONTROL_ADDRESS'] = $ssdpdevice['CONTROLADDRESS'];
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
  $pinghosts['HOSTNAME'] = $this->getIp($ssdpdevice['CONTROLADDRESS'],false);
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
* ssdp_devices delete device
*
* @access public
*/
function delete_ssdp_devices($id) {
    $rec=SQLSelectOne("SELECT * FROM ssdp_devices WHERE ID='$id'");
    $this->deleteDrivers($rec['TYPE']);
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
        // delete fromp tables classes
        SQLExec("DELETE FROM classes WHERE TITLE='S".$rec['TYPE']."'");
        }
    }


/**
* delete drivers for devices
*
* @access public
*/
function deleteDrivers($device_type){
    //выбираем количество устройств из базы данных по одному типу
    $devices = SQLSelect("SELECT * FROM ssdp_devices WHERE TYPE LIKE '".$device_type."'");
    // проверяем на присутствие еще такого устройства для определения необходимости удаления драйверов устройства
    if (count($devices)==1 and $device_type){
        // удаляем методы устройства
        $device = SQLSelectOne("SELECT * FROM classes WHERE TITLE LIKE 'S".$device_type."'");
        $methods = SQLSelect("SELECT * FROM methods WHERE CLASS_ID='".$device['ID']."'");
        foreach ($methods as $method) {
            //удаляем методы из мажордомо
            if ($method['TITLE'] and file_exists(ROOT.'/modules/devices/S'.$device_type.'_'.$method['TITLE'].'.php')) {
                unlink(ROOT.'/modules/devices/S'.$device_type.'_'.$method['TITLE'].'.php');
                };
            if ($method['TITLE'] and $device_type and $device['PARENT_ID']!=0) {// удаляем из базы записи о методах
               DebMes ('Beda udalyaem pustie metodi');
                SQLExec("DELETE FROM methods WHERE TITLE='".$method['TITLE']."'");
		        };
            };
        // удаляем шаблон для устройства
        if ($device_type and file_exists(ROOT.'/templates/classes/views/S'.$device_type.'.html')) {
            unlink(ROOT.'/templates/classes/views/S'.$device_type.'.html');
            };
        // удаляем управляющий класс для устройства
        if ($device_type and file_exists(ROOT.'/modules/ssdp_finder/upnp/vendor/jalder/upnp/src/'.$device_type.'.php')) {
            unlink(ROOT.'/modules/ssdp_finder/upnp/vendor/jalder/upnp/src/'.$device_type.'.php');
            };
        if ($device_type and file_exists(ROOT.'/modules/ssdp_finder/upnp/vendor/jalder/upnp/src/'.$device_type.'/Remote.php')) {
            unlink(ROOT.'/modules/ssdp_finder/upnp/vendor/jalder/upnp/src/'.$device_type.'/Remote.php');
            };
        for ($i = 1; $i <= 10; $i++) {
            if (!file_exists(ROOT.'/modules/ssdp_finder/upnp/vendor/jalder/upnp/src/'.$device_type.'/Remote'.$i.'.php')) {
                break;
                };
            unlink(ROOT.'/modules/ssdp_finder/upnp/vendor/jalder/upnp/src/'.$device_type.'/Remote'.$i.'.php');
            };
        // удаляем директорию для текущего устройства
        rmdir(ROOT.'/modules/ssdp_finder/upnp/vendor/jalder/upnp/src/'.$device_type);
        // удаляем structure in addons для устройства
        if ($device_type and file_exists(ROOT.'/modules/devices/addons/SSDPFinder_'.$device_type.'_structure.php')) {
             unlink(ROOT.'/modules/devices/addons/SSDPFinder_'.$device_type.'_structure.php');
            };
        };
    return  true;
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

function chek_update_drivers($curl='') {
  // проверяем присутсвые записей в устройствах и  если пусто то обновлять нету смыслы поскольку там пусто
  $table='ssdp_devices';
  $devices=SQLSelectOne("SELECT * FROM ssdp_devices WHERE `ID` IS NOT NULL");
  if (!$devices['ID']) { return;}
  
  $url = 'https://api.github.com/repos/tarasfrompir/SSDPDrivers/commits';
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $content = curl_exec($ch);
  if ($content === false) {
    // netu interneta
	return; 
  };
  $pos = strripos($content, '[ { "sha": "');
  $answer = substr($content, $pos+18, 40);
  // это файл в котором содержится последнее обновление
  $file = (ROOT.'/modules/ssdp_finder/timestamp.date');
  // проверяем на соответсвие даты создания файла гита  и файла проверки в модуле
  if (file_exists($file)) {
    // Открываем файл для получения существующего содержимого
    $current = file_get_contents($file);
    if ($current==$answer) {
        return;
        } else {
        return 1;
        };

    } else {
       return 1;
    };
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
    } else if (file_exists(ROOT.'/cms/cached/voice/rh_' . md5($message) . '.mp3')) {
        $cached_filename = 'http://'. $ipadressserver . '/cms/cached/voice/rh_' . md5($message) . '.mp3';
    } else {
        //DebMes($details);
        $level = $details['level'];
        $message = $details['message'];
    //  $accessKey = $this->config['ACCESS_KEY'];
    //  $speaker = $this->config['SPEAKER'];
        $filename = md5($message) . '_google.mp3';
        $cachedVoiceDir = ROOT . 'cms/cached/voice';
        $cachedFileName = $cachedVoiceDir . '/' . $filename;
        $base_url = 'https://translate.google.com/translate_tts?';
        $lang = SETTINGS_SITE_LANGUAGE;
        if ($lang == 'ua') {
            $lang = 'uk';
        }else if ($lang == 'ru') {
	        $lang = 'ru';
        }else {
	        $lang = 'en';
        }
        $qs = http_build_query([
            'ie' => 'UTF-8',
            'client' => 'tw-ob',
            'q' => $message,
            'tl' => $lang,
            //'ttsspeed' => 1 // 0-4
            //'speaker' => $speaker,
            //'key' => $accessKey,
            ]);
        //DebMes($base_url . $qs);
        try {
            $contents = file_get_contents($base_url . $qs);
        } catch (Exception $e) {
            registerError('ssdp_finder', get_class($e) . ', ' . $e->getMessage());
        }
        if (isset($contents)) {
            CreateDir($cachedVoiceDir);
            SaveFile($cachedFileName, $contents);
        }
        $cached_filename = 'http://'. $ipadressserver . '/cms/cached/voice/' . md5($message) . '_google.mp3';
	}
    $usedsay=SQLSelect("SELECT * FROM ssdp_devices WHERE USE_TO_SAY='".'1'."'");
    foreach ($usedsay as $saydev) {
        if ($saydev['TYPE']=='MediaRenderer' AND $saydev['USE_TO_SAY']=='1' AND $levelmes>=$level) {
          setGlobal($saydev['LINKED_OBJECT'].'.playUrl', $cached_filename);
        }
    }
}
if ($event=='SAYTO') {
    if($this->debug == 1) debmes('mpt sayto start');
    $level=$details['level'];
    $message=$details['message'];
    $target = $details['destination'];
    $levelmes = getGlobal('ThisComputer.minMsgLevel');
    $ipadressserver = $this->getLocalIp();
    if (file_exists(ROOT.'/cms/cached/voice/' . md5($message) . '_google.mp3')) {
        $cached_filename = 'http://'. $ipadressserver . '/cms/cached/voice/' . md5($message) . '_google.mp3';
    } else if (file_exists(ROOT.'/cms/cached/voice/' . md5($message) . '_yandex.mp3')) {
        $cached_filename = 'http://'. $ipadressserver . '/cms/cached/voice/' . md5($message) . '_yandex.mp3';
    } else if (file_exists(ROOT.'/cms/cached/voice/rh_' . md5($message) . '.mp3')) {
        $cached_filename = 'http://'. $ipadressserver . '/cms/cached/voice/rh_' . md5($message) . '.mp3';
    } else {
        //DebMes($details);
        $level = $details['level'];
        $message = $details['message'];
    //  $accessKey = $this->config['ACCESS_KEY'];
    //  $speaker = $this->config['SPEAKER'];
        $filename = md5($message) . '_google.mp3';
        $cachedVoiceDir = ROOT . 'cms/cached/voice';
        $cachedFileName = $cachedVoiceDir . '/' . $filename;
        $base_url = 'https://translate.google.com/translate_tts?';
        $lang = SETTINGS_SITE_LANGUAGE;
        if ($lang == 'ua') {
            $lang = 'uk';
        }else if ($lang == 'ru') {
	        $lang = 'ru';
        }else {
	        $lang = 'en';
        }
        $qs = http_build_query([
            'ie' => 'UTF-8',
            'client' => 'tw-ob',
            'q' => $message,
            'tl' => $lang,
            //'ttsspeed' => 1 // 0-4
            //'speaker' => $speaker,
            //'key' => $accessKey,
            ]);
        //DebMes($base_url . $qs);
        try {
            $contents = file_get_contents($base_url . $qs);
        } catch (Exception $e) {
            registerError('ssdp_finder', get_class($e) . ', ' . $e->getMessage());
        }
        if (isset($contents)) {
            CreateDir($cachedVoiceDir);
            SaveFile($cachedFileName, $contents);
        }
        $cached_filename = 'http://'. $ipadressserver . '/cms/cached/voice/' . md5($message) . '_google.mp3';
	    }
    setGlobal($target.'.playUrl', $cached_filename);
    }
	
	
/* if ($event=='ASK') {
   $tartget = $this->targetToIp($details['target']);
   if(!$target) return 0;
   $message=$details['prompt'];
   $this->send_mpt('ask', $message, $target);
   if($this->debug == 1) debmes('mpt ask ' . $message . '; target = ' . $target);
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

  // удаляем structure in addons для устройства
  if (!file_exists(ROOT.'/modules/devices/addons/SSDPFinder_structure.php')) {
    unlink(ROOT.'/modules/devices/addons/SSDPFinder_structure.php');
    }


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
 ssdp_devices: LOGO longtext NOT NULL DEFAULT ''
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
