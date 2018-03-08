<?php

$this->ssdpdevices_types=array(
        'ssdpdevices'=>array(
        'CLASS'=>'UPNPdevices',
        'PARENT_CLASS'=>'SDevices',
        'DESCRIPTION'=>'Auto finded devices',
        'PROPERTIES'=>array(
            'UUID'=>array('DESCRIPTION'=>'UUID device','_CONFIG_TYPE'=>'text'),
            'IP'=>array('DESCRIPTION'=>'IP Adress device','_CONFIG_TYPE'=>'text'),
            'Type'=>array('DESCRIPTION'=>' Тип устройства','ONCHANGE'=>'updatePreview','_CONFIG_TYPE'=>'text'),
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
            'Logo'=>array('DESCRIPTION'=>'Логотип','_CONFIG_TYPE'=>'text'),
            'Username'=>array('DESCRIPTION'=>'Username','_CONFIG_TYPE'=>'text'),
            'Password'=>array('DESCRIPTION'=>'Password','ONCHANGE'=>'updatePreview','_CONFIG_TYPE'=>'text'),
       ),
    ),
    'dial'=>array(
        'TITLE'=>'UPNP DIAL устройство',
        'PARENT_CLASS'=>'UPNPdevices',
        'CLASS'=>'Sdial',
        'PROPERTIES'=>array(
            'streamURL'=>array('DESCRIPTION'=>LANG_DEVICES_CAMERA_STREAM_URL.' (LQ)','ONCHANGE'=>'updatePreview','_CONFIG_TYPE'=>'text'),
            'streamURL_HQ'=>array('DESCRIPTION'=>LANG_DEVICES_CAMERA_STREAM_URL.' (HQ)','ONCHANGE'=>'updatePreview','_CONFIG_TYPE'=>'text'),
            'Username'=>array('DESCRIPTION'=>'Username','_CONFIG_TYPE'=>'text'),
            'Password'=>array('DESCRIPTION'=>'Password','ONCHANGE'=>'updatePreview','_CONFIG_TYPE'=>'text'),
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
);
