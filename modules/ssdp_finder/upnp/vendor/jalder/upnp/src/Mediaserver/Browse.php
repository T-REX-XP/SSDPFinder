<?php

namespace jalder\Upnp\Mediaserver;
use jalder\Upnp\Mediaserver;


class Browse
{

    public $ctrlurl;
    private $upnp;

    public function __construct($server)
    {
        $this->upnp = new Mediaserver();
        $control_url = str_ireplace("Location:", "", $server);
        $xml=simplexml_load_file($control_url);
            foreach($xml->device->serviceList->service as $service){
                if($service->serviceId == 'urn:upnp-org:serviceId:ContentDirectory'){
                    $chek_url = (substr($service->controlURL,0,1));
                    if ($chek_url == '/') {
                       $this->ctrlurl = ($this->upnp->baseUrl($control_url,True).$service->controlURL);
                     } else {
                        $this->ctrlurl = ($this->upnp->baseUrl($control_url,True).'/'.$service->controlURL);
                    }
                }
            }
    }

    //BrowseDirectChildren or BrowseMetadata
    public function browse($base = '0', $browseflag = 'BrowseDirectChildren', $start = 0, $count = 0)   {
        $response = $this->browsexml($base = '0', $browseflag = 'BrowseDirectChildren', $start = 0, $count = 0);
	//print_r($response);
        $alldirectories = array();
        $allfiles = array();
        $alldirectories = array_merge($alldirectories, $response);
        foreach ( $alldirectories as $dirname ) {
            // получили список директорий
            $response = $this->browsexml($base = $dirname['id'], $browseflag = 'BrowseDirectChildren', $start = 0, $count = 0);
            if ($response){
                    $alldirectories = array_merge($alldirectories, $response);
            }
        }
        return $alldirectories;
        
    }
 
   //запрос на получение хмл файла от устройства списком папок с их ИД
    public function browsexml($base = '0', $browseflag = 'BrowseDirectChildren', $start = 0, $count = 0) {
        libxml_use_internal_errors(true); //is this still needed?
        $args = array(
            'ObjectID'=>$base,
            'BrowseFlag'=>$browseflag,
            'Filter'=>'*',
            'StartingIndex'=>$start,
            'RequestedCount'=>$count,
            'SortCriteria'=>'',
        );
        $response = $this->upnp->sendRequestToDevice('Browse', $args, $this->ctrlurl, $type = 'ContentDirectory');
	//print_r($response);
        if($response){
            $doc = new \DOMDocument();
            $doc->loadXML($response);
	    //$doc->save("test.xml");
            $containers = $doc->getElementsByTagName('container');
            $directories = array();
            foreach($containers as $container){
                foreach($container->attributes as $attr){
                    if($attr->name == 'id'){
                        $id = $attr->nodeValue;
                    }
                    if($attr->name === 'parentID'){
                        $parentId = $attr->nodeValue;
                    }
                }
                $directories[$id]['parentID'] = $parentId;
                $directories[$id]['id'] = $id;
                foreach($container->childNodes as $property){
                    foreach($property->attributes as $attr){
                    }
                    $directories[$id][$property->nodeName] = $property->nodeValue;
                }
            }
          return $directories;
        }
        return false;
    }

 //запрос на получение хмл файла от устройства списком папок с их ИД
    public function browsexmlfiles($base = '0', $browseflag = 'BrowseDirectChildren', $start = 0, $count = 0) {
        libxml_use_internal_errors(true); //is this still needed?
        $args = array(
            'ObjectID'=>$base,
            'BrowseFlag'=>$browseflag,
            'Filter'=>'*',
            'StartingIndex'=>$start,
            'RequestedCount'=>$count,
            'SortCriteria'=>'',
        );
        $response = $this->upnp->sendRequestToDevice('Browse', $args, $this->ctrlurl, $type = 'ContentDirectory');
        $doc = new \DOMDocument();
        $doc->loadXML($response);
        $files = array();
        $items = $doc->getElementsByTagName('item');
        foreach($items as $i=>$item){
          $link=$item->getElementsByTagName( "res" );
          $link = $link->item(0)->nodeValue;
          $title=$item->getElementsByTagName( "title" );
          $title = $title->item(0)->nodeValue;   
          $creator=$item->getElementsByTagName( "creator" );
          $creator = $creator->item(0)->nodeValue;
          $genre=$item->getElementsByTagName( "genre" );
          $genre = $genre->item(0)->nodeValue;      
          $files[$i]['genre'] = $genre;
          $files[$i]['link'] = $link;
          $files[$i]['title'] = $title;
          $files[$i]['creator'] = $creator;

        }
     return $files;
 }
}