<?php

require(dirname(__FILE__).'/../../../autoload.php');

use jalder\Upnp\Mediaserver;

$mediaserver = new Mediaserver();

print('searching...'.PHP_EOL);

$servers = $mediaserver->discover();

if(!count($servers)){
    print_r('no upnp mediaservers found'.PHP_EOL);
}

foreach($servers as $server){
    $browse = new Mediaserver\Browse($server);
    $directories = $browse->browse();
    $info = $server['description']['device'];
    $summary = $info['friendlyName'].', '.$info['modelDescription'].', '.$info['modelName'].', '.$info['UDN'];
    //print($server);
    //print_r($directories);
    foreach($directories as $list){
      print_r($list);
      //print_r($list['dc:title']); // выводит имена папок
     //Array ( [parentID] => 0 [dc:title] => Списки воспроизведения [upnp:class] => object.container )

      
   }
}

