<?php
/*
* @version 0.1 (wizard)
*/
require('upnp/vendor/autoload.php');
use jalder\Upnp\Upnp;

global $session;
if ($this->owner->name == 'panel') {
    $out['CONTROLPANEL'] = 1;
}
$qry = "1";
// search filters
// QUERY READY
global $save_qry;
if ($save_qry) {
    $qry = $session->data['ssdp_devices_qry'];
} else {
    $session->data['ssdp_devices_qry'] = $qry;
}
if (!$qry) $qry = "1";
//="ID DESC";
//$out['SORTBY']=$sortby_ssdp_devices;
// SEARCH RESULTS
$res = Scan();
if ($res[0]['ID']) {
    //paging($res, 100, $out); // search result paging
    $total = count($res);
    for ($i = 0; $i < $total; $i++) {
        // some action for every record if required
        $tmp = explode(' ', $res[$i]['UPDATED']);
        $res[$i]['UPDATED'] = fromDBDate($tmp[0]) . " " . $tmp[1];
    }
    $out['RESULT'] = $res;
}

function Scan()
{
    $upnp = new Upnp();
    print('searching...' . PHP_EOL);
    $everything = $upnp->discover();
    $result = [];

    foreach ($everything as $device) {
        //print_r($device);  //uncomment to see all available array elements for a device.

        $info = $device['description']['device'];

        // print array_search(, array_column( $result, 'ADDRESS'));
        if (!array_search_result($result, 'UUID', $info["UDN"])) {
            $result[] = [
                "ID" => $info["UDN"],
                "TITLE" => $info["friendlyName"],
                "ADDRESS" => $info["presentationURL"],
                "UUID" => $info["UDN"],
                "DESCRIPTION" => is_array($info["modelDescription"]) ? implode(',', $info["modelDescription"]) : $info["modelDescription"],
                "TYPE" => explode(":", $info["deviceType"])[3],
                "LOGO" => getDefImg($info),
                "SERIAL" => $info["serialNumber"],
                "MANUFACTURERURL" => $info["manufacturerURL"],
                "UPDATED" => '',
                "MODEL" => $info["modelName"],
                "MANUFACTURER" => $info["manufacturer"],
                "IP" => getIp($info),
            ];
        }
    }
    /*
    print("<pre>");
    print_r($result);
     print("</pre>");
    */
    return $result;
}

function array_search_result($array, $key, $value)
{
    //  global $result;
    foreach ($array as $k => $v) {
        if (array_key_exists($key, $v) && ($v[$key] == $value)) {
            return true;
        }
    }
    // return $result;;
}


function getIp($dev)
{
    $result = explode(":", $dev["presentationURL"])[1];
    return str_replace("//", "", $result);
}

function getDefImg($dev)
{
//print ("<pre>DIR: " .DIR_MODULES.$this->name );
    if ($dev["manufacturer"] == "Google Inc." && $dev["modelName"] == "Eureka Dongle") {
        return "/templates/SSDPFinder/img/chromecast.png";
    } elseif (($dev["manufacturer"] == "LG Electronics." || $dev["manufacturer"] == "LG Electronics") && ($dev["modelName"] == "LG TV" || $dev["modelName"] == "LG Smart TV")) {
        return "/templates/SSDPFinder/img/tv.png";
    } elseif ($dev["manufacturer"] == "Synology" || $dev["manufacturer"] == "Synology Inc") {
        return "/templates/SSDPFinder/img/synology.png";
    } elseif ($dev["manufacturer"] == "Emby" && $dev["modelName"] == "Emby") {
        return $dev["presentationURL"] . $dev["iconList"]["icon"]["4"]["url"];
    } elseif ($dev["manufacturer"] == "Linksys" || $dev["manufacturer"] == "Cisco") {
        return "/templates/SSDPFinder/img/router.png";
    } elseif ($dev["manufacturer"] == "XBMC Foundation") {
        return "/templates/SSDPFinder/img/kodi.png";
    }elseif ($dev["manufacturer"] == "Bubblesoft") {
        return "/templates/SSDPFinder/img/bubleupnp.png";
    }elseif ($dev["manufacturer"] == "BlackBerry") {
        return "/templates/SSDPFinder/img/blackberry.jpg";
    }elseif ($dev["manufacturer"] == "ASUSTeK Corporation" || $dev["manufacturer"] == "ASUSTeK Computer Inc.") {
        return "/templates/SSDPFinder/img/ASUSRouter.png";
    }elseif ($dev["manufacturer"] == "HIKVISION") {
        return "/templates/SSDPFinder/img/hikvision.jpg";
    }elseif ($dev["manufacturer"] == "Samsung Electronics") {
        return "/templates/SSDPFinder/img/samsung_printer.png";
    }
    else  {
    // return $dev["presentationURL"] . $dev["iconList"]["icon"]["0"]["url"];
    return "/templates/SSDPFinder/img/dlna.png";
    }
    //
    //  return $result;
}





