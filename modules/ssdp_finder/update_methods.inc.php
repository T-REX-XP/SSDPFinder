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

  $url = 'https://api.github.com/repos/tarasfrompir/SSDPDrivers/commits';
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $content = curl_exec($ch);
  if ($content === false) {
      die (curl_error($ch)); 
  };
  $pos = strripos($content, '[ { "sha": "');
  $answer = substr($content, $pos+18, 40);
  // это файл в котором содержится последнее обновление
  $file = (ROOT.'/modules/ssdp_finder/timestamp.date');
  // Пишем содержимое обратно в файл
  file_put_contents($file, $answer);
