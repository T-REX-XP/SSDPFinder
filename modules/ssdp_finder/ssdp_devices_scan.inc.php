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
if ($res[0]['UUID']) {
    //paging($res, 100, $out); // search result paging
    $total = count($res);
    for ($i = 0; $i < $total; $i++) {
        // some action for every record if required
        $tmp = explode(' ', $res[$i]['UPDATED']);
        $res[$i]['UPDATED'] = fromDBDate($tmp[0]) . " " . $tmp[1];
    }
    $out['RESULT'] = $res;
}

function Scan(){
    $upnp = new Upnp();
    #print('searching...' . PHP_EOL);
    $everything = $upnp->discover();
    $result = [];
    $table_name='ssdp_devices';

    foreach ($everything as $deviceInfo) {

        $device= $deviceInfo['description']['device'];

       // print_r($device);
        $uuid = $device["UDN"];
        $existed = SQLSelectOne("SELECT * FROM $table_name WHERE UUID='".$uuid."'");
        $presenturl = $device["presentationURL"];
        $control_url = str_ireplace("Location:", "", $deviceInfo['location']);
        if (!$device["presentationURL"]){
            $presenturl='http://'.getIp($control_url,false);
            } 
        if (!array_search_result($result, 'UUID', $uuid) && !is_null($uuid) && !($existed)) {
      // print($device["iconList"]);
      $logo= getDefImg($deviceInfo["location"],$device);
        $result[] = [
            "ID" => $existed["ID"], //existed id Majordomo
            "TITLE" => $device["friendlyName"],//friendly name
            "ADDRESS" => $presenturl ,//presentation url (web UI of device),//presentation url (web UI of device)
            "UUID" => $uuid,
            "LOGO" => $logo,//Logo 
            "DESCRIPTION" => $device["modelDescription"] ,//. $deviceInfo['server'],//description get from xml or field "server"
            "TYPE" => explode(":", $device["deviceType"])[3],//DeviceType
            "SERIAL" => $device["serialNumber"],//serialnumber
            "MANUFACTURERURL" => $device["manufacturerURL"],//manufacturer url
            "UPDATED" => '',
            "MODEL" => $device["modelName"],//model
            "MODELNUMBER" => $device["modelNumber"],//modelNumber
            "MANUFACTURER" => $device["manufacturer"],//Manufacturer
            "SERVICES"=> getServices($device),//list services of device
            "CONTROLADDRESS"=> $control_url,//list services of device
        ];
        $_SESSION[$uuid] = $logo;
        session_write_close();
       
    }
    }
   
    return $result;
}

function array_search_result($array, $key, $value){
    foreach ($array as $k => $v) {
        if (array_key_exists($key, $v) && ($v[$key] == $value)) {
            return true;
        }
    }
}


function getIp($baseUrl,$withPort) {
    if( !empty($baseUrl) ){
        $parsed_url = parse_url($baseUrl);
        if($withPort ==true){
            $baseUrl = $parsed_url['scheme'].'://'.$parsed_url['host'].':'.$parsed_url['port']; 
        }else{
            $baseUrl = $parsed_url['host'];
        }
    }
    return  $baseUrl;
}

//получаем hostname адрес локального компьютера
function getLocalIp() { 
    return gethostbyname(trim(`hostname`)); 
}


function startsWith($haystack, $needle){
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

function getServices($device){
    $result = array();
    if(isset($device["serviceList"]["service"]["serviceType"])){
        $name = explode(":", $device["serviceList"]["service"]["serviceType"])[3];
        array_push($result,$name);
    }
    else{
        foreach($device["serviceList"]["service"] as $type)
        {
            $name = explode(":", $type["serviceType"])[3];
            array_push($result,$name);
        }
    }
	return implode(",",$result);
}
function endsWith($haystack, $needle){
	return $needle === '' || substr_compare($haystack, $needle, -strlen($needle)) === 0;
}

function SearchArray($array, $searchIndex, $searchValue){
    if (!is_array($array) || $searchIndex == '')
        return false;

    foreach ($array as $k => $v)
    {
        if (is_array($v) && array_key_exists($searchIndex, $v) && $v[$searchIndex] == $searchValue)
            return $k;
    }

    return false;
}


function img64($icon){
    return !is_null($icon) && $icon["width"] && $icon["url"] && $icon["width"] == 48;
};

function get_web_page( $url ) {
    $res = array();
    $options = array( 
        CURLOPT_RETURNTRANSFER => true,     // return web page 
        CURLOPT_HEADER         => false,    // do not return headers 
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects 
        CURLOPT_USERAGENT      => "spider", // who am i 
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect 
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect 
        CURLOPT_TIMEOUT        => 120,      // timeout on response 
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects 
    ); 
    $ch      = curl_init( $url ); 
    curl_setopt_array( $ch, $options ); 
    $content = curl_exec( $ch ); 
    $err     = curl_errno( $ch ); 
    $errmsg  = curl_error( $ch ); 
    $header  = curl_getinfo( $ch ); 
    curl_close( $ch ); 

    $res['content'] = $content;     
    $res['url'] = $header['url'];
    return $res; 
}


function getDefImg($control_url,$device) {
    $current="";
    $type="";
    $path="";
    $url ="";
 
    $baseUrl = getIp($control_url,True);
    $icons =$device["iconList"]["icon"];
    if (!$icons){
	    return "/templates/ssdp_finder/img/".explode(":", $device["deviceType"])[3] . ".png";//"Icons not found
    } else {
        if(isset($icons["url"])){
            $url = $icons["url"];
        }
        else if(is_array($icons)){
            $arrImg= array_filter($icons, "img64");
            $url= isset($arrImg[0]["url"]) ? $arrImg[0]["url"]: $icons[0]["url"];
        }
    }
    $path = $baseUrl . $url;
    $current =get_web_page($path);
    $type = pathinfo($path, PATHINFO_EXTENSION);
    return 'data:image/' . $type . ';base64,' . base64_encode($current['content']);
}
