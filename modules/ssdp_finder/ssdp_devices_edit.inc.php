<?php
/*
* @version 0.1 (wizard)
*/
  if ($this->owner->name=='panel') {
   $out['CONTROLPANEL']=1;
  }
  $table_name='ssdp_devices';
  $rec=SQLSelectOne("SELECT * FROM $table_name WHERE ID='$id'");
  if ($this->mode=='update') {
   $ok=1;
  //updating '<%LANG_TITLE%>' (varchar, required)
   global $title;
   $rec['TITLE']=$title;
   if ($rec['TITLE']=='') {
    $out['ERR_TITLE']=1;
    $ok=0;
   }

   global $create_sd;
   global $create_od;
   //updating 'Uuid' (varchar)
   global $uuid;
   $rec['UUID']=$uuid;
   //updating 'TITLE' (varchar)
   global $title;
   $rec['TITLE']=$title;
  //updating 'Type' (varchar)
   global $type;
   $rec['TYPE']=$type;
  //updating 'services' (varchar)
   global $services;
   $rec['SERVICES']=$services;
   //updating 'Address' (varchar)
   global $address;
   $rec['ADDRESS']=$address;
   $rec['IP']=$address;
   global $name;
   $rec['NAME']=$title;
  //updating 'Description' (varchar)
   global $description;
   $rec['DESCRIPTION']=$description;
  //updating 'Model' (varchar)
   global $model;
   $rec['MODEL']=$model;
  //updating 'Manufacturer' (varchar)
   global $manufacturer;
   $rec['MANUFACTURER']=$manufacturer;
  //updating 'location' (varchar)
   global $location;
   $rec['LOCATION']=$location;
 //updating 'Logo' (varchar)
   global $logo;
   $rec['LOGO']=$logo;
  //updating '<%LANG_LINKED_OBJECT%>' (varchar)
   global $linked_object;
   $rec['LINKED_OBJECT']=$linked_object;
  //updating '<%LANG_LINKED_PROPERTY%>' (varchar)
   global $linked_property;
   $rec['LINKED_PROPERTY']=$linked_property;
  //updating '<%LANG_METHOD%>' (varchar)
   global $linked_method;
   $rec['LINKED_METHOD']=$linked_method;
  //updating '<%LANG_UPDATED%>' (datetime)
   global $updated_date;
   global $updated_minutes;
   global $updated_hours;
   $rec['UPDATED']=toDBDate($updated_date)." $updated_hours:$updated_minutes:00";
  //UPDATING RECORD
   if ($ok) {
    if ($rec['ID']) {
     SQLUpdate($table_name, $rec); // update
    } else {
     $new_rec=1;
     $rec['ID']=SQLInsert($table_name, $rec); // adding new record

     // moya dobavka автодобавление устройств в онлайн и простые устройства
     if($create_sd==true){
      $this->add_to_SSDPdevices($rec['ID']);
     }
     if($create_od==true){
      $this->add_to_pinghost($rec['ID']);
     }
    }
    $out['OK']=1;
   } else {
    $out['ERR']=1;
   }
  }
  if ($this->mode=='add') {
    $ok=0;
   //updating '<%LANG_TITLE%>' (varchar, required)
    global $title;
    $rec['TITLE']=$title;
    if ($rec['TITLE']=='') {
     $out['ERR_TITLE']=1;
     $ok=0;
    }
   //updating 'Uuid' (varchar)
   global $uuid;
   $rec['UUID']=$uuid;
   //updating 'TITLE' (varchar)
   global $title;
   $rec['TITLE']=$title;
  //updating 'Type' (varchar)
   global $type;
   $rec['TYPE']=$type;
  //updating 'services' (varchar)
   global $services;
   $rec['SERVICES']=$services;
  //updating 'Model' (varchar)
   global $model;
   $rec['MODEL']=$model;
  //updating 'Manufacturer' (varchar)
   global $manufacturer;
   $rec['MANUFACTURER']=$manufacturer;
    //updating 'Address' (varchar)
   global $address;
   $rec['ADDRESS']=$address;
//   $rec['IP']=$address;
   global $name;
   $rec['NAME']=$title;
  //updating 'Description' (varchar)
   global $description;
   $rec['DESCRIPTION']=$description;
  //updating 'Location' (varchar)
   global $location;
   $rec['LOCATION']=$location;
 //updating 'Logo' (varchar)
   global $logo;
   $rec['LOGO']=$logo;
  //updating '<%LANG_LINKED_OBJECT%>' (varchar)
   global $linked_object;
   $rec['LINKED_OBJECT']=$linked_object;
  //updating '<%LANG_LINKED_PROPERTY%>' (varchar)
   global $linked_property;
   $rec['LINKED_PROPERTY']=$linked_property;
  //updating '<%LANG_METHOD%>' (varchar)
   global $linked_method;
   $rec['LINKED_METHOD']=$linked_method;
  //updating '<%LANG_UPDATED%>' (datetime)
   global $updated_date;
   global $updated_minutes;
   global $updated_hours;
    $rec['UPDATED']=toDBDate($updated_date)." $updated_hours:$updated_minutes:00";
   //UPDATING RECORD
    if ($ok) {
     if ($rec['ID']) {
      SQLUpdate($table_name, $rec); // update
     } else {
      $new_rec=1;
      $rec['ID']=SQLInsert($table_name, $rec); // adding new record
     }
     $out['OK']=1;
    } else {
     $out['ERR']=1;
    }
   }
 

  if ($rec['UPDATED']!='') {
   $tmp=explode(' ', $rec['UPDATED']);
   $out['UPDATED_DATE']=fromDBDate($tmp[0]);
   $tmp2=explode(':', $tmp[1]);
   $updated_hours=$tmp2[0];
   $updated_minutes=$tmp2[1];
  }
  for($i=0;$i<60;$i++) {
   $title=$i;
   if ($i<10) $title="0$i";
   if ($title==$updated_minutes) {
    $out['UPDATED_MINUTES'][]=array('TITLE'=>$title, 'SELECTED'=>1);
   } else {
    $out['UPDATED_MINUTES'][]=array('TITLE'=>$title);
   }
  }
  for($i=0;$i<24;$i++) {
   $title=$i;
   if ($i<10) $title="0$i";
   if ($title==$updated_hours) {
    $out['UPDATED_HOURS'][]=array('TITLE'=>$title, 'SELECTED'=>1);
   } else {
    $out['UPDATED_HOURS'][]=array('TITLE'=>$title);
   }
  }
  if (is_array($rec)) {
   foreach($rec as $k=>$v) {
    if (!is_array($v)) {
     $rec[$k]=htmlspecialchars($v);
    }
   }
  }
  outHash($rec, $out);
$out['LOCATIONS']=SQLSelect("SELECT ID, TITLE FROM locations ORDER BY TITLE");