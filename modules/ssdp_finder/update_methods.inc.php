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
}
