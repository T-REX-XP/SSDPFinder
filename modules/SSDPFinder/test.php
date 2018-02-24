<?php
require_once('ssdp/upnp.php');
//require('vendor/autoload.php');
//use jalder\Upnp\Upnp;
$upnp = new Upnp();
print('searching...'.PHP_EOL);
$everything = $upnp->discover();
if(!count($everything)){
	print_r('no upnp devices found'.PHP_EOL);
}
foreach($everything as $device){
	$current_dev = $device['description']['device'];
	print '<pre>';
	print getDefImg($current_dev);
	print '<pre>';
	print "Services: ". getServices($current_dev);
//	return;
	//print_r($current_dev);
	//u	ncomment to see all available array elements for a device.
		   // 	$info = $device['description']['device'];
	// 	$summary = $info['friendlyName'].', '.$info['modelName'].', '.$info['UDN'];
	// 	print($summary.PHP_EOL);
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

function getDefImg($dev)
{
	$baseUrl = $dev["presentationURL"];
	if( !empty($baseUrl) && endsWith($baseUrl,"/")){
		$baseUrl=  rtrim($baseUrl,"/");//$baseUrl . ;
	}

	if( $dev["iconList"]["icon"]){
		$icons = $dev["iconList"]["icon"];

		$searchedValue = 48; // Value to search.
		$index48 = SearchArray($icons,"width",48);

		$img48 =""; //empty by def
		if($index48 !=false){
			$img48 = $icons[$index48]["url"];
		}else{
			$img48 = $icons[0]["url"];
		}
		return $baseUrl . $img48;
		
	}else{
		return  "Icons not found... (((";
	}
}
?>