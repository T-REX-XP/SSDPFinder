<?php
require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/autoload.php');
use jalder\Upnp\Renderer;
$renderer = new Renderer();
$adress = $this->getProperty("CONTROLADDRESS");
$remote = new Renderer\Remote($adress);
$playNextUrl = $this->getProperty("playNextUrl");
if (strpos($playUrl,'youtube')>1 AND strpos($playUrl,'youtube')<20) {
    $res1=parse_url($playUrl, PHP_URL_QUERY);
    $res2=parse_str($res1,$res); 
    $res=$res['v'];
    $newurl='https://hms.lostcut.net/youtube/g.php?v='.$res.'&link_only=1&max_height=720'; 
    $playUrl=file_get_contents($newurl);
};
$result = $remote->setNext($playNextUrl);
