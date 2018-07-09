<?php
/**
 * Ukranian language file
 *
 * @package MajorDoMo
 * @author Serge Dzheigalo <jey@tut.by> http://smartliving.ru/
 * @version 1.0
 */

$dictionary=array(

'SSDP_TITLE' => 'Пошук та добавляння в систему UPNP пристроїв.',



);

foreach ($dictionary as $k=>$v) {
 if (!defined('LANG_'.$k)) {
  define('LANG_'.$k, $v);
 }
}
