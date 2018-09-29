<?php
/*
* @version 0.1 (wizard)
*/
require ('upnp/vendor/autoload.php');

use jalder\Upnp\Upnp;
global $session;

if ($this->owner->name == 'panel')
    {
    $out['CONTROLPANEL'] = 1;
    }

$qry = "1";

global $save_qry;

if ($save_qry)
    {
    $qry = $session->data['ssdp_devices_qry'];
    }
  else
    {
    $session->data['ssdp_devices_qry'] = $qry;
    }

if (!$qry) $qry = "1";
$res = Scan_3rddevice();

if ($res[0]['UUID'])
    {

    // paging($res, 100, $out); // search result paging

    $total = count($res);
    for ($i = 0; $i < $total; $i++)
        {

        // some action for every record if required

        $tmp = explode(' ', $res[$i]['UPDATED']);
        $res[$i]['UPDATED'] = fromDBDate($tmp[0]) . " " . $tmp[1];
        }

    $out['RESULT'] = $res;
    }

// функция сканирования устройств

function Scan_3rddevice()
    {
    $upnp = new Upnp();
    $everything = $upnp->discover_3rddevice();
    $result = [];
    $table_name = 'ssdp_devices';

        // перебираем по очереди все найденные устройства
        foreach($everything as $deviceInfo)
            {

            // если устройство mag250
            DebMes ($deviceInfo);
            if ($deviceInfo['MAGaddres'])
                {
                $control_url = $deviceInfo['MAGaddres'];

                // проверяем на наличие в базе для запрета вывода
                $uuid = $deviceInfo['MAGSN'];
                $existed = SQLSelectOne("SELECT * FROM $table_name WHERE UUID='" . $uuid . "'");

                // need for chek device type
                $device_type = 'MagXXXdevice'; //DeviceType
                $services = 'STB DeviceServices'; //DeviceServices
                // проверяем на наличие модуля в системе
                $mod_cheked = SQLSelectOne("SELECT * FROM project_modules WHERE NAME LIKE '" . $modules['YeelightSmartBulb'] . "'");
                if (!array_search_result($result, 'UUID', $uuid) && !is_null($uuid) && !($existed))
                    {
                    $result[] = [
                    "ID" => $existed["ID"], //existed id Majordomo
                    "TITLE" => $deviceInfo['MAGname'], //friendly name
                    "ADDRESS" => $control_url, //presentation url (web UI of device),//presentation url (web UI of device)
                    "UUID" => $deviceInfo['MAGSN'], 
                    "LOGO" => getDefImg($control_url, $device_type), //Logo
                    "DESCRIPTION" => 'TV smart box', //description get from xml or field "server"
                    "TYPE" => $device_type, //DeviceType
                    "SERIAL" => $deviceInfo['MAGSN'], //serialnumber
                    "MANUFACTURERURL" => 'Инфомир', //manufacturer url
                    "MODEL" => $deviceInfo['type'], //model
                    "MODELNUMBER" => 'not existed', //modelNumber
                    "SERVICES" => $services, //list services of device
                    "CONTROLADDRESS" => $control_url, //list services of device
                    "EXTENDED_MODULES" => ext_search_modules($services), // проверка на наличие модуля
                    "MODULE_INSTALLED" => $mod_cheked, //chek the installed module
                    "EXTENDED_SIMPLEDEVICE" => check_seample_device($device_type) , //chek the simple device extended
                    ];
                    $_SESSION[$uuid] = $logo;
                    }
                } 
              else if (substr($deviceInfo['location'], 0, 9) == "yeelight:")
                {
                $control_url = str_ireplace("yeelight:", "http:", $deviceInfo['location']);

                // проверяем на наличие в базе для запрета вывода
                $uuid = $deviceInfo['location'];
                $existed = SQLSelectOne("SELECT * FROM $table_name WHERE UUID='" . $uuid . "'");

                // need for chek device type
                $device_type = 'YeelightSmartBulb'; //DeviceType
                $services = 'YeelightSmartBulb'; //DeviceServices
                // проверяем на наличие модуля в системе
                $mod_cheked = SQLSelectOne("SELECT * FROM project_modules WHERE NAME LIKE '" . $modules['YeelightSmartBulb'] . "'");

                if (!array_search_result($result, 'UUID', $uuid) && !is_null($uuid) && !($existed))
                    {
                    $result[] = [
                    "ID" => $existed["ID"], //existed id Majordomo
                    "TITLE" => 'Yeelight bulb', //friendly name
                    "ADDRESS" => $control_url, //presentation url (web UI of device),//presentation url (web UI of device)
                    "UUID" => $deviceInfo['location'], 
                    "LOGO" => getDefImg($control_url, $device_type), //Logo
                    "DESCRIPTION" => 'Yeelight WiFi Light', //description get from xml or field "server"
                    "TYPE" => $device_type, //DeviceType
                    "SERIAL" => 'not existed', //serialnumber
                    "MANUFACTURERURL" => 'https://www.yeelight.com', //manufacturer url
                    "UPDATED" => '', 
                    "MODEL" => 'not existed', //model
                    "MODELNUMBER" => 'not existed', //modelNumber
                    "MANUFACTURER" => 'YeelightSmartBulb', //Manufacturer
                    "SERVICES" => $services, //list services of device
                    "CONTROLADDRESS" => $control_url, //list services of device
                    "EXTENDED_MODULES" => ext_search_modules($services), // проверка на наличие модуля
                    "MODULE_INSTALLED" => $mod_cheked, //chek the installed module
                    "EXTENDED_SIMPLEDEVICE" => check_seample_device($device_type) , //chek the simple device extended
                    ];
                    $_SESSION[$uuid] = $logo;
                    }
                } 
              else  // иначе проверяем остальные устройства
                {

                // то что надо обработать в первую очередь
                $device = $deviceInfo['description']['device'];
                $control_url = $deviceInfo['location'];

                // для начала проверяем не майкрософтовое ли это устройство
                // и если да то подгружаем внутренний файл потому что он находится в ссылке на файл
                // for microsoft devices
                if (substr($deviceInfo['location'], 0, 9) == "Location:")
                    {
                    $control_url = str_ireplace("Location:", "", $deviceInfo['location']);
                    libxml_use_internal_errors(true);
                    $xml = simplexml_load_file($control_url);
                    $json = json_encode($xml);
                    $dev = (array)json_decode($json, true);
                    $device = $dev['device'];
                    }

                // need for chek device type
                $device_type = explode(":", $device["deviceType"]) [3]; //DeviceType

                // получаем логотип на устройство
                $logo = getDefImg($control_url, $device);


                // иногда вместо serialNumber есть modelNumber
                $serialnumber = $device["serialNumber"];
                if (!$serialnumber)
                    {
                    $serialnumber = $device["modelNumber"];
                    }

                // иногда presentationURL отсутствует
                $presenturl = $device["presentationURL"];
                if (!$device["presentationURL"])
                    {
                    $presenturl = 'http://' . getIp($control_url, false);
                    }

                // иногда modelDescription отсутствует тогда берем server
                $descript = $device["modelDescription"];
                if (!$device["modelDescription"])
                    {
                    $descript = $deviceInfo["server"];
                    }
                $services = getServices($device);

                // проверяем на наличие в базе для запрета вывода
                $uuid = $device["UDN"];
                $existed = SQLSelectOne("SELECT * FROM $table_name WHERE UUID='" . $uuid . "' AND SERVICES='" . $services . "'"); 

                // проверяем на наличие модуля в системе
                $mod_cheked = SQLSelectOne("SELECT * FROM project_modules WHERE NAME LIKE '" . $modules[$device_type] . "'");
                if (!array_search_result($result, 'UUID', $uuid) && !is_null($uuid) && !($existed))
                    {
                    $result[] = [
                    "ID" => $existed["ID"], //existed id Majordomo
                    "TITLE" => $device["friendlyName"], //friendly name
                    "ADDRESS" => $presenturl, //presentation url (web UI of device),//presentation url (web UI of device)
                    "UUID" => $uuid, 
                    "LOGO" => $logo, //Logo
                    "DESCRIPTION" => $descript, //description get from xml or field "server"
                    "TYPE" => $device_type, //DeviceType
                    "SERIAL" => $serialnumber, //serialnumber
                    "MANUFACTURERURL" => $device["manufacturerURL"], //manufacturer url
                    "UPDATED" => '', "MODEL" => $device["modelName"], //model
                    "MODELNUMBER" => $device["modelNumber"], //modelNumber
                    "MANUFACTURER" => $device["manufacturer"], //Manufacturer
                    "SERVICES" => $services, //list services of device
                    "CONTROLADDRESS" => $control_url, //list services of device
                    "EXTENDED_MODULES" => ext_search_modules($services), 
                    "MODULE_INSTALLED" => $mod_cheked, //chek the installed module
                    "EXTENDED_SIMPLEDEVICE" => check_seample_device($device_type) , //chek the simple device extended
                    ];
                    $_SESSION[$uuid] = $logo;
                    }
                }
            }
    return $result;
    }

function ext_search_modules($services)
    {
    // подключение массива существующих модулей для найденных устройств
    include (DIR_MODULES . 'ssdp_finder/extended_modules.php');
    $services = explode (',',$services);
    foreach ( $services as $service)
    {
      if ($modules[$service]) {
          return $modules[$service];
      }
    }
    return;
    }

function array_search_result($array, $key, $value)
    {
    foreach($array as $k => $v)
        {
        if (array_key_exists($key, $v) && ($v[$key] == $value))
            {
            return true;
            }
        }
    }

function getIp($baseUrl, $withPort)
    {
    if (!empty($baseUrl))
        {
        $parsed_url = parse_url($baseUrl);
        if ($withPort == true)
            {
            $baseUrl = $parsed_url['scheme'] . '://' . $parsed_url['host'] . ':' . $parsed_url['port'];
            }
          else
            {
            $baseUrl = $parsed_url['host'];
            }
        }

    return $baseUrl;
    }

// получаем hostname адрес локального компьютера

function getLocalIp()
    {
    return gethostbyname(trim(`hostname`));
    }

// проверка на присутствие простого устройства для просканированого устройства

function check_seample_device($device_type)
    {
    $answer = @file_get_contents('https://raw.githubusercontent.com/tarasfrompir/SSDPDrivers/master/modules/devices/addons/SSDPFinder_' . $device_type . '_structure.php');
    if ($answer)
        {
        return $device_type;
        };
    return;
    }

function startsWith($haystack, $needle)
    {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
    }

function getServices($device)
    {
    $result = array();
    if (isset($device["serviceList"]["service"]["serviceType"]))
        {
        $name = $device["serviceList"]["service"]["serviceType"];
        array_push($result, $name);
        }
      else if (isset($device["serviceList"]["service"]))
        {
        foreach($device["serviceList"]["service"] as $type)
            {
            $name = $type["serviceType"];
            array_push($result, $name);
            }
        }

    // иногда сервис уходит в глубь файла описания еще на одно поле ["deviceList"]["device"]

    if (isset($device["deviceList"]["device"]["serviceList"]["service"]["serviceType"]))
        {
        $name = $device["deviceList"]["device"]["serviceList"]["service"]["serviceType"];
        array_push($result, $name);
        }
      else if (isset($device["deviceList"]["device"]["serviceList"]["service"]))
        {
        foreach($device["deviceList"]["device"]["serviceList"]["service"] as $type)
            {
            $name = $type["serviceType"];
            array_push($result, $name);
            }
        }

    // А еще иногда сервис уходит в глубь файла описания еще на два поле ["deviceList"]["device"]

    if (isset($device["deviceList"]["device"]["deviceList"]["device"]["serviceList"]["service"]["serviceType"]))
        {
        $name = $device["deviceList"]["device"]["deviceList"]["device"]["serviceList"]["service"]["serviceType"];
        array_push($result, $name);
        }
      else if (isset($device["deviceList"]["device"]["deviceList"]["device"]["serviceList"]["service"]))
        {
        foreach($device["deviceList"]["device"]["deviceList"]["device"]["serviceList"]["service"] as $type)
            {
            $name = $type["serviceType"];
            array_push($result, $name);
            }
        }

    if (!$result)
        {

        // иногда отсутствуют SERVICES  для устройств MSMD Gate тогда берем friendlyName

        return $device["friendlyName"];
        }

    return implode(",", $result);
    }

function endsWith($haystack, $needle)
    {
    return $needle === '' || substr_compare($haystack, $needle, -strlen($needle)) === 0;
    }

function SearchArray($array, $searchIndex, $searchValue)
    {
    if (!is_array($array) || $searchIndex == '') return false;
    foreach($array as $k => $v)
        {
        if (is_array($v) && array_key_exists($searchIndex, $v) && $v[$searchIndex] == $searchValue) return $k;
        }

    return false;
    }

function img64($icon)
    {
    return !is_null($icon) && $icon["width"] && $icon["url"] && $icon["width"] == 48 && $icon["mimetype"];
    };

function get_web_page($url)
    {
    $res = array();
    $options = array(
        CURLOPT_RETURNTRANSFER => true, // return web page
        CURLOPT_HEADER => false, // do not return headers
        CURLOPT_FOLLOWLOCATION => true, // follow redirects
        CURLOPT_USERAGENT => "spider", // who am i
        CURLOPT_AUTOREFERER => true, // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect
        CURLOPT_TIMEOUT => 120, // timeout on response
        CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
    );
    $ch = curl_init($url);
    curl_setopt_array($ch, $options);
    $content = curl_exec($ch);
    $err = curl_errno($ch);
    $errmsg = curl_error($ch);
    $header = curl_getinfo($ch);
    curl_close($ch);
    $res['content'] = $content;
    $res['url'] = $header['url'];
    return $res;
    }

function getDefImg($control_url, $device)
    {
    $current = "";
    $type = "";
    $path = "";
    $url = "";
    $baseUrl = getIp($control_url, True);
    if (is_array($device)){
        $icons = $device["iconList"]["icon"];
        }
    if (!$icons)
        {
        if (is_array($device)){
            $url = "/templates/ssdp_finder/img/" . explode(":", $device["deviceType"]) [3] . ".png"; //"Icons not found
        } else {
            $url = "/templates/ssdp_finder/img/" . $device . ".png"; //"Icons not found
        }
    } else {
        if (isset($icons["url"]))
            {
            $url = $icons["url"];
            }
          else
        if (is_array($icons))
            {
            $arrImg = array_filter($icons, "img64");
            $url = isset($arrImg[0]["url"]) ? $arrImg[0]["url"] : $icons[0]["url"];
            $mimetype = isset($arrImg[0]["mimetype"]) ? $arrImg[0]["mimetype"] : $icons[0]["mimetype"];
            }
        }

    // иногда ссылка дается полностью с всем адресом

    if (substr($url, 0, 4) == "http")
        {
        $path = $url;
    } else if ($baseUrl == "://:")
        {
        $path = "http://".getLocalIp(). $url;
    } else {
        $path = $baseUrl . $url;
        };
		//DebMes($path);
    $current = get_web_page($path);
    $type = pathinfo($path, PATHINFO_EXTENSION);

    // иногда в ссылке на лого отсутствует расширение файла поэтому пробуем взять его из типа в XML файле

    if (strlen($type) > 3)
        {
        return 'data:' . $mimetype . ';base64,' . base64_encode($current['content']);
        }
      else
        {
        return 'data:image/' . $type . ';base64,' . base64_encode($current['content']);
        }
    }
