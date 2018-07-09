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
'SSDP_PORT' => 'Порт для підписки на відповідь про дію пристрою',
'SSDP_NAME' => 'Назва:',

);

foreach ($dictionary as $k=>$v) {
 if (!defined('LANG_'.$k)) {
  define('LANG_'.$k, $v);
 }
}
