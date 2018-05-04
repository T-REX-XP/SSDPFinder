<?php

require(dirname(__FILE__).'/../../../autoload.php');

use jalder\Upnp\Renderer;

$renderer = new Renderer(); 
$remote = new Renderer\Remote('http://192.168.1.20:2869/upnphost/udhisapi.dll?content=uuid:0434893d-7702-4069-b18b-aeb90e3ea3f5');
$info = $remote->getPosition();
// сохраняет данные в файл
//$file = 'people.txt';
//file_put_contents($file, $info);
// создает документ хмл
$doc = new \DOMDocument();
//  загружет его
$doc->loadXML($info);
//  выбирает поле соответсвтуещее
$result = $doc->getElementsByTagName('Track');
$trackn = $result->item(0)->nodeValue;
print ($trackn);       
$result = $doc->getElementsByTagName('TrackURI');
$trackurl = $result->item(0)->nodeValue;
print ($trackurl); 
$result = $doc->getElementsByTagName('RelTime');
$tracktime = $result->item(0)->nodeValue;
print ($tracktime); 

return $track;
