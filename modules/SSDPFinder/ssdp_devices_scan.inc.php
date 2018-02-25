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
              //  "ID" => $info["UDN"],
                "TITLE" => $info["friendlyName"],
                "ADDRESS" => $info["presentationURL"],
                "UUID" => $info["UDN"],
                "DESCRIPTION" => is_array($info["modelDescription"]) ? implode(',', $info["modelDescription"]) : $info["modelDescription"],
                "TYPE" => explode(":", $info["deviceType"])[3],
                "LOGO" => getDefImg($device),//$info
                "SERIAL" => $info["serialNumber"],
                "MANUFACTURERURL" => $info["manufacturerURL"],
                "UPDATED" => '',
                "MODEL" => $info["modelName"],
                "MANUFACTURER" => $info["manufacturer"],
                "IP" => getIp($device),
                "SERVICES"=> getServices($info),
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


function getIp($device)
{
    $baseUrl = $device["location"];
	if( !empty($baseUrl) ){
		$parsed_url = parse_url($baseUrl);
		$baseUrl = $parsed_url['scheme'].'://'.$parsed_url['host'].':'.$parsed_url['port'];
    }
    
    return  $baseUrl;
}

function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

function getServices($dev){
	$services = $dev["serviceList"]["service"];
	$result = array();
	if($services){
		$servType = $services["serviceType"];
		//print "Serv Type: " . $servType;
		if(startsWith($servType,"urn") ){ // && empty($servType)
			//print "First cond->";
			$name = explode(":", $servType)[3];
			array_push($result,$name);
		}
		else{
		
			foreach($services as $k => $v){
					$value = $v["serviceType"];
				
						$name = explode(":", $value)[3];
					
						array_push($result,$name);
					}
			}
	}
	return implode(",",$result);
}

function endsWith($haystack, $needle)
{
	return $needle === '' || substr_compare($haystack, $needle, -strlen($needle)) === 0;
}

function SearchArray($array, $searchIndex, $searchValue)
{
    if (!is_array($array) || $searchIndex == '')
        return false;

    foreach ($array as $k => $v)
    {
        if (is_array($v) && array_key_exists($searchIndex, $v) && $v[$searchIndex] == $searchValue)
            return $k;
    }

    return false;
}

function getDefImg($device)
{
    $dev = $device['description']['device'];
	$baseUrl = getIp($device);

	if($baseUrl && $dev["iconList"]["icon"]){
		$icons = $dev["iconList"]["icon"];

        $img48 =""; //empty by def
        if($icons["url"]){
            $img48 =$icons["url"];
        }
        else{
            $searchedValue = 48; // Value to search.
            $index48 = SearchArray($icons,"width",48);
    
           
            if($index48 !=false){
                $img48 = $icons[$index48]["url"];
            }else{
                $img48 = $icons[0]["url"];
            }
        }
        return $baseUrl . $img48;
		
	}else{
        $type =explode(":", $dev["deviceType"])[3];
		return "/templates/SSDPFinder/img/".$type. ".png";//"Icons not found... (((";
	}
}
