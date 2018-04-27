<?php

require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/autoload.php');

use jalder\Upnp\Mediaserver;

$adress = $this->getProperty("CONTROLADDRESS");

$mediaserver = new Mediaserver();
$browse = new Mediaserver\Browse($adress);
$directories = $browse->browse();
foreach($directories as $list){
    $files = $browse->browsexmlfiles($list['id']);
    foreach($files as $file){
        //print_r ($file ['link']);
        //print_r ($file ['title']);
        //print_r ($file ['genre']);
        //print_r ($file ['creator']);
        $Record = SQLSelectOne("SELECT * FROM mediaservers_playlist WHERE URL_LINK='".$file ['link']."'");
        $Record['URL_LINK'] = $file ['link'];
        $Record['TITLE'] = mb_convert_encoding($text, 'utf-8', mb_detect_encoding($file ['title']));
        $Record['DESCRIPTION'] = mb_convert_encoding($text, 'utf-8', mb_detect_encoding($file ['creator']));
        $Record['GENRE'] = mb_convert_encoding($text, 'utf-8', mb_detect_encoding($file ['genre']));
        $Record['LINKED_OBJECT'] = $this->title;
        SQLUpdateInsert('mediaservers_playlist', $Record);
    }
   }