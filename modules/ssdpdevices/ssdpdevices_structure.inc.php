<?php

$this->ssdpdevices_types=array(
        'ssdpdevices'=>array(
        'CLASS'=>'UPNPdevices',
        'PARENT_CLASS'=>'SDevices',
        'DESCRIPTION'=>'Auto finded devices',
        'PROPERTIES'=>array(
            'Logo'=>array('DESCRIPTION'=>'Логотип','_CONFIG_TYPE'=>'text'),
            'UUID'=>array('DESCRIPTION'=>'UUID device','_CONFIG_TYPE'=>'text'),
            'IP'=>array('DESCRIPTION'=>'IP Adress device','_CONFIG_TYPE'=>'text'),
            'Type'=>array('DESCRIPTION'=>' Тип устройства','ONCHANGE'=>'updatePreview','_CONFIG_TYPE'=>'text'),
            'Services'=>array('DESCRIPTION'=>' Сервисы','_CONFIG_TYPE'=>'text'),
            'MANUFACTURER'=>array('DESCRIPTION'=>'Разработчик устройства','ONCHANGE'=>'updatePreview','_CONFIG_TYPE'=>'text'),
            'Model'=>array('DESCRIPTION'=>' Имя устройства','ONCHANGE'=>'updatePreview','_CONFIG_TYPE'=>'text'),
            'DESCRIPTION'=>array('DESCRIPTION'=>'Описание устройства','ONCHANGE'=>'updatePreview','_CONFIG_TYPE'=>'text'),
        ),
    ),
    'MediaServer'=>array(
        'TITLE'=>'UPNP Медиасервер',
        'PARENT_CLASS'=>'UPNPdevices',
        'CLASS'=>'SMediaServer',
        'PROPERTIES'=>array(
	    'mute_unmute'=>array('DESCRIPTION'=>'Отключение/включение звука', 'KEEP_HISTORY'=>1, 'ONCHANGE'=>'mute-unmute', 'DATA_KEY'=>1),
            'pause_unpause'=>array('DESCRIPTION'=>'Отключение/включение паузы', 'KEEP_HISTORY'=>1, 'ONCHANGE'=>'pause-unpause', 'DATA_KEY'=>1),
       ),
    'METHODS'=>array(
            'mute-unmute'=>array('DESCRIPTION'=>'Отключение/включение звука'),
            'pause-unpause'=>array('DESCRIPTION'=>'Отключение/включение паузы'),

        ),
    ),
    'dial'=>array(
        'TITLE'=>'UPNP DIAL устройство',
        'PARENT_CLASS'=>'UPNPdevices',
        'CLASS'=>'Sdial',
        'PROPERTIES'=>array(
            'PlayURL'=>array('DESCRIPTION'=>' Play Url','ONCHANGE'=>'updatePreview','_CONFIG_TYPE'=>'text'),
            'Applications'=>array('DESCRIPTION'=>' Приложения','_CONFIG_TYPE'=>'text'),
        ),
     ),
    'Basic'=>array(
        'TITLE'=>'UPNP Простое устройство',
        'PARENT_CLASS'=>'UPNPdevices',
        'CLASS'=>'SBasic',
        'PROPERTIES'=>array(
            'Username'=>array('DESCRIPTION'=>'Username','_CONFIG_TYPE'=>'text'),
            'Password'=>array('DESCRIPTION'=>'Password','ONCHANGE'=>'updatePreview','_CONFIG_TYPE'=>'text'),
        ),
     ),
    'DigitalSecurityCamera'=>array(
        'TITLE'=>'UPNP Камера видеонаблюдения',
        'PARENT_CLASS'=>'UPNPdevices',
        'CLASS'=>'SDigitalSecurityCamera',
        'PROPERTIES'=>array(
            'streamURL'=>array('DESCRIPTION'=>LANG_DEVICES_CAMERA_STREAM_URL.' (LQ)','ONCHANGE'=>'updatePreview','_CONFIG_TYPE'=>'text'),
            'Username'=>array('DESCRIPTION'=>'Username','_CONFIG_TYPE'=>'text'),
            'Password'=>array('DESCRIPTION'=>'Password','ONCHANGE'=>'updatePreview','_CONFIG_TYPE'=>'text'),
       ),
     ),
    'InternetGatewayDevice'=>array(
        'TITLE'=>'UPNP Роутер',
        'PARENT_CLASS'=>'UPNPdevices',
        'CLASS'=>'SInternetGatewayDevice',
        'PROPERTIES'=>array(
            'Username'=>array('DESCRIPTION'=>'Username','_CONFIG_TYPE'=>'text'),
            'Password'=>array('DESCRIPTION'=>'Password','ONCHANGE'=>'updatePreview','_CONFIG_TYPE'=>'text'),
        ),
    ),
    'MediaRenderer'=>array(
        'TITLE'=>'UPNP Телевизор',
        'PARENT_CLASS'=>'UPNPdevices',
        'CLASS'=>'SMediaRenderer',
        'PROPERTIES'=>array(
        ),
    ),
);
