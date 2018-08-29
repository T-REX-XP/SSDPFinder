<?php
/*
* @version 0.1 (wizard)
*/

  // delete all SSDP devices from majordomo
  $rec=SQLSelect("SELECT ID FROM ssdp_devices");
  foreach ($rec as $id) {
    $rec_type=SQLSelectOne("SELECT * FROM ssdp_devices WHERE ID='".$id['ID']."'");
    $device_type = $rec_type['TYPE'];
    // записываем шаблон для устройства
    $current = file_get_contents('https://raw.githubusercontent.com/tarasfrompir/SSDPDrivers/master/templates/classes/views/S'.$device_type.'.html');
    file_put_contents(ROOT.'/templates/classes/views/S'.$device_type.'.html', $current);


    // записываем methods для устройства
    $device = SQLSelectOne("SELECT * FROM classes WHERE TITLE LIKE 'S".$device_type."'");
    $methods = SQLSelect("SELECT * FROM methods WHERE CLASS_ID='".$device['ID']."'");
    foreach ($methods as $method) {
        $current = file_get_contents('https://raw.githubusercontent.com/tarasfrompir/SSDPDrivers/master/modules/devices/S'.$device_type.'_'.$method['TITLE'].'.php');
        file_put_contents(ROOT.'/modules/devices/S'.$device_type.'_'.$method['TITLE'].'.php', $current);
        };

    // записываем управляющий класс для устройства
    $current = file_get_contents('https://raw.githubusercontent.com/tarasfrompir/SSDPDrivers/master/modules/ssdp_finder/upnp/vendor/jalder/upnp/src/'.$device_type.'.php');
    file_put_contents(ROOT.'/modules/ssdp_finder/upnp/vendor/jalder/upnp/src/'.$device_type.'.php', $current);

    if (!file_exists(ROOT.'/modules/ssdp_finder/upnp/vendor/jalder/upnp/src/'.$device_type)){
        mkdir(ROOT.'/modules/ssdp_finder/upnp/vendor/jalder/upnp/src/'.$device_type, 0777);
        }
    $current = file_get_contents('https://raw.githubusercontent.com/tarasfrompir/SSDPDrivers/master/modules/ssdp_finder/upnp/vendor/jalder/upnp/src/'.$device_type.'/Remote.php');
    file_put_contents(ROOT.'/modules/ssdp_finder/upnp/vendor/jalder/upnp/src/'.$device_type.'/Remote.php', $current);
	for ($i = 1; $i <= 10; $i++) {
        $current = file_get_contents('https://raw.githubusercontent.com/tarasfrompir/SSDPDrivers/master/modules/ssdp_finder/upnp/vendor/jalder/upnp/src/'.$device_type.'/Remote'.$i.'.php');
        if (!$current) {
            break;
            };
        file_put_contents(ROOT.'/modules/ssdp_finder/upnp/vendor/jalder/upnp/src/'.$device_type.'/Remote'.$i.'.php', $current);
        };
  };

  $curl = curl_init('http://github.com/tarasfrompir/SSDPDrivers.git');
  // получаем время создания файла гитхаба ссылка выше
  curl_setopt($curl, CURLOPT_NOBODY, true);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_FILETIME, true);
  $result = curl_exec($curl);
  if ($result === false) {
      die (curl_error($curl)); 
  };
  $timestamp = curl_getinfo($curl, CURLINFO_FILETIME);

  // это файл в котором содержится последнее обновление
  $file = (ROOT.'/modules/ssdp_finder/timestamp.php');
  // Пишем содержимое обратно в файл
  file_put_contents($file, $timestamp);
