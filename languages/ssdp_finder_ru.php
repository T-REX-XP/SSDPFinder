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
   'SSDP_NAME' => 'Название:',
   'SSDP_TYPE' => 'Тип:',
   'SSDP_SERVICETYPE' => 'Типы Сервисов:',
   'SSDP_IPADRESS' => 'IP адрес:',
   'SSDP_DESCRIPTION' => 'Описание:',
   'SSDP_MODEL' => 'Модель:',
   'SSDP_MANUFACTURER' => 'Производитель:',
   'SSDP_CREATESD' => 'Создать простое устройство',
   'SSDP_HOSTONLINE' => 'Создать устройство онлайн',
   'SSDP_TERMINAL' => 'Создать терминал устройства',
   'SSDP_USETOSYSTEMMESAGE' => 'Использовать для воспроизведения системных сообщений',
);

foreach ($dictionary as $k => $v) {
    if (!defined('LANG_' . $k)) {
        define('LANG_' . $k, $v);
    }
}
