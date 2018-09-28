<?php
/**
 * English language file
 *
 * @package MajorDoMo
 * @author Serge Dzheigalo <jey@tut.by> http://smartliving.ru/
 * @version 1.0
 */



$dictionary = array (
   'SSDP_TITLE' => 'Search and add deices to system devices on the UPNP protocol.',
   'SSDP_PORT' => 'Port for answer from UPNP devices',
   'SSDP_NAME' => 'Name:',
   'SSDP_TYPE' => 'Type:',
   'SSDP_SERVICETYPE' => 'Service Type:',
   'SSDP_IPADRESS' => 'IP adress:',
   'SSDP_IDESCRIPTION' => 'Description:',
   'SSDP_MODEL' => 'Model:',
   'SSDP_MANUFACTURER' => 'Manufacturer:',
   'SSDP_CREATESD' => 'Create simple device',
   'SSDP_HOSTONLINE' => 'Create hosts online',
   'SSDP_TERMINAL' => 'Create terminal',
   'SSDP_USETOSYSTEMMESAGE' => 'Use to play system mesage',
   'SSDP_CLEARMODULE' => 'Clear all devices and trash from module',
   'SSDP_UPDATE_METHODS_SHABLON' => 'Update methods & shablons',
   'SSDP_SCAN_UPNP_DEVICE' => 'Scan UPNP devices',
   'SSDP_SCAN_3DDEVICE_DEVICE' => 'Scan ALL devices',
);

foreach ($dictionary as $k => $v) {
    if (!defined('LANG_' . $k)) {
        define('LANG_' . $k, $v);
    }
}
