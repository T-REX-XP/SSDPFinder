<?php
 
$this->device_types['ssdpdevices'] = array(
        'CLASS'=>'UPNPdevices',
        'PARENT_CLASS'=>'SDevices',
        'DESCRIPTION'=>'Auto finded devices',
        'PROPERTIES'=>array(
            'Logo'=>array('DESCRIPTION'=>'Логотип','_CONFIG_TYPE'=>'text'),
            'UUID'=>array('DESCRIPTION'=>'UUID device','_CONFIG_TYPE'=>'text'),
            'ADDRESS'=>array('DESCRIPTION'=>'IP адрес устройства','_CONFIG_TYPE'=>'text'),
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
);

$this->device_types['MediaServer'] = array(
        'TITLE'=>'UPNP Медиасервер',
        'PARENT_CLASS'=>'UPNPdevices',
        'CLASS'=>'SMediaServer',
        'PROPERTIES'=>array(
            'getFileList'=>array('DESCRIPTION'=>'При изменении Получает список файлов на устройстве', 'KEEP_HISTORY'=>1, 'ONCHANGE'=>'getFileList', 'DATA_KEY'=>1),
       ),
        'METHODS'=>array(
            'getFileList'=>array('DESCRIPTION'=>'Получает список файлов на устройстве'),

        )
);

$this->device_types['dial'] = array(
        'TITLE'=>'UPNP DIAL устройство',
        'PARENT_CLASS'=>'UPNPdevices',
        'CLASS'=>'Sdial',
        'PROPERTIES'=>array(
            'PlayURL'=>array('DESCRIPTION'=>' Play Url','ONCHANGE'=>'updatePreview','_CONFIG_TYPE'=>'text'),
            'Applications'=>array('DESCRIPTION'=>' Приложения','_CONFIG_TYPE'=>'text'),
        ),
);

$this->device_types['Basic'] = array(
        'TITLE'=>'UPNP Простое устройство',
        'PARENT_CLASS'=>'UPNPdevices',
        'CLASS'=>'SBasic',
        'PROPERTIES'=>array(
            'Username'=>array('DESCRIPTION'=>'Username','_CONFIG_TYPE'=>'text'),
            'Password'=>array('DESCRIPTION'=>'Password','ONCHANGE'=>'updatePreview','_CONFIG_TYPE'=>'text'),
        ),
);

$this->device_types['DigitalSecurityCamera'] = array(
        'TITLE'=>'UPNP Камера видеонаблюдения',
        'PARENT_CLASS'=>'UPNPdevices',
        'CLASS'=>'SDigitalSecurityCamera',
        'PROPERTIES'=>array(
            'streamURL'=>array('DESCRIPTION'=>LANG_DEVICES_CAMERA_STREAM_URL.' (LQ)','ONCHANGE'=>'updatePreview','_CONFIG_TYPE'=>'text'),
            'Username'=>array('DESCRIPTION'=>'Username','_CONFIG_TYPE'=>'text'),
            'Password'=>array('DESCRIPTION'=>'Password','ONCHANGE'=>'updatePreview','_CONFIG_TYPE'=>'text'),
       ),
);

$this->device_types['InternetGatewayDevice'] = array(
        'TITLE'=>'UPNP Роутер',
        'PARENT_CLASS'=>'UPNPdevices',
        'CLASS'=>'SInternetGatewayDevice',
        'PROPERTIES'=>array(
            'Username'=>array('DESCRIPTION'=>'Username','_CONFIG_TYPE'=>'text'),
            'Password'=>array('DESCRIPTION'=>'Password','ONCHANGE'=>'updatePreview','_CONFIG_TYPE'=>'text'),
        ),
);

$this->device_types['MediaRenderer'] = array(
        'TITLE'=>'UPNP Устройство воспроизведения',
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

        )
);

$this->device_types['BinaryLight'] = array(
        'TITLE'=>'UPNP выключатель',
        'PARENT_CLASS'=>'UPNPdevices',
        'CLASS'=>'SBinaryLight',
        'PROPERTIES'=>array(
            'turnOn'=>array('DESCRIPTION'=>'Включение', 'KEEP_HISTORY'=>1, 'ONCHANGE'=>'switch', 'DATA_KEY'=>1),
            'turnOff'=>array('DESCRIPTION'=>'Выключение', 'KEEP_HISTORY'=>1, 'ONCHANGE'=>'switch', 'DATA_KEY'=>1),
       ),
        'METHODS'=>array(
            'turnOn'=>array('DESCRIPTION'=>'turnOn'),
            'turnOff'=>array('DESCRIPTION'=>'turnOff'),
            'switch'=>array('DESCRIPTION'=>'Switch'),
        )
);

$this->device_types['controllee'] = array(
        'TITLE'=>'Wemos выключатель',
        'PARENT_CLASS'=>'UPNPdevices',
        'CLASS'=>'Scontrollee',
        'PROPERTIES'=>array(
            'turnOn'=>array('DESCRIPTION'=>'Включение', 'KEEP_HISTORY'=>1, 'ONCHANGE'=>'switch', 'DATA_KEY'=>1),
            'turnOff'=>array('DESCRIPTION'=>'Выключение', 'KEEP_HISTORY'=>1, 'ONCHANGE'=>'switch', 'DATA_KEY'=>1),
       ),
        'METHODS'=>array(
            'turnOn'=>array('DESCRIPTION'=>'turnOn'),
            'turnOff'=>array('DESCRIPTION'=>'turnOff'),
            'switch'=>array('DESCRIPTION'=>'Switch'),
        )
);

$this->device_types['YeelightSmartBulb'] = array(
        'TITLE'=>'Yeelight лампа',
        'PARENT_CLASS'=>'UPNPdevices',
        'CLASS'=>'SYeelightSmartBulb',
        'PROPERTIES'=>array(
            'turnOn'=>array('DESCRIPTION'=>'Включение', 'KEEP_HISTORY'=>1, 'ONCHANGE'=>'switch', 'DATA_KEY'=>1),
            'turnOff'=>array('DESCRIPTION'=>'Выключение', 'KEEP_HISTORY'=>1, 'ONCHANGE'=>'switch', 'DATA_KEY'=>1),
			'changecolor'=>array('DESCRIPTION'=>'Изменить цвет', 'KEEP_HISTORY'=>1, 'ONCHANGE'=>'changecolor', 'DATA_KEY'=>1),
			'changetemp'=>array('DESCRIPTION'=>'Изменить температуру', 'KEEP_HISTORY'=>1, 'ONCHANGE'=>'changetemp', 'DATA_KEY'=>1),
       ),
        'METHODS'=>array(
            'turnOn'=>array('DESCRIPTION'=>'Включение'),
            'turnOff'=>array('DESCRIPTION'=>'Выключение'),
            'switch'=>array('DESCRIPTION'=>'Изменить состояние'),
			'changecolor'=>array('DESCRIPTION'=>'Изменить цвет'),
			'changetemp'=>array('DESCRIPTION'=>'Изменить температуру'),
        )
);