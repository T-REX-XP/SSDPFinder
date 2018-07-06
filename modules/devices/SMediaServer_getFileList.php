<?php

require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/autoload.php');

use jalder\Upnp\Mediaserver;
// массивы для определения типов файлов
$video = [".3g2",".3gp",".3gp2",".3gpp",".3gpp2",".asf",".asx",".avi",".bin",".dat",".drv",".f4v",".flv",".gtp",".h264",".m4v",".mkv",".mod",".moov",".mov",".mp4",".mpeg",".mpg",".mts",".rm",".rmvb",".spl",".srt",".stl",".swf",".ts",".vcd",".vid",".vid",".vid",".vob",".webm",".wm",".wmv",".yuv"];
$audio = [".aac",".ac3",".aif",".aiff",".amr",".aob",".ape",".asf",".aud",".aud",".aud",".aud",".awb",".bin",".bwg",".cdr",".flac",".gpx",".ics",".iff",".m",".m3u",".m3u8",".m4a",".m4b",".m4p",".m4r",".mid",".midi",".mod",".mp3",".mp3",".mp3",".mpa",".mpp",".msc",".msv",".mts",".nkc",".ogg",".ps",".ra",".ram",".sdf",".sib",".sln",".spl",".srt",".srt",".temp",".vb",".wav",".wav",".wave",".wm",".wma",".wpd",".xsb",".xwb"];

$adress = $this->getProperty("CONTROLADDRESS");
$this->setProperty("havedfiles",'0');
$mediaserver = new Mediaserver();
$browse = new Mediaserver\Browse($adress);
$directories = $browse->browse();
$count=0;
foreach($directories as $list){
    $files = $browse->browsexmlfiles($list['id']);
    foreach($files as $file){
        $link = $file ['link'];
        //print_r ($file ['link']);
        //print_r ($file ['title']);
        //print_r ($file ['genre']);
        //print_r ($file ['creator']);
        $Record = SQLSelectOne("SELECT * FROM mediaservers_playlist WHERE URL_LINK='".$file ['link']."'");
        $Record['URL_LINK'] = $link;
        $tcode = mb_detect_encoding($file ['title']);
        $Record['TITLE'] = iconv($tcode, "UTF-8", $file ['title']);
        $Record['DESCRIPTION'] = $file ['creator'];
        $ext_file = substr(strrchr($file ['link'], "."),0);
        if (in_array($ext_file, $video)) {
            $Record['GENRE'] = 'Видео';
            }else if (in_array($ext_file, $audio)) {
            $Record['GENRE'] = 'Аудио';
            }else {
            $Record['GENRE'] = 'Изображения';
            };
        $Record['LINKED_OBJECT'] = $this->description;
        SQLUpdateInsert('mediaservers_playlist', $Record);
        $count = $count+1;
    }
   }
$this->setProperty("havedfiles",$count);
