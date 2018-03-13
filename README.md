

# SSDP Finder

[Модуль](https://majordomo.smartliving.ru/forum/viewtopic.php?f=5&t=2756) для открытой платформы домашней автоматизации [Majordomo](majordomo.smartliving.ru).
Основное предназначение модуля - поиск и добавление в систему UPNP устройств. Конкретный функционал(список файлов, воспроизведение...и.т.) по работе с каждым устройством(сервер, рендер, камера, хромкаст...) в основном должен быть вынесен в другой модуль на подобии Простые устройства




**Возможности:**

 - Поиск устройств в локальной сети на основании UPNP протокола
 - Добавление устройств в систему
 - Получение списка сервисов устройства
 
 
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

вкинуть 

      // moya dobavka
      //$this->add_to_SSDPdevices($rec['ID']);
      //$this->add_to_pinghost($rec['ID']);
