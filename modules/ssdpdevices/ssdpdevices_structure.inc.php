<?php
$this->ssdpdevices_types=array(
        'ssdpdevices'=>array(
        'CLASS'=>'UPNPdevices',
        'PARENT_CLASS'=>'SDevices',
        'DESCRIPTION'=>'Auto finded devices',
        'PROPERTIES'=>array(
            'Logo'=>array('DESCRIPTION'=>'Логотип','_CONFIG_TYPE'=>'text'),
            'UUID'=>array('DESCRIPTION'=>'UUID device','_CONFIG_TYPE'=>'text'),
            'ADDRESS'=>array('DESCRIPTION'=>'IP адрес устройтсва','_CONFIG_TYPE'=>'text'),
            'Type'=>array('DESCRIPTION'=>' Тип устройства','ONCHANGE'=>'updatePreview','_CONFIG_TYPE'=>'text'),
            'Services'=>array('DESCRIPTION'=>' Сервисы','_CONFIG_TYPE'=>'text'),
            'MANUFACTURER'=>array('DESCRIPTION'=>'Разработчик устройства','ONCHANGE'=>'updatePreview','_CONFIG_TYPE'=>'text'),
            'Model'=>array('DESCRIPTION'=>' Имя устройства','ONCHANGE'=>'updatePreview','_CONFIG_TYPE'=>'text'),
            'DESCRIPTION'=>array('DESCRIPTION'=>'Описание устройства','ONCHANGE'=>'updatePreview','_CONFIG_TYPE'=>'text'),
			'CONTROLADDRESS'=>array('DESCRIPTION'=>'Адрес управления устройством ','_CONFIG_TYPE'=>'text'),
            'groupEco'=>array('DESCRIPTION'=>LANG_DEVICES_GROUP_ECO,'_CONFIG_TYPE'=>'yesno'),
            'groupEcoOn'=>array('DESCRIPTION'=>LANG_DEVICES_GROUP_ECO_ON,'_CONFIG_TYPE'=>'yesno'),            
            'groupSunrise'=>array('DESCRIPTION'=>LANG_DEVICES_GROUP_SUNRISE,'_CONFIG_TYPE'=>'yesno'),
            'isActivity'=>array('DESCRIPTION'=>LANG_DEVICES_IS_ACTIVITY,'_CONFIG_TYPE'=>'yesno'),

        ),
    ),
    'MediaServer'=>array(
        'TITLE'=>'UPNP Медиасервер',
        'PARENT_CLASS'=>'UPNPdevices',
        'CLASS'=>'SMediaServer',
        'PROPERTIES'=>array(
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
            'mute_unmute'=>array('DESCRIPTION'=>'Отключение/включение звука', 'KEEP_HISTORY'=>1, 'ONCHANGE'=>'mute-unmute', 'DATA_KEY'=>1),
            'volume'=>array('DESCRIPTION'=>'Уровень звука', 'KEEP_HISTORY'=>1, 'ONCHANGE'=>'volume', 'DATA_KEY'=>1),
            'pause_unpause'=>array('DESCRIPTION'=>'Отключение/включение паузы', 'KEEP_HISTORY'=>1, 'ONCHANGE'=>'pause-unpause', 'DATA_KEY'=>1),
            'next'=>array('DESCRIPTION'=>'Следующая запись', 'KEEP_HISTORY'=>1, 'ONCHANGE'=>'next', 'DATA_KEY'=>1),
            'previous'=>array('DESCRIPTION'=>'Предыдущая запись', 'KEEP_HISTORY'=>1, 'ONCHANGE'=>'previous', 'DATA_KEY'=>1),
            'seeknext'=>array('DESCRIPTION'=>'Перемотка вперед на 30сек', 'KEEP_HISTORY'=>1, 'ONCHANGE'=>'seeknext', 'DATA_KEY'=>1),
            'seekprevious'=>array('DESCRIPTION'=>'Перемотка назад на 30сек', 'KEEP_HISTORY'=>1, 'ONCHANGE'=>'seekprevious', 'DATA_KEY'=>1),
            'stop'=>array('DESCRIPTION'=>'Стоп', 'KEEP_HISTORY'=>1, 'ONCHANGE'=>'stop', 'DATA_KEY'=>1),
            'playUrl'=>array('DESCRIPTION'=>'Воспроизвести ссылку', 'KEEP_HISTORY'=>1, 'ONCHANGE'=>'playUrl', 'DATA_KEY'=>1),
            'playNextUrl'=>array('DESCRIPTION'=>'Воспроизвести следующую ссылку без прерывания предыдущей', 'KEEP_HISTORY'=>1, 'ONCHANGE'=>'playNextUrl', 'DATA_KEY'=>1),
       ),
        'METHODS'=>array(
            'mute-unmute'=>array('DESCRIPTION'=>'Отключение/включение звука'),
            'volume'=>array('DESCRIPTION'=>'Уровень звука'),
            'pause-unpause'=>array('DESCRIPTION'=>'Отключение/включение паузы'),
            'next'=>array('DESCRIPTION'=>'Следующий трек'),
            'previous'=>array('DESCRIPTION'=>'Предыдущий трек'),
            'seeknext'=>array('DESCRIPTION'=>'Перемотка вперед на 30сек'),
            'seekprevious'=>array('DESCRIPTION'=>'Перемотка назад на 30сек'),
            'stop'=>array('DESCRIPTION'=>'Стоп'),
            'playUrl'=>array('DESCRIPTION'=>'Воспроизвести ссылку'),
            'playNextUrl'=>array('DESCRIPTION'=>'Воспроизвести следующую ссылку без прерывания предыдущей'),

        ),
    ),
);
