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
        if (!array_search_result($result, 'UUID', $info["UDN"]) && !is_null($info["UDN"])) {
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
$result = "/templates/SSDPFinder/img/".$dev["manufacturer"].".png";
    if (!$result) {
        return "/templates/SSDPFinder/img/unknow.png";
    }else  {
     return $result;
    }
}





