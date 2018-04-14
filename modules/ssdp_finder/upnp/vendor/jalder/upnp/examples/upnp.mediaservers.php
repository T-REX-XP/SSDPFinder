<?php

require(dirname(__FILE__).'/../../../autoload.php');

use jalder\Upnp\Mediaserver;

$mediaserver = new Mediaserver();
$browse = new Mediaserver\Browse('http://192.168.1.20:2869/upnphost/udhisapi.dll?content=uuid:cc3558a5-ce9c-4a9e-a898-38cd8e4ade29');
    $directories = $browse->browse();
    $info = $server['description']['device'];
    $summary = $info['friendlyName'].', '.$info['modelDescription'].', '.$info['modelName'].', '.$info['UDN'];
    //print($server);
    //print_r($directories);
    foreach($directories as $list){
      print_r('  '.$list['dc:title']."\n\r");
      //print_r('id - '.$list['id']);
      $files = $browse->browsexmlfiles($list['id']);
      foreach($files as $file){
           print_r ($file ['link']);
           print_r ($file ['title']);
           print_r ($file ['genre']);
           print_r ($file ['creator']);
    }
   }

