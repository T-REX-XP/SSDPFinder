<?php
/**
 * Russian language file
 *
 * @package MajorDoMo
 * @author Serge Dzheigalo <jey@tut.by> http://smartliving.ru/
 * @version 1.0
 */



$dictionary = array (
   'SSDP_TITLE' => 'Поиск и добавление в систему UPNP устройств.',
   'SSDP_PORT' => 'Порт для подписки на ответы от событий в устройствах',

);

foreach ($dictionary as $k => $v) {
    if (!defined('LANG_' . $k)) {
        define('LANG_' . $k, $v);
    }
}
