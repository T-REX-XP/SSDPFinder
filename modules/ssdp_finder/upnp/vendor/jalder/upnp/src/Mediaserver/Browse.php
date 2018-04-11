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
    public function browse($base = 'musicdb://', $browseflag = 'BrowseDirectChildren', $start = 0, $count = 10000)
    {
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
			$doc->save("test.xml");
            $containers = $doc->getElementsByTagName('container');
            $items = $doc->getElementsByTagName('item');
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
                foreach($container->childNodes as $property){
                    foreach($property->attributes as $attr){
                    }
                    $directories[$id][$property->nodeName] = $property->nodeValue;
                }
            }
            foreach($items as $item){
                foreach($item->attributes as $attr){
                    if($attr->name == 'id'){
                        $id = $attr->nodeValue;
                    }
                }
                foreach($item->childNodes as $property){
                    if($property->nodeName === 'res'){
                        $att_length = $property->attributes->length;
                        for($i = 0; $i < $att_length; ++$i){
                            if($property->attributes->item($i)->name === 'protocolInfo' && strpos($property->attributes->item($i)->value, 'video')){
                                $directories[$id]['video'] = $property->nodeValue;
                            }
                        }
                        
                    }
                    $directories[$id][$property->nodeName] = $property->nodeValue;
                }
            }
            return $directories;
        }
        return false;
    }
}
