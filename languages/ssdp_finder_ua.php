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
'SSDP_TYPE' => 'Тип:',
'SSDP_SERVICETYPE' => 'Типи Сервісів:',
'SSDP_IPADRESS' => 'IP адреса:',
'SSDP_DESCRIPTION' => 'Опис:',
'SSDP_MODEL' => 'Модель:',
'SSDP_MANUFACTURER' => 'Виробник:',
'SSDP_CREATESD' => 'Створити простий пристрій',
'SSDP_HOSTONLINE' => 'Створити пристрій онлайн',
 'SSDP_TERMINAL' => 'Створити термінал пристрою',
 'SSDP_USETOSYSTEMMESAGE' => 'Використовувати для відтворення системних сповіщень',

);

foreach ($dictionary as $k=>$v) {
 if (!defined('LANG_'.$k)) {
  define('LANG_'.$k, $v);
 }
}
