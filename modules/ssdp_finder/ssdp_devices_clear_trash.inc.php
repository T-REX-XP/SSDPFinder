<?php
/*
* @version 0.1 (wizard)
*/
error_reporting( E_ERROR );

// delete all SSDP devices from majordomo
$rec=SQLSelect("SELECT ID FROM ssdp_devices");
foreach ($rec as $id) {
    $rec=SQLSelectOne("SELECT * FROM ssdp_devices WHERE ID='".$id['ID']."'");
    $this->deleteDrivers($rec['TYPE']);
	SQLExec("DELETE FROM ssdp_devices WHERE ID='".$id['ID']."'"); 
    if($rec['LINKED_OBJECT']) {
        /// delete from simple device
        $sdev_del=SQLSelectOne("SELECT * FROM devices WHERE LINKED_OBJECT='".$rec['LINKED_OBJECT']."'");
        $sdevice = $sdev_del['ID'];
        include_once (DIR_MODULES.'devices/devices.class.php');
        $dev=new devices();
        $dev->delete_devices($sdevice);
        SQLExec("DELETE FROM pinghosts WHERE LINKED_OBJECT='".$rec['LINKED_OBJECT']."'"); 
        SQLExec("DELETE FROM terminals WHERE LINKED_OBJECT='".$rec['LINKED_OBJECT']."'"); 
        SQLExec("DELETE FROM classes WHERE TITLE='S".$rec['TYPE']."'");
       }
}
// delete all included classes from database
SQLExec("DELETE FROM classes WHERE TITLE='SMediaServer'");
SQLExec("DELETE FROM classes WHERE TITLE='Sdial'");
SQLExec("DELETE FROM classes WHERE TITLE='SBasic'");
SQLExec("DELETE FROM classes WHERE TITLE='SDigitalSecurityCamera'");
SQLExec("DELETE FROM classes WHERE TITLE='SInternetGatewayDevice'");
SQLExec("DELETE FROM classes WHERE TITLE='SMediaRenderer'");
SQLExec("DELETE FROM classes WHERE TITLE='SBinaryLight'");
SQLExec("DELETE FROM classes WHERE TITLE='Scontrollee'");
SQLExec("DELETE FROM classes WHERE TITLE='SYeelightSmartBulb'");

// delete all simlpe devices from database
SQLExec("DELETE FROM devices WHERE TYPE='MediaServer'");
SQLExec("DELETE FROM devices WHERE TYPE='dial'");
SQLExec("DELETE FROM devices WHERE TYPE='Basic'");
SQLExec("DELETE FROM devices WHERE TYPE='DigitalSecurityCamera'");
SQLExec("DELETE FROM devices WHERE TYPE='InternetGatewayDevice'");
SQLExec("DELETE FROM devices WHERE TYPE='MediaRenderer'");
SQLExec("DELETE FROM devices WHERE TYPE='BinaryLight'");
SQLExec("DELETE FROM devices WHERE TYPE='controllee'");
SQLExec("DELETE FROM devices WHERE TYPE='YeelightSmartBulb'");

// delete all objects from database
SQLExec("DELETE FROM objects WHERE TITLE  LIKE 'MediaServer%'");
SQLExec("DELETE FROM objects WHERE TITLE  LIKE 'dial%'");
SQLExec("DELETE FROM objects WHERE TITLE  LIKE 'Basic%'");
SQLExec("DELETE FROM objects WHERE TITLE  LIKE 'DigitalSecurityCamera%'");
SQLExec("DELETE FROM objects WHERE TITLE  LIKE 'InternetGatewayDevice%'");
SQLExec("DELETE FROM objects WHERE TITLE  LIKE 'MediaRenderer%'");
SQLExec("DELETE FROM objects WHERE TITLE  LIKE 'BinaryLight%'");
SQLExec("DELETE FROM objects WHERE TITLE  LIKE 'controllee%'");
SQLExec("DELETE FROM objects WHERE TITLE  LIKE 'YeelightSmartBulb%'");

// delete all values from database
SQLExec("DELETE FROM pvalues WHERE PROPERTY_NAME  LIKE 'MediaServer%'");
SQLExec("DELETE FROM pvalues WHERE PROPERTY_NAME  LIKE 'dial%'");
SQLExec("DELETE FROM pvalues WHERE PROPERTY_NAME  LIKE 'Basic%'");
SQLExec("DELETE FROM pvalues WHERE PROPERTY_NAME  LIKE 'DigitalSecurityCamera%'");
SQLExec("DELETE FROM pvalues WHERE PROPERTY_NAME  LIKE 'InternetGatewayDevice%'");
SQLExec("DELETE FROM pvalues WHERE PROPERTY_NAME  LIKE 'MediaRenderer%'");
SQLExec("DELETE FROM pvalues WHERE PROPERTY_NAME  LIKE 'BinaryLight%'");
SQLExec("DELETE FROM pvalues WHERE PROPERTY_NAME  LIKE 'controllee%'");
SQLExec("DELETE FROM pvalues WHERE PROPERTY_NAME  LIKE 'YeelightSmartBulb%'");
SQLExec("DELETE FROM pvalues WHERE PROPERTY_NAME=''");

SQLExec("DELETE FROM cached_values WHERE KEYWORD  LIKE 'MJD:MediaServer%'");
SQLExec("DELETE FROM cached_values WHERE KEYWORD  LIKE 'MJD:dial%'");
SQLExec("DELETE FROM cached_values WHERE KEYWORD  LIKE 'MJD:Basic%'");
SQLExec("DELETE FROM cached_values WHERE KEYWORD  LIKE 'MJD:DigitalSecurityCamera%'");
SQLExec("DELETE FROM cached_values WHERE KEYWORD  LIKE 'MJD:InternetGatewayDevice%'");
SQLExec("DELETE FROM cached_values WHERE KEYWORD  LIKE 'MJD:MediaRenderer'%");
SQLExec("DELETE FROM cached_values WHERE KEYWORD  LIKE 'MJD:BinaryLight%'");
SQLExec("DELETE FROM cached_values WHERE KEYWORD  LIKE 'MJD:controllee%'");
SQLExec("DELETE FROM cached_values WHERE KEYWORD  LIKE 'MJD:YeelightSmartBulb%'");

// delete all filles from module

unlink(DIR_MODULES.'/devices/SYeelightSmartBulb_turnOn.php');
unlink(DIR_MODULES.'/devices/Scontrollee_changecolor.php');
unlink(DIR_MODULES.'/devices/Scontrollee_changetemp.php');
unlink(DIR_MODULES.'/devices/SMedaiserver_urllist.php');
unlink(DIR_MODULES.'/devices/SBinaryLight_switch.php');
unlink(DIR_MODULES.'/devices/SBinaryLight_turnOff.php');
unlink(DIR_MODULES.'/devices/SBinaryLight_turnOn.php');
unlink(DIR_MODULES.'/devices/SControllee_switch.php');
unlink(DIR_MODULES.'/devices/SControllee_turnOff.php');
unlink(DIR_MODULES.'/devices/SControllee_turnOn.php');
unlink(DIR_MODULES.'/devices/SMediaRenderer_mute-unmute.php');
unlink(DIR_MODULES.'/devices/SMediaRenderer_next.php');
unlink(DIR_MODULES.'/devices/SMediaRenderer_pause-unpause.php');
unlink(DIR_MODULES.'/devices/SMediaRenderer_playNextUrl.php');
unlink(DIR_MODULES.'/devices/SMediaRenderer_playUrl.php');
unlink(DIR_MODULES.'/devices/SMediaRenderer_previous.php');
unlink(DIR_MODULES.'/devices/SMediaRenderer_seeknext.php');
unlink(DIR_MODULES.'/devices/SMediaRenderer_seekprevious.php');
unlink(DIR_MODULES.'/devices/SMediaRenderer_stop.php');
unlink(DIR_MODULES.'/devices/SMediaRenderer_urllist.php');
unlink(DIR_MODULES.'/devices/SMediaRenderer_volume.php');
unlink(DIR_MODULES.'/devices/SMediaServer_getFileList.php');
unlink(DIR_MODULES.'/devices/SYeelightSmartBulb_changecolor.php');
unlink(DIR_MODULES.'/devices/SYeelightSmartBulb_changetemp.php');
unlink(DIR_MODULES.'/devices/SYeelightSmartBulb_switch.php');
unlink(DIR_MODULES.'/devices/SYeelightSmartBulb_turnOff.php');
unlink(DIR_MODULES.'/devices/addons/sspdfinder_structure.php');
// delete classes views
unlink(DIR_TEMPLATES.'/classes\views\SBinaryLight.html');
unlink(DIR_TEMPLATES.'/classes\views\SControllee.html');
unlink(DIR_TEMPLATES.'/classes\views\SInternetGatewayDevice.html');
unlink(DIR_TEMPLATES.'/classes\views\SMediaRenderer.html');
unlink(DIR_TEMPLATES.'/classes\views\SMediaServer.html');
unlink(DIR_TEMPLATES.'/classes\views\SYeelightSmartBulb.html');

// delete all jalders library not nedded
deleteDirectory(DIR_MODULES.'/ssdp_finder/ssdp');

unlink(DIR_MODULES.'/ssdp_finder\upnp\vendor\jalder\upnp\src\BelkinDevice.php');
unlink(DIR_MODULES.'/ssdp_finder\upnp\vendor\jalder\upnp\src\Renderer.php');
unlink(DIR_MODULES.'/ssdp_finder\upnp\vendor\jalder\upnp\src\BelkinDevice\Devswitch.php');
unlink(DIR_MODULES.'/ssdp_finder\upnp\vendor\jalder\upnp\src\BinaryLight.php');
unlink(DIR_MODULES.'/ssdp_finder\upnp\vendor\jalder\upnp\src\BinaryLight\Remote.php');
unlink(DIR_MODULES.'/ssdp_finder\upnp\vendor\jalder\upnp\src\CastCommander.php');
unlink(DIR_MODULES.'/ssdp_finder\upnp\vendor\jalder\upnp\src\Chromecast.php');
unlink(DIR_MODULES.'/ssdp_finder\upnp\vendor\jalder\upnp\src\Dial.php');
unlink(DIR_MODULES.'/ssdp_finder\upnp\vendor\jalder\upnp\src\InternetGatewayDevice.php');
unlink(DIR_MODULES.'/ssdp_finder\upnp\vendor\jalder\upnp\src\MediaRenderer.php');
unlink(DIR_MODULES.'/ssdp_finder\upnp\vendor\jalder\upnp\src\MediaServer.php');
unlink(DIR_MODULES.'/ssdp_finder\upnp\vendor\jalder\upnp\src\Roku.php');
// delete all jalders library not nedded
deleteDirectory(DIR_MODULES.'/ssdp_finder/upnp/vendor/jalder/upnp/src/BelkinDevice');
deleteDirectory(DIR_MODULES.'/ssdp_finder/upnp/vendor/jalder/upnp/src/BinaryLight');
deleteDirectory(DIR_MODULES.'/ssdp_finder/upnp/vendor/jalder/upnp/src/Chromecast');
deleteDirectory(DIR_MODULES.'/ssdp_finder/upnp/vendor/jalder/upnp/src/Dial');
deleteDirectory(DIR_MODULES.'/ssdp_finder/upnp/vendor/jalder/upnp/src/Mediaserver');
deleteDirectory(DIR_MODULES.'/ssdp_finder/upnp/vendor/jalder/upnp/src/Renderer');
deleteDirectory(DIR_MODULES.'/ssdp_finder/upnp/vendor/jalder/upnp/src/Roku');
deleteDirectory(DIR_MODULES.'/ssdp_finder/upnp/vendor/jalder/upnp/bin');
deleteDirectory(DIR_MODULES.'/ssdp_finder/upnp/vendor/jalder/upnp/examples');
deleteDirectory(DIR_MODULES.'/ssdp_finder/upnp/vendor/jalder/upnp/tests');

// исправлено для измениения типа поля Логотипа
SQLExec("ALTER TABLE `ssdp_devices` CHANGE `LOGO` `LOGO` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''");



// function of delete directory
function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }

    }

    return rmdir($dir);
}
