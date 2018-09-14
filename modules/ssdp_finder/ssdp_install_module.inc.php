<?php
/*
* @version 0.1 (wizard) скрипт установки модуля и записи данных об устройстве в базу ssdp_devices
*/

// install module for device
  //имя модуля для этого устройства
  global $namemodule;
  // установлен ли этот модуль
  global $installed_module;
  // проверяем на наличие модуля
  if (!$installed_module) {
    // если нету то устанавливаем модуль по названию которое находится в переменной $namemodule
    include_once (DIR_MODULES.'market/market.class.php');
    $mkt=new market();
    $result = $mkt->marketRequest();
    $data=json_decode($result, TRUE);
    foreach ( $data['PLUGINS'] as $plug_number ) {
      if ( $plug_number['TITLE'] == $namemodule ){
        $url= $plug_number['REPOSITORY_URL'];
        $name = $plug_number['MODULE_NAME'];
        $version = $plug_number['LATEST_VERSION'];
        break;
      };
    };
    //загружаем модуль в папку сайвресторе
    if (!is_dir(ROOT . 'cms/saverestore')) {
        @umask(0);
        @mkdir(ROOT . 'cms/saverestore', 0777);
    }
    umask(0);
    @mkdir(ROOT . 'cms/saverestore/temp', 0777);
    
    $filename=ROOT.'cms/saverestore/'.$name.'.tgz';
    @unlink(ROOT.'cms/saverestore/'.$name.'.tgz');
    @unlink(ROOT.'cms/saverestore/'.$name.'.tar');
    $f = fopen($filename, 'wb');
    DebMes("Downloading plugin $name ($version) from $url");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 600);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FILE, $f);
    $incoming = curl_exec($ch);
    curl_close($ch);
    @fclose($f);

// procedura ustanovki modulya
    if (file_exists($filename)) {
        $file = basename($filename);
        DebMes("Installing/updating plugin $name ($version) $file");
        chdir(ROOT . 'cms/saverestore/temp');
        if (IsWindowsOS()) {
          //DebMes("Running ".DOC_ROOT.'/gunzip ../'.$file);
          exec(DOC_ROOT . '/gunzip ../' . $file, $output, $res);
          //DebMes("Running ".DOC_ROOT.'/tar xvf ../'.str_replace('.tgz', '.tar', $file));
          exec(DOC_ROOT . '/tar xvf ../' . str_replace('.tgz', '.tar', $file), $output, $res);
        } else {
          exec('tar xzvf ../' . $file, $output, $res);
        }
        $x = 0;
        $latest_dir = '';
        $latest_file = '';
        $dir = opendir('./');
        while (($filec = readdir($dir)) !== false) {
          if ($filec == '.' || $filec == '..') {
            continue;
          }
          if (is_Dir($filec)) {
            $latest_dir = $filec;
          } elseif (is_File($filec)) {
            $latest_file = $filec;
          }
          $x++;
        }
        if ($x == 1 && $latest_dir) {
          $folder = '/' . $latest_dir;
        }
        chdir('../../');
        DebMes("Latest folder: $latest_dir");             
        $mkt->installUnpacketPlugin(ROOT . 'cms/saverestore/temp' . $folder, $name);
        // zapisivaem chto modul ustanovlen
        $rec = SQLSelectOne("SELECT * FROM plugins WHERE MODULE_NAME LIKE '" . DBSafe($name) . "'");
        $rec['MODULE_NAME'] = $name;
        $rec['CURRENT_VERSION'] = $version;
        $rec['IS_INSTALLED'] = 1;
        $rec['LATEST_UPDATE'] = date('Y-m-d H:i:s');
        if ($rec['ID']) {
          SQLUpdate('plugins', $rec);
        } else {
          SQLInsert('plugins', $rec);
        }
        //berem dannie s modulya
        $module_data = file_get_contents(ROOT.'modules/'.$name."/".$name.".class.php");
        $module_data = substr($module_data, 1, 800);
        // berem imya modulya
        $start_pos = stripos($module_data, '$this->name="');
        $module_data = substr($module_data, $start_pos+13);
        $end_pos = stripos($module_data, '";');
        $module_name = substr($module_data, 0, $end_pos);        
        DebMes ("Имя Модуля ".$module_name);
        $module_data = substr($module_data, $end_pos);

        // berem title modulya
        $start_pos = stripos($module_data, '$this->title="');
        $module_data = substr($module_data, $start_pos+14);
        $end_pos = stripos($module_data, '";');
        $module_title = substr($module_data, 0, $end_pos);        
        DebMes ("Описание модуля ".$module_title);
        $module_data = substr($module_data, $end_pos);

        // berem category modulya
        $start_pos = stripos($module_data, '$this->module_category="');
        $module_data = substr($module_data, $start_pos+24);
        $end_pos = stripos($module_data, '";');
        $module_category = substr($module_data, 0, $end_pos);        
        DebMes ("Категория модуля ".$module_category);

        // заполняем данные о модуле
        $rec = SQLSelectOne("SELECT * FROM project_modules WHERE NAME LIKE '" . DBSafe($name) . "'");
        $rec['NAME'] = $module_name;
        $rec['TITLE'] = $module_title;
        $rec['CATEGORY'] = $module_category;
        $rec['HIDDEN'] = 0;
        $rec['PRIORITY'] = 0;
        $rec['ADDED'] = date('Y-m-d H:i:s');
        if ($rec['ID']) {
          SQLUpdate('project_modules', $rec);
        } else {
          SQLInsert('project_modules', $rec);
        }

    }
    @rmdir(ROOT.'cms/saverestore/temp/');
    
// konec ustanovki modulya
}  

// добавление устройства в таблицу ssdp_devices
  $table_name='ssdp_devices';
  $rec=SQLSelectOne("SELECT * FROM $table_name WHERE ID='$id'");
   global $session;
  //updating '<%LANG_TITLE%>' (varchar, required)
   global $title;
   $rec['TITLE']=$title;
   //updating 'controladdress' (varchar)
   global $controladdress;
   $rec['CONTROLADDRESS']=$controladdress;
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
   $rec['LOGO']=$_SESSION[$uuid];
   //updating '<%LANG_LINKED_OBJECT%>' (varchar)
   global $namemodule;
   $rec['LINKED_OBJECT']="Модуль ".$namemodule;
   //updating '<%LANG_METHOD%>' (varchar)
   global $linked_method;
   $rec['LINKED_METHOD']=$linked_method;
   //updating '<%LANG_UPDATED%>' (datetime)
   global $updated_date;
   global $updated_minutes;
   global $updated_hours;
   $rec['USE_TO_SAY']=0;
   $rec['UPDATED']=toDBDate($updated_date)." $updated_hours:$updated_minutes:00";
   if ($rec['ID']) {
      SQLUpdate($table_name, $rec); // update
    } else {
       $new_rec=1;
       $rec['ID']=SQLInsert($table_name, $rec); // adding new record
    }
    $out['OK']=1;
    // после сохранения устройства переходим на основную страницу 
    $this->redirect("?");
