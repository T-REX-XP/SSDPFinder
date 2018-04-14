

# SSDP Finder

[Модуль](https://majordomo.smartliving.ru/forum/viewtopic.php?f=5&t=2756) для открытой платформы домашней автоматизации [Majordomo](majordomo.smartliving.ru).
Основное предназначение модуля - поиск и добавление в систему UPNP устройств. Конкретный функционал(список файлов, воспроизведение...и.т.) по работе с каждым устройством(сервер, рендер, камера, хромкаст...) в основном должен быть вынесен в другой модуль на подобии Простые устройства




**Возможности:**

 - Поиск устройств в локальной сети на основании UPNP протокола
 - Добавление устройств в систему
 - Получение списка сервисов устройства
 - управление устройствами проигрывания MediaRenderer (автоматически создается шаблон управления отдельными устройствами)
   Только необходимо добавить на собственную сцену управления
   Для воспроизведения ссылки необходимо изменить значение MediaRenderer01.playUrl (Воспроизвести ссылку) для нужного устройства.. 
   Остальное управление находится в шаблоне...
 
 - Получение списка файлов находящихся на MediaServere
 
 
 ![ScreenShot](/screen.png)


**TODO:**

 - ~~Добавление простых устройств с помощью модуля (Сначала MediaServer, MediaRender, dial(Chromecast))~~
 - Получения дополнитеньных устройств с одного UPNP 


----------


----------


----------


Запись для себя
выключатель - состояние on off

<?xml version="1.0" encoding="utf-8"?>

<root xmlns="urn:schemas-upnp-org:device-1-0">

<specVersion>

<major>1</major>

<minor>0</minor>

</specVersion>



<device>

<deviceType>urn:schemas-upnp-org:device:BinaryLight:1</deviceType>
<friendlyName>Kitchen Lights</friendlyName>
<manufacturer>OpenedHand</manufacturer>
<modelName>Virtual Light</modelName>
<UDN>uuid:cc93d8e6-6b8b-4f60-87ca-228c36b5b0e8</UDN> -последние 12 символов мак адрес
<serviceList>
<service>
<serviceType>urn:schemas-upnp-org:service:SwitchPower:1</serviceType>
<serviceId>urn:upnp-org:serviceId:SwitchPower:1</serviceId>
<SCPDURL>/SwitchPower1.xml</SCPDURL>
<controlURL>/SwitchPower/Control</controlURL>
<eventSubURL>/SwitchPower/Event</eventSubURL>
</service>
</serviceList>
</device>
</root>

скрипт оперделения папок переделка и не забыть в рендерер исправить $control_url = str_ireplace("Location:", "", $server['location']); удалить ['location'] 
<?php

require(dirname(__FILE__).'/../../../autoload.php');

use jalder\Upnp\Mediaserver;

$mediaserver = new Mediaserver();

$browse = new Mediaserver\Browse('http://192.168.1.20:2869/upnphost/udhisapi.dll?content=uuid:cc3558a5-ce9c-4a9e-a898-38cd8e4ade29');
$directories = $browse->browse();
foreach($directories as $list){
      print_r($list['dc:title'].'<br> ');
      //print_r($list['dc:title']); // выводит имена папок
     //Array ( [parentID] => 0 [dc:title] => Списки воспроизведения [upnp:class] => object.container )
     $s = new SplObjectStorage($list['upnp:class']);
     var_dump($s->count());
     //var_dump($s->serialize());
     //var_dump($s->getinfo());
     $base = get_class_methods($s);
     print_r($base);
     print_r($base['getInfo']);
      
   }


