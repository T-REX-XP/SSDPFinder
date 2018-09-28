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
   'SSDP_CLEARMODULE' => 'Удалить все устройства и мусор модуля',
   'SSDP_UPDATE_METHODS_SHABLON' => 'Обновить методы и шаблоны установленных устройств',
   'SSDP_SCAN_UPNP_DEVICE' => 'Сканировать UPNP устройства',
   'SSDP_SCAN_3DDEVICE_DEVICE' => 'Сканировать ВСЕ устройства',
);

foreach ($dictionary as $k => $v) {
    if (!defined('LANG_' . $k)) {
        define('LANG_' . $k, $v);
    }
}
